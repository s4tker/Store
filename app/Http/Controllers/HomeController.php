<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->search ?? '';
        $selectedCategory = $request->integer('category') ?: null;
        $selectedSubcategory = $request->integer('subcategory') ?: null;
        $Products = collect();
        $Categories = collect();

        try {
            $Categories = Categoria::query()
                ->with(['subcategorias' => fn ($query) => $query->orderBy('Nombre')])
                ->whereNull('ParentId')
                ->orderBy('Nombre')
                ->get();

            $Query = Producto::with(['marca', 'categoria.padre', 'imagenes', 'variantes']);

            if ($Search) {
                $Query->where('Nombre', 'like', "%{$Search}%");
            }

            if ($selectedSubcategory) {
                $Query->where('CategoriaId', $selectedSubcategory);
            } elseif ($selectedCategory) {
                $categoryIds = Categoria::query()
                    ->where('Id', $selectedCategory)
                    ->orWhere('ParentId', $selectedCategory)
                    ->pluck('Id')
                    ->all();

                $Query->whereIn('CategoriaId', $categoryIds);
            }

            $Products = $Query->orderBy('Id', 'desc')->get();
        } catch (QueryException) {
            $Products = new Collection();
            $Categories = new Collection();
        }

        return view('index', [
            'Products' => $Products,
            'Categorias' => $Categories,
            'Search' => $Search,
            'SelectedCategory' => $selectedCategory,
            'SelectedSubcategory' => $selectedSubcategory,
        ]);
    }

    public function showProduct(string $slug)
    {
        $Categories = Categoria::query()
            ->with(['subcategorias' => fn ($query) => $query->orderBy('Nombre')])
            ->whereNull('ParentId')
            ->orderBy('Nombre')
            ->get();

        $Product = Producto::query()
            ->with(['marca', 'categoria.padre', 'imagenes', 'variantes' => fn ($query) => $query->orderBy('Id')])
            ->where('Slug', $slug)
            ->firstOrFail();

        $variant = $Product->variantes->first();
        $stock = $variant
            ? (int) (DB::table('Inventario')->where('VarianteId', $variant->Id)->value('Stock') ?? 0)
            : 0;

        $attributes = $variant
            ? DB::table('VarianteAtributos as va')
                ->join('AtributoValores as av', 'av.Id', '=', 'va.ValorId')
                ->join('Atributos as a', 'a.Id', '=', 'av.AtributoId')
                ->where('va.VarianteId', $variant->Id)
                ->orderBy('a.Nombre')
                ->get(['a.Nombre as nombre', 'av.Valor as valor'])
            : collect();

        $originalPrice = (float) ($variant?->Precio ?? 0);
        $offerPrice = (float) ($variant?->PrecioOferta ?? 0);
        $finalPrice = $offerPrice > 0 ? $offerPrice : $originalPrice;
        $discountPercent = ($offerPrice > 0 && $originalPrice > $offerPrice)
            ? (int) round((($originalPrice - $offerPrice) / $originalPrice) * 100)
            : 0;

        $rootCategoryId = $Product->categoria?->ParentId ?: $Product->categoria?->Id;

        $RelatedProducts = Producto::query()
            ->with(['marca', 'imagenes', 'variantes'])
            ->when($rootCategoryId, function ($query) use ($rootCategoryId) {
                $categoryIds = Categoria::query()
                    ->where('Id', $rootCategoryId)
                    ->orWhere('ParentId', $rootCategoryId)
                    ->pluck('Id')
                    ->all();

                $query->whereIn('CategoriaId', $categoryIds);
            })
            ->where('Id', '!=', $Product->Id)
            ->orderByDesc('Id')
            ->limit(8)
            ->get();

        return view('Product.show', [
            'Product' => $Product,
            'Categorias' => $Categories,
            'Attributes' => $attributes,
            'Variant' => $variant,
            'Stock' => $stock,
            'OriginalPrice' => $originalPrice,
            'FinalPrice' => $finalPrice,
            'DiscountPercent' => $discountPercent,
            'RelatedProducts' => $RelatedProducts,
        ]);
    }
}
