<div class="grid gap-6 xl:grid-cols-[minmax(0,0.86fr)_minmax(0,1.14fr)]">
    <section class="admin-panel p-6 md:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="admin-card-kicker" id="BrandFormEyebrow">Marcas</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" id="BrandFormTitle">Nueva marca</h2>
            </div>

            <button type="button" class="admin-button" id="BtnResetBrandForm">Nueva</button>
        </div>

        <form
            id="FormAddMarca"
            action="{{ route('admin.marcas.store') }}"
            method="POST"
            class="mt-8 space-y-5"
            data-store-url="{{ route('admin.marcas.store') }}"
            data-update-base="{{ url('/admin/marcas') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="BrandFormMethod">
            <input type="hidden" id="EditingBrandId" value="">

            <div>
                <label class="admin-label" for="BrandName">Nombre</label>
                <input type="text" name="Nombre" id="BrandName" required class="admin-input" placeholder="Apple">
            </div>

            <button type="submit" class="admin-button-primary w-full" id="BtnSubmitBrand">Guardar marca</button>
        </form>
    </section>

    <section class="admin-panel p-6 md:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="admin-card-kicker">Listado</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Marcas registradas</h2>
            </div>

            <button type="button" class="admin-button" data-toggle-target="BrandListPanel" data-toggle-label-show="Ver marcas" data-toggle-label-hide="Ocultar marcas">Ocultar marcas</button>
        </div>

        <div id="BrandListPanel" class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($Marcas as $marca)
                <article class="rounded-[1.35rem] border border-slate-200/80 bg-slate-50/80 p-4 transition hover:border-slate-300 hover:bg-white">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-admin.icon tone="emerald" size="sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 7.5h.01M5 3h6.76a2 2 0 0 1 1.41.59l6.24 6.24a2 2 0 0 1 0 2.82l-6.76 6.76a2 2 0 0 1-2.82 0l-6-6A2 2 0 0 1 3 11.99V5a2 2 0 0 1 2-2z"/>
                                </svg>
                            </x-admin.icon>
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $marca->Nombre }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="admin-button px-3"
                                data-edit-brand="{{ $marca->Id }}"
                                data-brand-name="{{ $marca->Nombre }}"
                                title="Editar"
                            >
                                Editar
                            </button>
                            <button type="button" class="admin-button-danger px-3" data-delete-url="{{ route('admin.marcas.destroy', $marca->Id) }}" data-delete-label="marca {{ $marca->Nombre }}" title="Borrar">
                                Borrar
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="admin-empty sm:col-span-2 xl:col-span-3">No hay marcas registradas.</div>
            @endforelse
        </div>
    </section>
</div>
