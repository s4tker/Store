<div class="grid lg:grid-cols-2 gap-8">
    {{-- Formulario de categorías: permite crear principal o subcategoría --}}
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm h-fit">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1" id="CategoryFormEyebrow">categorías</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight" id="CategoryFormTitle">Registrar categoría</h2>
            </div>
            <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" id="BtnResetCategoryForm">Nueva</button>
        </div>

        <form
            id="FormAddCategoria"
            action="{{ route('admin.categorias.store') }}"
            method="POST"
            class="space-y-5"
            data-store-url="{{ route('admin.categorias.store') }}"
            data-update-base="{{ url('/admin/categorias') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="POST" id="CategoryFormMethod">
            <input type="hidden" id="EditingCategoryId" value="">

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="CategoryName">Nombre</label>
                <input type="text" name="Nombre" id="CategoryName" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="Ej: Televisores">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="SelectCategoryType">Tipo</label>
                <select name="TipoCategoria" id="SelectCategoryType" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                    <option value="principal">Principal</option>
                    <option value="subcategoria">Subcategoría</option>
                </select>
            </div>

            <div id="CategoryParentWrapper">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2" for="CategoryParentSelect">Categoría padre</label>
                <select name="ParentId" id="CategoryParentSelect" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <option value="">Categoría principal</option>
                    @foreach($Categorias as $categoria)
                        <option value="{{ $categoria->Id }}">{{ $categoria->Nombre }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 font-medium mt-2">Solo se activa cuando eliges crear una subcategoría.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-sm shadow-blue-600/20" id="BtnSubmitCategory">Guardar categoría</button>
            </div>
        </form>
    </div>

    {{-- Vista de jerarquía actual de categorías --}}
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm h-fit">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">listado</p>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Categorías y subcategorías</h2>
            </div>
            <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" data-toggle-target="CategoryListPanel" data-toggle-label-show="Ver categorías" data-toggle-label-hide="Ocultar categorías">Ver categorías</button>
        </div>

        <div id="CategoryListPanel" class="space-y-4 hidden">
            @forelse($Categorias as $categoria)
                <article class="border border-slate-100 rounded-2xl overflow-hidden">
                    <div class="bg-slate-50 px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <strong class="block text-slate-900 text-sm font-black">{{ $categoria->Nombre }}</strong>
                            <span class="text-xs font-bold text-slate-400">{{ $categoria->subcategorias->count() }} subcategoría(s)</span>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <button
                                type="button"
                                class="text-slate-400 hover:text-blue-600 font-bold text-[11px] uppercase tracking-widest transition-colors px-2 py-1"
                                data-edit-category="{{ $categoria->Id }}"
                                data-category-name="{{ $categoria->Nombre }}"
                                data-category-type="principal"
                                data-category-parent=""
                            >
                                Editar
                            </button>
                            <button type="button" class="text-red-400 hover:text-red-600 font-bold text-[11px] uppercase tracking-widest transition-colors px-2 py-1" data-delete-url="{{ route('admin.categorias.destroy', $categoria->Id) }}" data-delete-label="categoría {{ $categoria->Nombre }}">
                                Borrar
                            </button>
                        </div>
                    </div>

                    @if($categoria->subcategorias->isNotEmpty())
                        <div class="divide-y divide-slate-100/50">
                            @foreach($categoria->subcategorias as $subcategoria)
                                <div class="px-5 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white hover:bg-slate-50/50 transition-colors">
                                    <span class="text-sm font-medium text-slate-600">{{ $subcategoria->Nombre }}</span>
                                    <div class="flex items-center gap-2 self-end sm:self-auto">
                                        <button
                                            type="button"
                                            class="text-slate-400 hover:text-blue-600 font-bold text-[11px] uppercase tracking-widest transition-colors px-2 py-1"
                                            data-edit-category="{{ $subcategoria->Id }}"
                                            data-category-name="{{ $subcategoria->Nombre }}"
                                            data-category-type="subcategoria"
                                            data-category-parent="{{ $categoria->Id }}"
                                        >
                                            Editar
                                        </button>
                                        <button type="button" class="text-red-400 hover:text-red-600 font-bold text-[11px] uppercase tracking-widest transition-colors px-2 py-1" data-delete-url="{{ route('admin.categorias.destroy', $subcategoria->Id) }}" data-delete-label="subcategoría {{ $subcategoria->Nombre }}">
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm font-medium text-slate-500">
                    No hay categorías registradas.
                </div>
            @endforelse
        </div>
    </div>
</div>
