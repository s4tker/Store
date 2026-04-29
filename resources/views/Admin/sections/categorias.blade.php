<div class="admin-grid">
    {{-- Formulario de categorías: permite crear principal o subcategoría --}}
    <div class="admin-card">
        <div class="admin-card-header admin-card-header-tight">
            <div>
                <p class="admin-kicker" id="CategoryFormEyebrow">categorías</p>
                <h2 id="CategoryFormTitle">Registrar categoría</h2>
                <p class="admin-card-intro">Crea una categoría principal o una subcategoría.</p>
            </div>
            <button type="button" class="secondary-admin-btn" id="BtnResetCategoryForm">Nueva</button>
        </div>

        <form
            id="FormAddCategoria"
            action="{{ route('admin.categorias.store') }}"
            method="POST"
            class="admin-form-grid compact-admin-form compact-admin-form-category"
            data-store-url="{{ route('admin.categorias.store') }}"
            data-update-base="{{ url('/admin/categorias') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="CategoryFormMethod">
            <input type="hidden" id="EditingCategoryId" value="">

            <div class="field-half">
                <label class="label-admin" for="CategoryName">Nombre</label>
                <input type="text" name="Nombre" id="CategoryName" required class="input-admin" placeholder="Ej: Televisores">
            </div>

            <div class="field-half">
                <label class="label-admin" for="SelectCategoryType">Tipo</label>
                <select name="TipoCategoria" id="SelectCategoryType" class="input-admin">
                    <option value="principal">Principal</option>
                    <option value="subcategoria">Subcategoría</option>
                </select>
            </div>

            <div class="field-span-3" id="CategoryParentWrapper">
                <label class="label-admin" for="CategoryParentSelect">Categoría padre</label>
                <select name="ParentId" id="CategoryParentSelect" class="input-admin" disabled>
                    <option value="">Categoría principal</option>
                    @foreach($Categorias as $categoria)
                        <option value="{{ $categoria->Id }}">{{ $categoria->Nombre }}</option>
                    @endforeach
                </select>
                <p class="helper-admin mt-2">Solo se activa cuando eliges crear una subcategoría.</p>
            </div>

            <div class="field-span-3 form-actions">
                <button type="submit" class="btn-primary-admin" id="BtnSubmitCategory">Guardar categoría</button>
            </div>
        </form>
    </div>

    {{-- Vista de jerarquía actual de categorías --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <p class="admin-kicker">listado</p>
                <h2>Categorías y subcategorías</h2>
                <p class="admin-card-intro">Edita o elimina elementos desde esta lista.</p>
            </div>
            <button type="button" class="ghost-admin-btn admin-list-toggle" data-toggle-target="CategoryListPanel" data-toggle-label-show="Ver categorías" data-toggle-label-hide="Ocultar categorías">Ver categorías</button>
        </div>

        <div id="CategoryListPanel" class="table-list admin-collapsible-panel hidden">
            @forelse($Categorias as $categoria)
                <article class="category-tree">
                    <div class="category-tree-header">
                        <div>
                            <strong>{{ $categoria->Nombre }}</strong>
                            <span>{{ $categoria->subcategorias->count() }} subcategoría(s)</span>
                        </div>
                        <div class="inline-actions">
                            <button
                                type="button"
                                class="ghost-admin-btn ghost-admin-btn-small"
                                data-edit-category="{{ $categoria->Id }}"
                                data-category-name="{{ $categoria->Nombre }}"
                                data-category-type="principal"
                                data-category-parent=""
                            >
                                Editar
                            </button>
                            <button type="button" class="danger-inline-btn" data-delete-url="{{ route('admin.categorias.destroy', $categoria->Id) }}" data-delete-label="categoría {{ $categoria->Nombre }}">
                                Borrar
                            </button>
                        </div>
                    </div>

                    @if($categoria->subcategorias->isNotEmpty())
                        <div class="subcategory-list">
                            @foreach($categoria->subcategorias as $subcategoria)
                                <div class="subcategory-item">
                                    <span>{{ $subcategoria->Nombre }}</span>
                                    <div class="inline-actions">
                                        <button
                                            type="button"
                                            class="ghost-admin-btn ghost-admin-btn-small"
                                            data-edit-category="{{ $subcategoria->Id }}"
                                            data-category-name="{{ $subcategoria->Nombre }}"
                                            data-category-type="subcategoria"
                                            data-category-parent="{{ $categoria->Id }}"
                                        >
                                            Editar
                                        </button>
                                        <button type="button" class="danger-inline-btn danger-inline-btn-soft" data-delete-url="{{ route('admin.categorias.destroy', $subcategoria->Id) }}" data-delete-label="subcategoría {{ $subcategoria->Nombre }}">
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty-state">No hay categorías registradas.</div>
            @endforelse
        </div>
    </div>
</div>
