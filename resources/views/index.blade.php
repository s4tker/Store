@extends('layouts.app')

@section('title', 'ElectroShop - Tienda de Electrónica')

@section('content')
<div class="flex min-h-full flex-col">
<header class="mb-8">
    <h1 class="text-base sm:text-lg md:text-2xl font-black text-slate-900 tracking-tighter uppercase italic">
        Explora lo <span class="text-blue-600">Último</span>
    </h1>
    <p class="text-[9px] sm:text-[10px] md:text-[11px] text-slate-500 font-bold uppercase tracking-[0.16em] mt-3">Tecnología, audio y accesorios</p>
</header>

<section class="mb-8 rounded-[2rem] border border-slate-200 bg-white p-4 md:p-6 shadow-sm">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400">Filtros del catálogo</p>
            <h2 class="mt-2 text-sm sm:text-base font-black text-slate-900">Encuentra por categoría</h2>
        </div>
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-3 py-1.5 text-[9px] sm:text-[10px] font-black uppercase tracking-[0.14em] text-slate-600 transition-colors hover:border-blue-200 hover:text-blue-600">Limpiar filtros</a>
    </div>

    <div class="mt-5 grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
        <div>
            <label for="IndexCategoryFilter" class="mb-2 block text-[9px] sm:text-[10px] font-black uppercase tracking-[0.14em] text-slate-500">Categoría</label>
            <select id="IndexCategoryFilter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[12px] sm:text-[13px] font-bold text-slate-800 outline-none transition-colors focus:border-blue-500 focus:bg-white">
                <option value="">Todas</option>
                @foreach($Categorias as $categoria)
                <option value="{{ $categoria->Id }}" @selected((int) $SelectedCategory === (int) $categoria->Id)>{{ $categoria->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="IndexSubcategoryFilter" class="mb-2 block text-[9px] sm:text-[10px] font-black uppercase tracking-[0.14em] text-slate-500">Subcategoría</label>
            <select id="IndexSubcategoryFilter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[12px] sm:text-[13px] font-bold text-slate-800 outline-none transition-colors focus:border-blue-500 focus:bg-white">
                <option value="">Todas</option>
                @foreach($Categorias as $categoria)
                @foreach($categoria->subcategorias as $subcategoria)
                <option value="{{ $subcategoria->Id }}" data-parent="{{ $categoria->Id }}" @selected((int) $SelectedSubcategory === (int) $subcategoria->Id)>{{ $categoria->Nombre }} / {{ $subcategoria->Nombre }}</option>
                @endforeach
                @endforeach
            </select>
        </div>

        <button type="button" onclick="ApplyCatalogFilters()" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2.5 text-[9px] sm:text-[10px] font-black uppercase tracking-[0.14em] text-white transition-colors hover:bg-blue-600">Filtrar</button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($Categorias as $categoria)
        <button type="button" class="rounded-full border px-3 py-1.5 text-[9px] sm:text-[10px] font-black uppercase tracking-[0.12em] transition-colors {{ (int) $SelectedCategory === (int) $categoria->Id ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:text-blue-700' }}" onclick="SetCategoryFilter('{{ $categoria->Id }}')">
            {{ $categoria->Nombre }}
        </button>
        @endforeach
    </div>
</section>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
    @forelse($Products as $P)
    <a href="{{ route('product.show', $P->Slug) }}" class="product-card p-2.5 md:p-3.5 group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all block">
        <div class="aspect-square mb-2.5 md:mb-3 bg-slate-50 rounded-[1rem] flex items-center justify-center p-2.5 md:p-3 overflow-hidden">
            <img src="{{ $P->image_url }}" alt="{{ $P->Nombre }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform duration-700">
        </div>
        <p class="text-[7px] md:text-[8px] font-black text-blue-600 uppercase tracking-[0.16em] mb-1">
            {{ $P->marca?->Nombre ?? 'Marca libre' }}
        </p>
        <h3 class="text-slate-800 font-bold text-[9px] sm:text-[10px] md:text-[11px] line-clamp-2 min-h-[1.8rem] sm:min-h-[2rem] group-hover:text-blue-600 transition-colors">
            {{ $P->Nombre }}
        </h3>
        <p class="text-[13px] sm:text-sm md:text-base font-black text-slate-900 mt-1.5 italic">S/.{{ number_format($P->display_price, 2) }}</p>
    </a>
    @empty
    <div class="col-span-full py-20 text-center">
        <p class="text-slate-500 font-bold uppercase">No se encontraron productos.</p>
    </div>
    @endforelse
</div>

{{-- este panel solo se usa en movil --}}
<div id="MobileCatalogOverlay" class="fixed inset-0 z-[95] hidden bg-[#0f172a]/70 backdrop-blur-sm md:hidden" onclick="ToggleMobileCatalog(false)"></div>
<aside id="MobileCatalogDrawer" class="fixed left-0 top-0 z-[100] h-full w-full max-w-sm -translate-x-full bg-white shadow-2xl transition-transform duration-300 md:hidden">
    <div class="flex h-full flex-col">
        <div class="flex items-center justify-between border-b px-5 py-5">
            <h2 class="text-lg font-black uppercase italic text-slate-900">Explorar</h2>
            <button type="button" onclick="ToggleMobileCatalog(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
        </div>

        <div class="space-y-5 overflow-y-auto px-5 py-5">
            <div>
                <p class="mb-3 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Categorías</p>
                <div class="space-y-2">
                    @foreach($Categorias as $categoria)
                    <button type="button" class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm font-bold text-slate-800" onclick="SetCategoryFilter('{{ $categoria->Id }}')">
                        <span>{{ $categoria->Nombre }}</span>
                        <span class="text-xs text-slate-400">{{ $categoria->subcategorias->count() }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="mb-3 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Cuenta</p>
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Inicio</a>
                    @auth
                    <a href="{{ route('pedidos.index') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Pedidos</a>
                    <a href="{{ route('account') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Mi cuenta</a>
                    @else
                    <a href="{{ route('login') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Ingresar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</aside>

</div>
@endsection
