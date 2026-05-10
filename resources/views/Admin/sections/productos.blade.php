{{-- barra principal de categorías enlazada con la base de datos --}}
<section class="sticky top-[125px] lg:top-[65px] z-10 mb-6 bg-white/80 backdrop-blur-md rounded-2xl border border-slate-100 shadow-sm p-3">
    <div class="flex flex-wrap gap-2" id="ProductRootCategories">
        @foreach($Categorias as $cat)
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all [&.is-active]:bg-blue-600 [&.is-active]:text-white [&.is-active]:border-blue-600"
                data-root-category-id="{{ $cat->Id }}"
                data-root-category-name="{{ $cat->Nombre }}"
            >
                {{ $cat->Nombre }}
            </button>
        @endforeach
    </div>
</section>

<div class="grid xl:grid-cols-12 gap-8 items-start">
    <section class="xl:col-span-5 bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1" id="ProductFormEyebrow">productos</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight" id="ProductFormTitle">Registrar producto</h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3 py-1.5 rounded-lg border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50 [&.is-positive]:bg-emerald-50 [&.is-positive]:text-emerald-600 [&.is-positive]:border-emerald-200 transition-colors" id="TxtDescuento">0% desc.</div>
                <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" id="BtnResetProductForm">Nuevo</button>
            </div>
        </div>

        <form
            id="FormAddProducto"
            action="{{ route('admin.productos.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid grid-cols-2 gap-4"
            data-store-url="{{ route('admin.productos.store') }}"
            data-update-base="{{ url('/admin/productos') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="ProductFormMethod">
            <input type="hidden" id="EditingProductId" value="">
            <div id="RemovedImagesContainer"></div>

            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="ProductName">Nombre del producto</label>
                <input type="text" name="Nombre" id="ProductName" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="Nombre completo del producto">
                <p class="text-[10px] font-medium text-slate-400 mt-2" id="ProductNameHint"></p>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="ProductBrand">Marca</label>
                <select name="MarcaId" id="ProductBrand" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer" required>
                    <option value="">Seleccionar</option>
                    @foreach($Marcas as $marca)
                        <option value="{{ $marca->Id }}">{{ $marca->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="ProductStock">Stock (Unidades)</label>
                <input type="number" min="0" max="32767" name="Stock" id="ProductStock" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="0">
            </div>

            <div class="col-span-2 grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="InpPrecio">Precio normal</label>
                    <input type="number" step="0.01" min="0.10" name="Precio" id="InpPrecio" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="InpDescuentoManual">Desc. %</label>
                    <input type="number" step="1" min="0" max="99" id="InpDescuentoManual" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="InpOferta">Precio final</label>
                    <input type="number" step="0.01" min="0" name="PrecioOferta" id="InpOferta" class="w-full bg-slate-50 border border-emerald-200 text-emerald-700 text-sm font-bold rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-emerald-300" placeholder="0.00">
                </div>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="SelectCat">Categoría principal</label>
                <select name="CategoriaId" id="SelectCat" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer" required>
                    <option value="">Seleccionar</option>
                    @foreach($Categorias as $cat)
                        <option value="{{ $cat->Id }}" data-name="{{ $cat->Nombre }}" data-subs='@json($cat->subcategorias->map(fn ($sub) => ["Id" => $sub->Id, "Nombre" => $sub->Nombre])->values())'>{{ $cat->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="SelectSub">Subcategoría</label>
                <select name="SubCategoriaId" id="SelectSub" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <option value="">Sin subcategorías</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="ProductDescription">Descripción</label>
                <textarea name="Descripcion" id="ProductDescription" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 resize-y" placeholder="Describe el producto, beneficios, materiales o compatibilidades."></textarea>
            </div>

            <div class="col-span-2 bg-slate-50 rounded-2xl p-4 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500" for="ProductImages">Galería de imágenes</label>
                </div>

                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-white hover:bg-slate-50 hover:border-blue-300 transition-all mb-4" for="ProductImages">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                        <p class="mb-1 text-xs font-bold text-slate-600">Subir imágenes</p>
                        <p class="text-[10px] text-slate-400" id="FileName">Puedes seleccionar varias imágenes a la vez.</p>
                    </div>
                    <input type="file" name="Imagenes[]" id="ProductImages" accept="image/*" multiple class="hidden">
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Actuales</p>
                        <div class="grid grid-cols-2 gap-2" id="ExistingImagesGrid">
                            <div class="col-span-2 bg-white rounded-xl border border-slate-200 p-4 text-center text-[10px] text-slate-400">Sin imágenes.</div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nuevas</p>
                        <div class="grid grid-cols-2 gap-2" id="NewImagesPreview">
                            <div class="col-span-2 bg-white rounded-xl border border-dashed border-slate-200 p-4 text-center text-[10px] text-slate-400">Vista previa.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-2 bg-slate-50 rounded-2xl p-4 border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500">Atributos dinámicos</label>
                        <p class="text-[10px] font-medium text-slate-400 mt-1" id="ProductPresetCopy"></p>
                    </div>
                    <button type="button" class="shrink-0 bg-white hover:bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-black text-[10px] uppercase tracking-widest transition-colors border border-blue-100" id="BtnAddAttribute">Añadir campo</button>
                </div>

                <div class="flex flex-wrap gap-2 mb-4" id="ProductPresetAttributes"></div>
                <div id="ContainerAtributos" class="space-y-3"></div>
            </div>

            <div class="col-span-2 flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-black text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-md" id="BtnSubmitProduct">Registrar producto</button>
                <button type="button" class="bg-red-50 hover:bg-red-500 hover:text-white text-red-500 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-red-100" id="BtnClearEditor">Limpiar</button>
            </div>
        </form>
    </section>

    <section class="xl:col-span-7 bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">catálogo</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Productos registrados</h2>
            </div>
            <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" data-toggle-target="ProductListPanel" data-toggle-label-show="Ver catálogo" data-toggle-label-hide="Ocultar catálogo">Ocultar catálogo</button>
        </div>

        <div id="ProductListPanel" class="">
            <div class="grid sm:grid-cols-3 gap-4 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div class="sm:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="ProductSearch">Buscar producto</label>
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="search" id="ProductSearch" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="Nombre, marca, categoría o SKU">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="FilterBrand">Filtrar por marca</label>
                    <select id="FilterBrand" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                        <option value="">Todas</option>
                        @foreach($Marcas as $marca)
                            <option value="{{ $marca->Nombre }}">{{ $marca->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="FilterCategory">Filtrar por categoría</label>
                    <select id="FilterCategory" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                        <option value="">Todas</option>
                        @foreach($TodasLasCategorias as $categoria)
                            <option value="{{ $categoria->Nombre }}">{{ $categoria->padre?->Nombre ? $categoria->padre->Nombre . ' / ' . $categoria->Nombre : $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4" id="ProductCatalog">
                @forelse($Productos as $producto)
                    @php
                        $firstVariant = $producto->variantes->sortBy('Id')->first();
                        $categoryLabel = $producto->categoria?->padre?->Nombre
                            ? $producto->categoria->padre->Nombre . ' / ' . $producto->categoria->Nombre
                            : ($producto->categoria?->Nombre ?? 'Sin categoría');
                    @endphp
                    <article
                        class="product-card-admin group relative bg-white border border-slate-200 hover:border-blue-300 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col [&.is-active]:border-blue-500 [&.is-active]:ring-2 [&.is-active]:ring-blue-500/20"
                        data-product-id="{{ $producto->Id }}"
                        data-product-name="{{ \Illuminate\Support\Str::lower($producto->Nombre) }}"
                        data-product-brand="{{ \Illuminate\Support\Str::lower($producto->marca?->Nombre ?? '') }}"
                        data-product-category="{{ \Illuminate\Support\Str::lower($categoryLabel) }}"
                        data-product-root-category-id="{{ $producto->categoria?->ParentId ?: $producto->categoria?->Id }}"
                        data-product-sku="{{ \Illuminate\Support\Str::lower($firstVariant?->Sku ?? '') }}"
                    >
                        <button type="button" class="text-left flex items-start gap-4 p-4" data-load-product="{{ $producto->Id }}">
                            <div class="w-20 h-20 rounded-xl bg-slate-50 border border-slate-100 flex-shrink-0 overflow-hidden">
                                <img src="{{ $producto->image_url }}" alt="{{ $producto->Nombre }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-black text-slate-900 truncate mb-1">{{ $producto->Nombre }}</h3>
                                <p class="text-[11px] font-bold text-slate-500 truncate mb-2">{{ $producto->marca?->Nombre ?? 'Sin marca' }} · {{ $categoryLabel }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-black text-blue-600">S/. {{ number_format($producto->display_price, 2) }}</span>
                                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md text-[9px] font-bold uppercase">{{ $producto->imagenes->count() }} img</span>
                                </div>
                            </div>
                        </button>

                        <div class="border-t border-slate-100 bg-slate-50/50 p-3 flex items-center justify-end gap-2">
                            <button type="button" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-600 bg-white hover:bg-blue-50 border border-slate-200 px-3 py-1.5 rounded-lg transition-colors" data-load-product="{{ $producto->Id }}">
                                Editar
                            </button>
                            <button type="button" class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-600 bg-white hover:bg-red-50 border border-red-100 px-3 py-1.5 rounded-lg transition-colors" data-delete-url="{{ route('admin.productos.destroy', $producto->Id) }}" data-delete-label="producto {{ $producto->Nombre }}">
                                Borrar
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="col-span-2 bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm font-medium text-slate-500">
                        Todavía no hay productos registrados.
                    </div>
                @endforelse
            </div>

            <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl p-6 text-center text-sm font-medium hidden" id="NoProductResults">
                No hay productos que coincidan con los filtros actuales.
            </div>
        </div>
    </section>
</div>

<script type="application/json" id="AdminProductsData">@json($ProductosAdmin)</script>

<style>
/* Utilities for dynamic attribute rows */
.attribute-row {
    @apply flex gap-2;
}
.attribute-row input {
    @apply w-full bg-white border border-slate-200 text-slate-900 text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500;
}
.attribute-row .attribute-remove {
    @apply shrink-0 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-100 rounded-xl px-3 font-black text-[10px] transition-colors;
}

/* Utilities for image preview cards */
.image-card {
    @apply relative bg-white border border-slate-200 rounded-xl overflow-hidden group;
}
.image-card img {
    @apply w-full h-20 object-cover;
}
.image-card .image-card-footer {
    @apply p-2 flex items-center justify-between text-[9px] bg-slate-50 border-t border-slate-100;
}
.image-card .image-card-remove {
    @apply text-red-500 hover:text-red-700 font-bold uppercase tracking-wider;
}
.image-card.is-marked-remove {
    @apply opacity-50 border-red-300;
}
</style>
