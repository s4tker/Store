<div class="grid gap-5 xl:grid-cols-[minmax(0,0.92fr)_minmax(0,1.08fr)]">
    <section class="admin-panel p-5 md:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="admin-card-kicker" id="CategoryFormEyebrow">Categorías</p>
                <h2 class="mt-1.5 text-lg font-semibold tracking-tight text-slate-950" id="CategoryFormTitle">Registrar categoría</h2>
            </div>

            <button type="button" class="admin-button" id="BtnResetCategoryForm">Nueva</button>
        </div>

        <form
            id="FormAddCategoria"
            action="{{ route('admin.categorias.store') }}"
            method="POST"
            class="mt-6 space-y-4"
            data-store-url="{{ route('admin.categorias.store') }}"
            data-update-base="{{ url('/admin/categorias') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="CategoryFormMethod">
            <input type="hidden" id="EditingCategoryId" value="">

            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="admin-label" for="CategoryName">Nombre</label>
                    <input type="text" name="Nombre" id="CategoryName" required class="admin-input" placeholder="Televisores">
                </div>

                <div>
                    <label class="admin-label" for="SelectCategoryType">Tipo</label>
                    <select name="TipoCategoria" id="SelectCategoryType" class="admin-select">
                        <option value="principal">Principal</option>
                        <option value="subcategoria">Subcategoría</option>
                    </select>
                </div>

                <div id="CategoryParentWrapper">
                    <label class="admin-label" for="CategoryParentSelect">Categoría padre</label>
                    <select name="ParentId" id="CategoryParentSelect" class="admin-select disabled:cursor-not-allowed disabled:opacity-50" disabled>
                        <option value="">Categoría principal</option>
                        @foreach($Categorias as $categoria)
                            <option value="{{ $categoria->Id }}">{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="admin-button-primary w-full" id="BtnSubmitCategory">Guardar categoría</button>
        </form>
    </section>

    <section class="admin-panel p-5 md:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="admin-card-kicker">Estructura</p>
                <h2 class="mt-1.5 text-lg font-semibold tracking-tight text-slate-950">Categorías</h2>
            </div>

            <button type="button" class="admin-button" data-toggle-target="CategoryListPanel" data-toggle-label-show="Ver lista" data-toggle-label-hide="Ocultar lista">Ocultar lista</button>
        </div>

        <div id="CategoryListPanel" class="mt-6 space-y-3">
            @forelse($Categorias as $categoria)
                <article class="rounded-[1.3rem] border border-slate-200/80 bg-slate-50/70 p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-4">
                            <x-admin.icon tone="violet" size="sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A1.75 1.75 0 0 1 5.75 5h4.5A1.75 1.75 0 0 1 12 6.75v4.5A1.75 1.75 0 0 1 10.25 13h-4.5A1.75 1.75 0 0 1 4 11.25z"/>
                                </svg>
                            </x-admin.icon>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ $categoria->Nombre }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ $categoria->subcategorias->count() }} subcategorías</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="admin-action-link"
                                data-edit-category="{{ $categoria->Id }}"
                                data-category-name="{{ $categoria->Nombre }}"
                                data-category-type="principal"
                                data-category-parent=""
                            >
                                Editar
                            </button>
                            <button type="button" class="admin-action-danger" data-delete-url="{{ route('admin.categorias.destroy', $categoria->Id) }}" data-delete-label="categoría {{ $categoria->Nombre }}">
                                Borrar
                            </button>
                        </div>
                    </div>

                    @if($categoria->subcategorias->isNotEmpty())
                        <div class="mt-4 grid gap-2.5 sm:grid-cols-2">
                            @foreach($categoria->subcategorias as $subcategoria)
                                <div class="flex items-center justify-between gap-3 rounded-[1rem] border border-white bg-white px-3.5 py-2.5">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-slate-700">{{ $subcategoria->Nombre }}</p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            class="admin-action-link"
                                            data-edit-category="{{ $subcategoria->Id }}"
                                            data-category-name="{{ $subcategoria->Nombre }}"
                                            data-category-type="subcategoria"
                                            data-category-parent="{{ $categoria->Id }}"
                                        >
                                            Editar
                                        </button>
                                        <button type="button" class="admin-action-danger" data-delete-url="{{ route('admin.categorias.destroy', $subcategoria->Id) }}" data-delete-label="subcategoría {{ $subcategoria->Nombre }}">
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div class="admin-empty">No hay categorías registradas.</div>
            @endforelse
        </div>
    </section>
</div>
