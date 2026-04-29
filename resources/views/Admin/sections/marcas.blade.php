<div class="admin-grid">
    {{-- Formulario para registrar marcas --}}
    <div class="admin-card">
        <div class="admin-card-header admin-card-header-tight">
            <div>
                <p class="admin-kicker" id="BrandFormEyebrow">marcas</p>
                <h2 id="BrandFormTitle">Nueva marca</h2>
                <p class="admin-card-intro">Registra y actualiza las marcas de tu catálogo.</p>
            </div>
            <button type="button" class="secondary-admin-btn" id="BtnResetBrandForm">Nueva</button>
        </div>

        <form
            id="FormAddMarca"
            action="{{ route('admin.marcas.store') }}"
            method="POST"
            class="admin-form-grid compact-admin-form compact-admin-form-brand"
            data-store-url="{{ route('admin.marcas.store') }}"
            data-update-base="{{ url('/admin/marcas') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="BrandFormMethod">
            <input type="hidden" id="EditingBrandId" value="">

            <div class="field-half">
                <label class="label-admin" for="BrandName">Nombre</label>
                <input type="text" name="Nombre" id="BrandName" required class="input-admin" placeholder="Ej: Apple, Sony, HP">
            </div>

            <div class="field-half form-actions form-actions-inline">
                <button type="submit" class="btn-primary-admin" id="BtnSubmitBrand">Guardar marca</button>
            </div>
        </form>
    </div>

    {{-- Listado compacto de marcas registradas --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <p class="admin-kicker">listado</p>
                <h2>Marcas registradas</h2>
                <p class="admin-card-intro">Cada marca se puede editar o borrar desde aquí.</p>
            </div>
            <button type="button" class="ghost-admin-btn admin-list-toggle" data-toggle-target="BrandListPanel" data-toggle-label-show="Ver marcas" data-toggle-label-hide="Ocultar marcas">Ver marcas</button>
        </div>

        <div id="BrandListPanel" class="chip-list admin-collapsible-panel hidden">
            @forelse($Marcas as $marca)
                <div class="chip">
                    <span>{{ $marca->Nombre }}</span>
                    <div class="inline-actions">
                        <button
                            type="button"
                            class="ghost-admin-btn ghost-admin-btn-small"
                            data-edit-brand="{{ $marca->Id }}"
                            data-brand-name="{{ $marca->Nombre }}"
                        >
                            Editar
                        </button>
                        <button type="button" class="danger-inline-btn danger-inline-btn-soft" data-delete-url="{{ route('admin.marcas.destroy', $marca->Id) }}" data-delete-label="marca {{ $marca->Nombre }}">
                            Borrar
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">No hay marcas registradas.</div>
            @endforelse
        </div>
    </div>
</div>
