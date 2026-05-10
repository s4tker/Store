<section class="admin-panel overflow-hidden p-4 md:p-5">
    <div class="rounded-[1.6rem] bg-slate-50/80 p-3">
        <div class="no-scrollbar flex gap-2 overflow-x-auto" id="ProductRootCategories">
            @foreach($Categorias as $cat)
                <button
                    type="button"
                    class="admin-tab shrink-0 [&.is-active]:border-slate-900 [&.is-active]:bg-slate-900 [&.is-active]:text-white"
                    data-root-category-id="{{ $cat->Id }}"
                    data-root-category-name="{{ $cat->Nombre }}"
                >
                    {{ $cat->Nombre }}
                </button>
            @endforeach
        </div>
    </div>
</section>

<div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.25fr)]">
    <section class="admin-panel p-6 md:p-7">
        <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="admin-card-kicker" id="ProductFormEyebrow">Productos</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" id="ProductFormTitle">Registrar producto</h2>
            </div>

            <div class="flex items-center gap-3">
                <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-medium text-slate-500 [&.is-positive]:border-emerald-200 [&.is-positive]:bg-emerald-50 [&.is-positive]:text-emerald-600" id="TxtDescuento">0% desc.</div>
                <button type="button" class="admin-button" id="BtnResetProductForm">Nuevo</button>
            </div>
        </div>

        <form
            id="FormAddProducto"
            action="{{ route('admin.productos.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="mt-8 grid grid-cols-2 gap-5"
            data-store-url="{{ route('admin.productos.store') }}"
            data-update-base="{{ url('/admin/productos') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="ProductFormMethod">
            <input type="hidden" id="EditingProductId" value="">
            <div id="RemovedImagesContainer"></div>

            <div class="col-span-2">
                <label class="admin-label" for="ProductName">Nombre</label>
                <input type="text" name="Nombre" id="ProductName" required class="admin-input" placeholder="MacBook Air M3">
                <p class="mt-2 text-xs text-slate-400" id="ProductNameHint"></p>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="admin-label" for="ProductBrand">Marca</label>
                <select name="MarcaId" id="ProductBrand" class="admin-select" required>
                    <option value="">Seleccionar</option>
                    @foreach($Marcas as $marca)
                        <option value="{{ $marca->Id }}">{{ $marca->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="admin-label" for="ProductStock">Stock</label>
                <input type="number" min="0" max="32767" name="Stock" id="ProductStock" class="admin-input" placeholder="0">
            </div>

            <div class="col-span-2 grid gap-5 md:grid-cols-3">
                <div>
                    <label class="admin-label" for="InpPrecio">Precio normal</label>
                    <input type="number" step="0.01" min="0.10" name="Precio" id="InpPrecio" required class="admin-input" placeholder="0.00">
                </div>
                <div>
                    <label class="admin-label" for="InpDescuentoManual">Descuento %</label>
                    <input type="number" step="1" min="0" max="99" id="InpDescuentoManual" class="admin-input" placeholder="0">
                </div>
                <div>
                    <label class="admin-label" for="InpOferta">Precio final</label>
                    <input type="number" step="0.01" min="0" name="PrecioOferta" id="InpOferta" class="admin-input border-emerald-200 bg-emerald-50/60 text-emerald-700 placeholder:text-emerald-300 focus:border-emerald-300 focus:ring-emerald-100" placeholder="0.00">
                </div>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="admin-label" for="SelectCat">Categoría principal</label>
                <select name="CategoriaId" id="SelectCat" class="admin-select" required>
                    <option value="">Seleccionar</option>
                    @foreach($Categorias as $cat)
                        <option value="{{ $cat->Id }}" data-name="{{ $cat->Nombre }}" data-subs='@json($cat->subcategorias->map(fn ($sub) => ["Id" => $sub->Id, "Nombre" => $sub->Nombre])->values())'>{{ $cat->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="admin-label" for="SelectSub">Subcategoría</label>
                <select name="SubCategoriaId" id="SelectSub" class="admin-select disabled:cursor-not-allowed disabled:opacity-50" disabled>
                    <option value="">Sin subcategorías</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="admin-label" for="ProductDescription">Descripción</label>
                <textarea name="Descripcion" id="ProductDescription" rows="4" class="admin-textarea resize-y" placeholder="Descripción breve"></textarea>
            </div>

            <div class="admin-panel-soft col-span-2 p-5">
                <div class="flex items-center justify-between gap-3">
                    <label class="admin-label mb-0" for="ProductImages">Galería</label>
                    <span class="text-xs text-slate-400" id="FileName">Sin archivos seleccionados.</span>
                </div>

                <label class="mt-4 flex h-36 w-full cursor-pointer flex-col items-center justify-center rounded-[1.35rem] border border-dashed border-slate-300 bg-white text-center transition hover:border-blue-200 hover:bg-blue-50/40" for="ProductImages">
                    <x-admin.icon tone="blue" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V7m0 0-3 3m3-3 3 3M5 16.5A3.5 3.5 0 0 0 8.5 20h7A3.5 3.5 0 0 0 19 16.5"/>
                        </svg>
                    </x-admin.icon>
                    <p class="mt-4 text-sm font-medium text-slate-700">Subir imágenes</p>
                    <input type="file" name="Imagenes[]" id="ProductImages" accept="image/*" multiple class="hidden">
                </label>

                <div class="mt-5 grid gap-5 lg:grid-cols-2">
                    <div>
                        <p class="admin-label">Actuales</p>
                        <div class="grid grid-cols-2 gap-3" id="ExistingImagesGrid">
                            <div class="col-span-2 rounded-[1rem] border border-slate-200 bg-white px-4 py-6 text-center text-xs text-slate-400">Sin imágenes.</div>
                        </div>
                    </div>

                    <div>
                        <p class="admin-label">Nuevas</p>
                        <div class="grid grid-cols-2 gap-3" id="NewImagesPreview">
                            <div class="col-span-2 rounded-[1rem] border border-dashed border-slate-200 bg-white px-4 py-6 text-center text-xs text-slate-400">Vista previa.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-panel-soft col-span-2 p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="admin-label mb-0">Atributos</p>
                        <p class="mt-2 text-xs text-slate-400" id="ProductPresetCopy"></p>
                    </div>
                    <button type="button" class="admin-button" id="BtnAddAttribute">Añadir campo</button>
                </div>

                <div class="mt-4 flex flex-wrap gap-2" id="ProductPresetAttributes"></div>
                <div id="ContainerAtributos" class="mt-4 space-y-3"></div>
            </div>

            <div class="col-span-2 flex flex-col gap-3 pt-2 sm:flex-row">
                <button type="submit" class="admin-button-primary flex-1" id="BtnSubmitProduct">Registrar producto</button>
                <button type="button" class="admin-button-danger" id="BtnClearEditor">Limpiar</button>
            </div>
        </form>
    </section>

    <section class="admin-panel p-6 md:p-7">
        <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="admin-card-kicker">Catálogo</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Productos registrados</h2>
            </div>

            <button type="button" class="admin-button" data-toggle-target="ProductListPanel" data-toggle-label-show="Ver catálogo" data-toggle-label-hide="Ocultar catálogo">Ocultar catálogo</button>
        </div>

        <div id="ProductListPanel" class="mt-8">
            <div class="admin-panel-soft mb-6 p-4">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="sm:col-span-2 xl:col-span-4">
                        <label class="admin-label" for="ProductSearch">Buscar producto</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M18 10.5a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0z"/>
                            </svg>
                            <input type="search" id="ProductSearch" class="admin-input pl-11" placeholder="Nombre, marca, categoría o SKU">
                        </div>
                    </div>

                    <div>
                        <label class="admin-label" for="FilterBrand">Marca</label>
                        <select id="FilterBrand" class="admin-select">
                            <option value="">Todas</option>
                            @foreach($Marcas as $marca)
                                <option value="{{ $marca->Nombre }}">{{ $marca->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 xl:col-span-3">
                        <label class="admin-label" for="FilterCategory">Categoría</label>
                        <select id="FilterCategory" class="admin-select">
                            <option value="">Todas</option>
                            @foreach($TodasLasCategorias as $categoria)
                                <option value="{{ $categoria->Nombre }}">{{ $categoria->padre?->Nombre ? $categoria->padre->Nombre . ' / ' . $categoria->Nombre : $categoria->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2" id="ProductCatalog">
                @forelse($Productos as $producto)
                    @php
                        $firstVariant = $producto->variantes->sortBy('Id')->first();
                        $categoryLabel = $producto->categoria?->padre?->Nombre
                            ? $producto->categoria->padre->Nombre . ' / ' . $producto->categoria->Nombre
                            : ($producto->categoria?->Nombre ?? 'Sin categoría');
                    @endphp
                    <article
                        class="product-card-admin admin-list-card group flex flex-col [&.is-active]:border-blue-300"
                        data-product-id="{{ $producto->Id }}"
                        data-product-name="{{ \Illuminate\Support\Str::lower($producto->Nombre) }}"
                        data-product-brand="{{ \Illuminate\Support\Str::lower($producto->marca?->Nombre ?? '') }}"
                        data-product-category="{{ \Illuminate\Support\Str::lower($categoryLabel) }}"
                        data-product-root-category-id="{{ $producto->categoria?->ParentId ?: $producto->categoria?->Id }}"
                        data-product-sku="{{ \Illuminate\Support\Str::lower($firstVariant?->Sku ?? '') }}"
                    >
                        <button type="button" class="flex items-start gap-4 text-left" data-load-product="{{ $producto->Id }}">
                            <div class="h-24 w-24 overflow-hidden rounded-[1.2rem] bg-slate-100">
                                <img src="{{ $producto->image_url }}" alt="{{ $producto->Nombre }}" class="h-full w-full object-cover">
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-950">{{ $producto->Nombre }}</h3>
                                        <p class="mt-1 truncate text-sm text-slate-500">{{ $producto->marca?->Nombre ?? 'Sin marca' }}</p>
                                    </div>
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">{{ $producto->imagenes->count() }} img</span>
                                </div>

                                <p class="mt-3 text-sm text-slate-500">{{ $categoryLabel }}</p>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="text-lg font-semibold text-slate-900">S/. {{ number_format($producto->display_price, 2) }}</span>
                                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs text-slate-500">{{ $firstVariant?->Sku ?? 'Sin SKU' }}</span>
                                </div>
                            </div>
                        </button>

                        <div class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4">
                            <button type="button" class="admin-button flex-1" data-load-product="{{ $producto->Id }}">Editar</button>
                            <button type="button" class="admin-button-danger" data-delete-url="{{ route('admin.productos.destroy', $producto->Id) }}" data-delete-label="producto {{ $producto->Nombre }}">Borrar</button>
                        </div>
                    </article>
                @empty
                    <div class="admin-empty md:col-span-2">Todavía no hay productos registrados.</div>
                @endforelse
            </div>

            <div class="admin-empty mt-4 hidden" id="NoProductResults">
                No hay productos que coincidan con los filtros actuales.
            </div>
        </div>
    </section>
</div>

<script type="application/json" id="AdminProductsData">@json($ProductosAdmin)</script>
