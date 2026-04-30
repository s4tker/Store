<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagenes;
use App\Models\ProductoVariantes;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        // datos del dashboard principal
        $rootCategories = Categoria::with('subcategorias')
            ->select(['Id', 'Nombre', 'Slug', 'ParentId'])
            ->whereNull('ParentId')
            ->orderBy('Nombre')
            ->get();

        $allCategories = Categoria::with('padre')
            ->select(['Id', 'Nombre', 'Slug', 'ParentId'])
            ->orderBy('Nombre')
            ->get();

        $brands = Marca::query()
            ->select(['Id', 'Nombre', 'Slug'])
            ->orderBy('Nombre')
            ->get();

        $products = Producto::query()
            ->select(['Id', 'Nombre', 'Slug', 'Descripcion', 'CategoriaId', 'MarcaId', 'Estado'])
            ->with([
                'categoria.padre',
                'marca',
                'imagenes',
                'variantes' => fn ($query) => $query->orderBy('Id'),
            ])
            ->orderByDesc('Id')
            ->get();

        return view('Admin.admin', [
            'Search' => '',
            'AdminNavLabel' => 'Panel Admin',
            'AdminNavRoute' => route('admin.dashboard'),
            'HideNavbarSearch' => true,
            'HideNavbarOrders' => true,
            'HideNavbarCart' => true,
            'NavbarMobileTriggerAction' => 'ToggleAdminNav(true)',
            'Categorias' => $rootCategories,
            'TodasLasCategorias' => $allCategories,
            'Marcas' => $brands,
            'Productos' => $products,
            'ProductosAdmin' => $this->buildProductEditorPayloads($products),
        ]);
    }

    public function products()
    {
        // datos de la vista independiente de productos
        $rootCategories = Categoria::with('subcategorias')
            ->select(['Id', 'Nombre', 'Slug', 'ParentId'])
            ->whereNull('ParentId')
            ->orderBy('Nombre')
            ->get();

        $allCategories = Categoria::with('padre')
            ->select(['Id', 'Nombre', 'Slug', 'ParentId'])
            ->orderBy('Nombre')
            ->get();

        $brands = Marca::query()
            ->select(['Id', 'Nombre', 'Slug'])
            ->orderBy('Nombre')
            ->get();

        $products = Producto::query()
            ->select(['Id', 'Nombre', 'Slug', 'Descripcion', 'CategoriaId', 'MarcaId', 'Estado'])
            ->with([
                'categoria.padre',
                'marca',
                'imagenes',
                'variantes' => fn ($query) => $query->orderBy('Id'),
            ])
            ->orderByDesc('Id')
            ->get();

        return view('Admin.products.manage', [
            'Search' => '',
            'AdminNavLabel' => 'Volver',
            'AdminNavRoute' => route('admin.dashboard'),
            'HideNavbarMobileTrigger' => true,
            'HideNavbarSearch' => true,
            'HideNavbarOrders' => true,
            'HideNavbarCart' => true,
            'Categorias' => $rootCategories,
            'TodasLasCategorias' => $allCategories,
            'Marcas' => $brands,
            'Productos' => $products,
            'ProductosAdmin' => $this->buildProductEditorPayloads($products),
        ]);
    }

    public function users()
    {
        $roles = Role::query()
            ->get(['Id', 'Nombre'])
            ->filter(fn (Role $role) => in_array($this->resolveRoleKey($role->Nombre), ['admin', 'usuario'], true))
            ->sortBy(fn (Role $role) => $this->resolveRoleSortOrder($role->Nombre))
            ->values();

        $allUsers = User::query()
            ->select(['Id', 'Alias', 'Correo', 'CreatedAt'])
            ->with('roles')
            ->orderByDesc('Id')
            ->get();

        $adminUsers = $allUsers->filter(function (User $user) {
            return $this->userHasAdminRole($user);
        })->values();

        return view('Admin.user.user', [
            'Search' => '',
            'AdminNavLabel' => 'Volver',
            'AdminNavRoute' => route('admin.dashboard'),
            'HideNavbarMobileTrigger' => true,
            'HideNavbarSearch' => true,
            'HideNavbarOrders' => true,
            'HideNavbarCart' => true,
            'RolesUsuarios' => $roles,
            'UsuariosAdmin' => $this->buildUserManagementPayloads($adminUsers),
            'UsuariosBusqueda' => $this->buildUserManagementPayloads($allUsers),
        ]);
    }

    public function statistics()
    {
        $customers = User::query()
            ->select(['Id', 'Alias', 'Nombre', 'Apellidos', 'Correo', 'CreatedAt'])
            ->with('roles')
            ->orderByDesc('Id')
            ->get()
            ->reject(fn (User $user) => $this->userHasAdminRole($user))
            ->values();

        return view('Admin.estadisticas.estadisticas', [
            'Search' => '',
            'AdminNavLabel' => 'Volver',
            'AdminNavRoute' => route('admin.dashboard'),
            'HideNavbarMobileTrigger' => true,
            'HideNavbarSearch' => true,
            'HideNavbarOrders' => true,
            'HideNavbarCart' => true,
            'ClientesStats' => $this->buildCustomerStatisticsPayloads($customers),
        ]);
    }

    public function storeCategory(Request $request)
    {
        return $this->saveCategory($request);
    }

    public function updateCategory(Request $request, Categoria $categoria)
    {
        return $this->saveCategory($request, $categoria);
    }

    public function destroyCategory(Categoria $categoria)
    {
        $hasChildren = Categoria::where('ParentId', $categoria->Id)->exists();
        $hasProducts = Producto::where('CategoriaId', $categoria->Id)->exists();

        if ($hasChildren) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar una categoría que todavía tiene subcategorías.',
            ], 422);
        }

        if ($hasProducts) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar una categoría que tiene productos registrados.',
            ], 422);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }

    public function storeBrand(Request $request)
    {
        return $this->saveBrand($request);
    }

    public function updateBrand(Request $request, Marca $marca)
    {
        return $this->saveBrand($request, $marca);
    }

    public function destroyBrand(Marca $marca)
    {
        if (Producto::where('MarcaId', $marca->Id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar una marca que tiene productos asociados.',
            ], 422);
        }

        $marca->delete();

        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada correctamente.',
        ]);
    }

    public function storeProduct(Request $request)
    {
        return $this->saveProduct($request);
    }

    public function storeUser(Request $request)
    {
        return $this->saveUserAccount($request);
    }

    public function updateProduct(Request $request, Producto $producto)
    {
        return $this->saveProduct($request, $producto);
    }

    public function updateUser(Request $request, User $usuario)
    {
        return $this->saveUserAccount($request, $usuario);
    }

    public function destroyProduct(Producto $producto)
    {
        DB::transaction(function () use ($producto) {
            $producto->load('imagenes');

            foreach ($producto->imagenes as $imagen) {
                if ($imagen->Url) {
                    Storage::disk('public')->delete($imagen->Url);
                }
            }

            $producto->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado correctamente.',
        ]);
    }

    public function destroyUser(User $usuario)
    {
        if ($this->isProtectedAdminUser($usuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar una cuenta con rol administrador desde esta vista.',
            ], 422);
        }

        if ((int) $usuario->Id === (int) auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propia cuenta desde esta sección.',
            ], 422);
        }

        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }

    protected function saveProduct(Request $request, ?Producto $product = null)
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'max:120'],
            'Descripcion' => ['nullable', 'string'],
            'MarcaId' => ['required', 'integer', Rule::exists('Marcas', 'Id')],
            'CategoriaId' => ['required', 'integer', Rule::exists('Categorias', 'Id')],
            'SubCategoriaId' => ['nullable', 'integer', Rule::exists('Categorias', 'Id')],
            'Precio' => ['required', 'numeric', 'min:0.10'],
            'PrecioOferta' => ['nullable', 'numeric', 'min:0'],
            'Stock' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'Estado' => ['nullable', Rule::in(['Activo', 'Inactivo'])],
            'Imagenes' => ['nullable', 'array'],
            'Imagenes.*' => ['image', 'max:4096'],
            'Imagen' => ['nullable', 'image', 'max:4096'],
            'RemoveImagenes' => ['nullable', 'array'],
            'RemoveImagenes.*' => ['integer'],
            'attr_nombre' => ['nullable', 'array'],
            'attr_nombre.*' => ['nullable', 'string', 'max:50'],
            'attr_valor' => ['nullable', 'array'],
            'attr_valor.*' => ['nullable', 'string', 'max:100'],
        ]);

        if (! empty($data['PrecioOferta']) && (float) $data['PrecioOferta'] >= (float) $data['Precio']) {
            return response()->json([
                'success' => false,
                'message' => 'El precio oferta debe ser menor que el precio normal.',
            ], 422);
        }

        $categoriaFinalId = $this->resolveCategoryId($data);

        if ($categoriaFinalId instanceof \Illuminate\Http\JsonResponse) {
            return $categoriaFinalId;
        }

        $product = DB::transaction(function () use ($request, $data, $categoriaFinalId, $product) {
            $product ??= new Producto();

            $product->fill([
                'Nombre' => trim($data['Nombre']),
                'Slug' => $this->uniqueSlug('Productos', $data['Nombre'], $product->Id),
                'Descripcion' => trim((string) ($data['Descripcion'] ?? '')),
                'CategoriaId' => $categoriaFinalId,
                'MarcaId' => $data['MarcaId'],
                'Estado' => $data['Estado'] ?? $product->Estado ?? 'Activo',
            ]);
            $product->save();

            $variant = $product->variantes()->orderBy('Id')->first();

            if (! $variant) {
                $variant = new ProductoVariantes([
                    'ProductoId' => $product->Id,
                    'Sku' => $this->generateSku($product->Nombre),
                ]);
            }

            $variant->fill([
                'Precio' => $data['Precio'],
                'PrecioOferta' => $data['PrecioOferta'] ?? null,
            ]);
            $variant->save();

            DB::table('Inventario')->updateOrInsert(
                ['VarianteId' => $variant->Id],
                ['Stock' => $data['Stock'] ?? 0]
            );

            DB::table('VarianteAtributos')->where('VarianteId', $variant->Id)->delete();

            $this->persistAttributes(
                $variant->Id,
                $data['attr_nombre'] ?? [],
                $data['attr_valor'] ?? []
            );

            $this->syncProductImages(
                $request,
                $product,
                collect($data['RemoveImagenes'] ?? [])->map(fn ($id) => (int) $id)->all()
            );

            return $product->fresh(['categoria.padre', 'marca', 'imagenes', 'variantes']);
        });

        $isUpdate = $request->route('producto') !== null;

        return response()->json([
            'success' => true,
            'message' => $isUpdate ? 'Producto actualizado correctamente.' : 'Producto registrado correctamente.',
            'data' => [
                'id' => $product->Id,
                'nombre' => $product->Nombre,
                'producto' => $this->buildProductEditorPayload($product),
            ],
        ]);
    }

    protected function saveCategory(Request $request, ?Categoria $category = null)
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'max:100'],
            'TipoCategoria' => ['nullable', 'in:principal,subcategoria'],
            'ParentId' => ['nullable', 'integer', Rule::exists('Categorias', 'Id')],
        ]);

        $type = $data['TipoCategoria'] ?? 'principal';
        $parentId = $type === 'subcategoria' ? ($data['ParentId'] ?? null) : null;

        if ($type === 'subcategoria' && ! $parentId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes elegir una categoría principal para crear la subcategoría.',
            ], 422);
        }

        if ($category && $parentId === $category->Id) {
            return response()->json([
                'success' => false,
                'message' => 'Una categoría no puede ser su propia categoría padre.',
            ], 422);
        }

        $category ??= new Categoria();
        $category->fill([
            'Nombre' => trim($data['Nombre']),
            'Slug' => $this->uniqueSlug('Categorias', $data['Nombre'], $category->Id),
            'ParentId' => $parentId,
        ]);
        $category->save();

        return response()->json([
            'success' => true,
            'message' => $request->route('categoria')
                ? 'Categoría actualizada correctamente.'
                : 'Categoría registrada correctamente.',
            'data' => [
                'id' => $category->Id,
                'nombre' => $category->Nombre,
            ],
        ]);
    }

    protected function saveBrand(Request $request, ?Marca $brand = null)
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'max:100'],
        ]);

        $brand ??= new Marca();
        $brand->fill([
            'Nombre' => trim($data['Nombre']),
            'Slug' => $this->uniqueSlug('Marcas', $data['Nombre'], $brand->Id),
        ]);
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => $request->route('marca')
                ? 'Marca actualizada correctamente.'
                : 'Marca registrada correctamente.',
            'data' => [
                'id' => $brand->Id,
                'nombre' => $brand->Nombre,
            ],
        ]);
    }

    protected function saveUserAccount(Request $request, ?User $user = null)
    {
        $data = $request->validate([
            'Correo' => ['required', 'email', 'max:120', Rule::unique('Usuarios', 'Correo')->ignore($user?->Id, 'Id')],
            'Password' => [$user ? 'nullable' : 'required', 'string', 'min:6', 'max:255'],
            'RolId' => ['required', 'integer'],
        ]);

        $role = Role::query()->find((int) $data['RolId']);

        if (! $role || ! in_array($this->resolveRoleKey($role->Nombre), ['admin', 'usuario'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes asignar los roles Administrador o Usuario.',
            ], 422);
        }

        $user = DB::transaction(function () use ($data, $user, $role) {
            $user ??= new User();

            $correo = trim((string) $data['Correo']);
            $alias = str(explode('@', $correo)[0])
                ->replace(['.', ' ', '_'], '-')
                ->lower()
                ->value();

            $user->fill([
                'Alias' => $alias !== '' ? $alias : null,
                'Correo' => $correo,
            ]);

            if (trim((string) ($data['Password'] ?? '')) !== '') {
                $user->Password = Hash::make($data['Password']);
            }

            $user->save();
            $user->roles()->sync([$role->Id]);

            return $user->fresh('roles');
        });

        return response()->json([
            'success' => true,
            'message' => $request->route('usuario')
                ? 'Usuario actualizado correctamente.'
                : 'Usuario registrado correctamente.',
            'data' => [
                'id' => $user->Id,
                'correo' => $user->Correo,
            ],
        ]);
    }

    protected function resolveCategoryId(array $data)
    {
        if (empty($data['SubCategoriaId'])) {
            return (int) $data['CategoriaId'];
        }

        $isValidSubcategory = Categoria::query()
            ->where('Id', $data['SubCategoriaId'])
            ->where('ParentId', $data['CategoriaId'])
            ->exists();

        if (! $isValidSubcategory) {
            return response()->json([
                'success' => false,
                'message' => 'La subcategoría no pertenece a la categoría seleccionada.',
            ], 422);
        }

        return (int) $data['SubCategoriaId'];
    }

    protected function syncProductImages(Request $request, Producto $product, array $removeImageIds = []): void
    {
        $imagenes = $product->imagenes()->get()->keyBy('Id');

        foreach ($removeImageIds as $imageId) {
            $image = $imagenes->get($imageId);

            if (! $image) {
                continue;
            }

            if ($image->Url) {
                Storage::disk('public')->delete($image->Url);
            }

            $image->delete();
        }

        $newImages = $request->file('Imagenes', []);

        if (empty($newImages) && $request->hasFile('Imagen')) {
            $newImages = [$request->file('Imagen')];
        }

        if (empty($newImages)) {
            return;
        }

        $nextOrder = (int) ($product->imagenes()->max('Orden') ?? 0);

        foreach ($newImages as $index => $image) {
            $path = $image->store('productos', 'public');

            ProductoImagenes::create([
                'ProductoId' => $product->Id,
                'Url' => $path,
                'Orden' => $nextOrder + $index + 1,
            ]);
        }
    }

    protected function persistAttributes(int $variantId, array $names, array $values): void
    {
        foreach ($names as $index => $name) {
            $cleanName = trim((string) $name);
            $cleanValue = trim((string) ($values[$index] ?? ''));

            if ($cleanName === '' || $cleanValue === '') {
                continue;
            }

            $attributeId = DB::table('Atributos')->where('Nombre', $cleanName)->value('Id');

            if (! $attributeId) {
                $attributeId = DB::table('Atributos')->insertGetId([
                    'Nombre' => $cleanName,
                ]);
            }

            $valueId = DB::table('AtributoValores')
                ->where('AtributoId', $attributeId)
                ->where('Valor', $cleanValue)
                ->value('Id');

            if (! $valueId) {
                $valueId = DB::table('AtributoValores')->insertGetId([
                    'AtributoId' => $attributeId,
                    'Valor' => $cleanValue,
                ]);
            }

            DB::table('VarianteAtributos')->insert([
                'VarianteId' => $variantId,
                'ValorId' => $valueId,
            ]);
        }
    }

    protected function buildProductEditorPayload(Producto $product, array $stocks = [], array $attributesByVariant = []): array
    {
        $variant = $product->variantes->sortBy('Id')->first();
        $category = $product->categoria;
        $rootCategoryId = $category?->ParentId ?: $category?->Id;
        $subcategoryId = $category?->ParentId ? $category->Id : null;
        $variantId = $variant?->Id;

        return [
            'id' => $product->Id,
            'nombre' => $product->Nombre,
            'descripcion' => $product->Descripcion,
            'marca_id' => $product->MarcaId,
            'categoria_id' => $rootCategoryId,
            'subcategoria_id' => $subcategoryId,
            'estado' => $product->Estado,
            'precio' => $variant?->Precio !== null ? (string) $variant->Precio : '',
            'precio_oferta' => $variant?->PrecioOferta !== null ? (string) $variant->PrecioOferta : '',
            'stock' => $variantId ? (int) ($stocks[$variantId] ?? 0) : 0,
            'sku' => $variant?->Sku,
            'categoria_texto' => $category?->ParentId
                ? $category->padre?->Nombre . ' / ' . $category->Nombre
                : ($category?->Nombre ?? 'Sin categoría'),
            'marca_texto' => $product->marca?->Nombre ?? 'Sin marca',
            'imagenes' => $product->imagenes->map(fn ($image) => [
                'id' => $image->Id,
                'url' => $this->resolveImageUrl($image->Url),
                'orden' => $image->Orden,
            ])->values()->all(),
            'atributos' => $variantId ? ($attributesByVariant[$variantId] ?? []) : [],
        ];
    }

    protected function buildProductEditorPayloads($products): array
    {
        $firstVariants = $products
            ->map(fn (Producto $product) => $product->variantes->first())
            ->filter()
            ->values();

        $variantIds = $firstVariants->pluck('Id')->all();

        $stocks = DB::table('Inventario')
            ->whereIn('VarianteId', $variantIds)
            ->pluck('Stock', 'VarianteId')
            ->map(fn ($stock) => (int) $stock)
            ->all();

        $attributesByVariant = DB::table('VarianteAtributos as va')
            ->join('AtributoValores as av', 'av.Id', '=', 'va.ValorId')
            ->join('Atributos as a', 'a.Id', '=', 'av.AtributoId')
            ->whereIn('va.VarianteId', $variantIds)
            ->orderBy('a.Nombre')
            ->get(['va.VarianteId', 'a.Nombre as nombre', 'av.Valor as valor'])
            ->groupBy('VarianteId')
            ->map(fn ($group) => $group->map(fn ($attribute) => [
                'nombre' => $attribute->nombre,
                'valor' => $attribute->valor,
            ])->values()->all())
            ->all();

        return $products
            ->map(fn (Producto $product) => $this->buildProductEditorPayload($product, $stocks, $attributesByVariant))
            ->values()
            ->all();
    }

    protected function buildUserManagementPayload(User $user): array
    {
        $role = $user->roles->sortBy('Id')->first();

        return [
            'id' => $user->Id,
            'alias' => $user->Alias,
            'correo' => $user->Correo,
            'rol_id' => $role?->Id,
            'rol_nombre' => $this->formatRoleLabel($role?->Nombre),
            'rol_clave' => $this->resolveRoleKey($role?->Nombre),
            'creado_en' => $user->CreatedAt,
        ];
    }

    protected function buildUserManagementPayloads($users): array
    {
        return $users
            ->map(fn (User $user) => $this->buildUserManagementPayload($user))
            ->values()
            ->all();
    }

    protected function buildCustomerStatisticsPayload(User $user): array
    {
        return [
            'id' => $user->Id,
            'nombre' => trim(collect([$user->Nombre, $user->Apellidos])->filter()->implode(' ')) ?: ($user->Alias ?: 'Cliente sin nombre'),
            'correo' => $user->Correo,
            'alias' => $user->Alias,
            'creado_en' => $user->CreatedAt,
        ];
    }

    protected function buildCustomerStatisticsPayloads($users): array
    {
        return $users
            ->map(fn (User $user) => $this->buildCustomerStatisticsPayload($user))
            ->values()
            ->all();
    }

    protected function generateSku(string $name): string
    {
        do {
            $sku = strtoupper(Str::slug(Str::limit($name, 12, ''), ''));
            $sku .= '-' . Str::upper(Str::random(6));
        } while (ProductoVariantes::where('Sku', $sku)->exists());

        return $sku;
    }

    protected function uniqueSlug(string $table, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (
            DB::table($table)
                ->where('Slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('Id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function resolveImageUrl(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    protected function userHasAdminRole(User $user): bool
    {
        return $user->roles->contains(function (Role $role) {
            return $this->resolveRoleKey($role->Nombre) === 'admin';
        });
    }

    protected function isProtectedAdminUser(User $user): bool
    {
        return $this->userHasAdminRole($user);
    }

    protected function resolveRoleKey(?string $roleName): string
    {
        $normalized = mb_strtolower(trim((string) $roleName));

        return match ($normalized) {
            'admin', 'administrador' => 'admin',
            'cliente', 'usuario', 'user' => 'usuario',
            default => $normalized,
        };
    }

    protected function formatRoleLabel(?string $roleName): string
    {
        return match ($this->resolveRoleKey($roleName)) {
            'admin' => 'Administrador',
            'usuario' => 'Usuario',
            '' => 'Sin rol',
            default => Str::headline((string) $roleName),
        };
    }

    protected function resolveRoleSortOrder(?string $roleName): int
    {
        return match ($this->resolveRoleKey($roleName)) {
            'admin' => 0,
            'usuario' => 1,
            default => 99,
        };
    }
}
