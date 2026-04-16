<div class="admin-grid">
    {{-- Formulario para registrar marcas --}}
    <div class="admin-card">
        <div class="admin-card-header admin-card-header-tight">
            <div>
                <p class="admin-kicker" id="BrandFormEyebrow">Fabricantes</p>
                <h2 id="BrandFormTitle">Nueva marca</h2>
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
                <p class="admin-kicker">Listado actual</p>
                <h2>Marcas registradas</h2>
            </div>
        </div>

        <div class="chip-list">
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
                        <button type="button" class="danger-inline-btn is-chip" data-delete-url="{{ route('admin.marcas.destroy', $marca->Id) }}" data-delete-label="marca {{ $marca->Nombre }}">
                            ×
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">No hay marcas registradas.</div>
            @endforelse
        </div>
    </div>
</div>
