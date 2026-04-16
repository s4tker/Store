<div class="admin-grid admin-grid-products">
    <section class="admin-card product-editor-card">
        <div class="admin-card-header admin-card-header-tight">
            <div>
                <p class="admin-kicker" id="ProductFormEyebrow">Nuevo producto</p>
                <h2 id="ProductFormTitle">Registrar producto</h2>
            </div>
            <div class="editor-actions">
                <div class="discount-pill" id="TxtDescuento">0% desc.</div>
                <button type="button" class="secondary-admin-btn" id="BtnResetProductForm">Nuevo</button>
            </div>
        </div>

        <form
            id="FormAddProducto"
            action="{{ route('admin.productos.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="admin-form-grid"
            data-store-url="{{ route('admin.productos.store') }}"
            data-update-base="{{ url('/admin/productos') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="ProductFormMethod">
            <input type="hidden" id="EditingProductId" value="">
            <div id="RemovedImagesContainer"></div>

            <div class="field-half">
                <label class="label-admin" for="ProductName">Nombre del producto</label>
                <input type="text" name="Nombre" id="ProductName" required class="input-admin" placeholder="Ej: iPhone 15 Pro Max">
            </div>

            <div class="field-half">
                <label class="label-admin" for="ProductBrand">Marca</label>
                <select name="MarcaId" id="ProductBrand" class="input-admin" required>
                    <option value="">Seleccionar</option>
                    @foreach($Marcas as $marca)
                        <option value="{{ $marca->Id }}">{{ $marca->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field-third field-metric">
                <label class="label-admin" for="InpPrecio">Precio base</label>
                <input type="number" step="0.01" min="0.10" name="Precio" id="InpPrecio" required class="input-admin" placeholder="0.00">
            </div>

            <div class="field-third field-metric">
                <label class="label-admin" for="InpOferta">Precio oferta</label>
                <input type="number" step="0.01" min="0" name="PrecioOferta" id="InpOferta" class="input-admin" placeholder="0.00">
            </div>

            <div class="field-third field-metric">
                <label class="label-admin" for="ProductStock">Stock</label>
                <input type="number" min="0" max="32767" name="Stock" id="ProductStock" class="input-admin" placeholder="0">
            </div>

            <div class="field-half">
                <label class="label-admin" for="ProductStatus">Estado</label>
                <select name="Estado" id="ProductStatus" class="input-admin">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <div class="field-half">
                <label class="label-admin" for="SelectCat">Categoría principal</label>
                <select name="CategoriaId" id="SelectCat" class="input-admin" required>
                    <option value="">Seleccionar</option>
                    @foreach($Categorias as $cat)
                        <option value="{{ $cat->Id }}" data-name="{{ $cat->Nombre }}" data-subs='@json($cat->subcategorias->map(fn ($sub) => ["Id" => $sub->Id, "Nombre" => $sub->Nombre])->values())'>{{ $cat->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field-half">
                <label class="label-admin" for="SelectSub">Subcategoría</label>
                <select name="SubCategoriaId" id="SelectSub" class="input-admin" disabled>
                    <option value="">Sin subcategorías</option>
                </select>
            </div>

            <div class="field-span-3 field-description">
                <label class="label-admin" for="ProductDescription">Descripción</label>
                <textarea name="Descripcion" id="ProductDescription" rows="4" class="input-admin textarea-admin" placeholder="Describe el producto, beneficios, materiales o compatibilidades."></textarea>
            </div>

            <div class="field-span-3">
                <div class="admin-inline-header">
                    <div>
                        <label class="label-admin" for="ProductImages">Galería de imágenes</label>
                        <p class="helper-admin">Sube varias vistas del producto: frontal, lateral, detalle, caja o contexto.</p>
                    </div>
                </div>

                <label class="upload-card upload-card-rich" for="ProductImages">
                    <input type="file" name="Imagenes[]" id="ProductImages" accept="image/*" multiple class="sr-only">
                    <span class="upload-title">Subir imágenes</span>
                    <span id="FileName" class="upload-name">Puedes seleccionar varias imágenes a la vez.</span>
                </label>

                <div class="gallery-block">
                    <div>
                        <p class="gallery-title">Imágenes actuales</p>
                        <div class="image-grid" id="ExistingImagesGrid">
                            <div class="empty-inline-state">Aún no hay imágenes guardadas.</div>
                        </div>
                    </div>

                    <div>
                        <p class="gallery-title">Nuevas imágenes</p>
                        <div class="image-grid image-grid-dashed" id="NewImagesPreview">
                            <div class="empty-inline-state">Las nuevas imágenes aparecerán aquí.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field-span-3">
                <div class="admin-inline-header">
                    <div>
                        <label class="label-admin">Atributos dinámicos</label>
                    </div>
                    <button type="button" class="secondary-admin-btn" id="BtnAddAttribute">Añadir campo</button>
                </div>

                <div id="ContainerAtributos" class="attribute-stack"></div>
            </div>

            <div class="field-span-3 form-actions form-actions-split">
                <button type="submit" class="btn-primary-admin" id="BtnSubmitProduct">Registrar producto</button>
                <button type="button" class="ghost-admin-btn" id="BtnClearEditor">Limpiar formulario</button>
            </div>
        </form>
    </section>

    <section class="admin-card product-catalog-card">
        <div class="admin-card-header admin-card-header-stack">
            <div>
                <p class="admin-kicker">Catálogo actual</p>
                <h2>Productos registrados</h2>
            </div>
            <div class="catalog-stats">
                <span id="ProductResultsCount">{{ $Productos->count() }} resultados</span>
            </div>
        </div>

        <div class="catalog-filters">
            <div class="field-span-3">
                <label class="label-admin" for="ProductSearch">Buscar producto</label>
                <input type="search" id="ProductSearch" class="input-admin" placeholder="Nombre, marca, categoría o SKU">
            </div>

            <div class="field-half">
                <label class="label-admin" for="FilterBrand">Filtrar por marca</label>
                <select id="FilterBrand" class="input-admin">
                    <option value="">Todas</option>
                    @foreach($Marcas as $marca)
                        <option value="{{ $marca->Nombre }}">{{ $marca->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field-half">
                <label class="label-admin" for="FilterCategory">Filtrar por categoría</label>
                <select id="FilterCategory" class="input-admin">
                    <option value="">Todas</option>
                    @foreach($TodasLasCategorias as $categoria)
                        <option value="{{ $categoria->Nombre }}">{{ $categoria->padre?->Nombre ? $categoria->padre->Nombre . ' / ' . $categoria->Nombre : $categoria->Nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="product-catalog" id="ProductCatalog">
            @forelse($Productos as $producto)
                @php
                    $firstVariant = $producto->variantes->sortBy('Id')->first();
                    $categoryLabel = $producto->categoria?->padre?->Nombre
                        ? $producto->categoria->padre->Nombre . ' / ' . $producto->categoria->Nombre
                        : ($producto->categoria?->Nombre ?? 'Sin categoría');
                @endphp
                <article
                    class="product-card-admin"
                    data-product-id="{{ $producto->Id }}"
                    data-product-name="{{ \Illuminate\Support\Str::lower($producto->Nombre) }}"
                    data-product-brand="{{ \Illuminate\Support\Str::lower($producto->marca?->Nombre ?? '') }}"
                    data-product-category="{{ \Illuminate\Support\Str::lower($categoryLabel) }}"
                    data-product-sku="{{ \Illuminate\Support\Str::lower($firstVariant?->Sku ?? '') }}"
                >
                    <button type="button" class="product-card-main" data-load-product="{{ $producto->Id }}">
                        <img src="{{ $producto->image_url }}" alt="{{ $producto->Nombre }}" class="product-card-thumb">
                        <div class="product-card-copy">
                            <div class="product-card-topline">
                                <h3>{{ $producto->Nombre }}</h3>
                                <span class="product-card-price">S/. {{ number_format($producto->display_price, 2) }}</span>
                            </div>
                            <p>{{ $producto->marca?->Nombre ?? 'Sin marca' }} · {{ $categoryLabel }}</p>
                            <div class="product-card-meta">
                                <span>{{ $producto->imagenes->count() }} imagen(es)</span>
                                <span>{{ $producto->variantes->count() }} variante(s)</span>
                                <span>{{ $producto->Estado }}</span>
                            </div>
                        </div>
                    </button>

                    <div class="product-card-actions">
                        <button type="button" class="ghost-admin-btn ghost-admin-btn-small" data-load-product="{{ $producto->Id }}">
                            Editar
                        </button>
                        <button type="button" class="danger-inline-btn" data-delete-url="{{ route('admin.productos.destroy', $producto->Id) }}" data-delete-label="producto {{ $producto->Nombre }}">
                            Eliminar
                        </button>
                    </div>
                </article>
            @empty
                <div class="empty-state">Todavía no hay productos registrados.</div>
            @endforelse
        </div>

        <div class="empty-state hidden" id="NoProductResults">
            No hay productos que coincidan con los filtros actuales.
        </div>
    </section>
</div>

<script type="application/json" id="AdminProductsData">@json($ProductosAdmin)</script>
