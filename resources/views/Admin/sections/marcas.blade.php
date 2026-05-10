<div class="grid lg:grid-cols-2 gap-8">
    {{-- Formulario para registrar marcas --}}
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm h-fit">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1" id="BrandFormEyebrow">marcas</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight" id="BrandFormTitle">Nueva marca</h2>
            </div>
            <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" id="BtnResetBrandForm">Nueva</button>
        </div>

        <form
            id="FormAddMarca"
            action="{{ route('admin.marcas.store') }}"
            method="POST"
            class="space-y-5"
            data-store-url="{{ route('admin.marcas.store') }}"
            data-update-base="{{ url('/admin/marcas') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="BrandFormMethod">
            <input type="hidden" id="EditingBrandId" value="">

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="BrandName">Nombre</label>
                <input type="text" name="Nombre" id="BrandName" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="Ej: Apple, Sony, HP">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-sm shadow-blue-600/20" id="BtnSubmitBrand">Guardar marca</button>
            </div>
        </form>
    </div>

    {{-- Listado compacto de marcas registradas --}}
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm h-fit">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">listado</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Marcas registradas</h2>
            </div>
            <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" data-toggle-target="BrandListPanel" data-toggle-label-show="Ver marcas" data-toggle-label-hide="Ocultar marcas">Ver marcas</button>
        </div>

        <div id="BrandListPanel" class="hidden flex flex-wrap gap-2">
            @forelse($Marcas as $marca)
                <div class="group flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-full pl-4 pr-1.5 py-1.5 hover:bg-white hover:border-slate-300 transition-all">
                    <span class="text-sm font-bold text-slate-700">{{ $marca->Nombre }}</span>
                    <div class="flex items-center gap-1 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                        <button
                            type="button"
                            class="bg-slate-100 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-full w-7 h-7 flex items-center justify-center transition-colors"
                            data-edit-brand="{{ $marca->Id }}"
                            data-brand-name="{{ $marca->Nombre }}"
                            title="Editar"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button type="button" class="bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-full w-7 h-7 flex items-center justify-center transition-colors" data-delete-url="{{ route('admin.marcas.destroy', $marca->Id) }}" data-delete-label="marca {{ $marca->Nombre }}" title="Borrar">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="w-full bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm font-medium text-slate-500">
                    No hay marcas registradas.
                </div>
            @endforelse
        </div>
    </div>
</div>
