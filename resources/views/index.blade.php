<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ElectroShop</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50">

    <nav class="bg-[#0f172a] sticky top-0 z-[60] px-3 md:px-8 py-3 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-2 md:gap-8">
            <button type="button" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-white" onclick="ToggleMobileCatalog(true)" aria-label="Abrir menú">
                <span class="space-y-1.5">
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                </span>
            </button>

            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-8 h-8 md:w-10 md:h-10 rounded-full" alt="ElectroShop">
            </a>

            @auth
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('administrador'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-[#0f172a] px-3 py-1.5 rounded-lg transition-all active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4z"></path></svg>
                        <span class="text-[11px] font-black uppercase hidden sm:block text-slate-900">Panel Admin</span>
                        <span class="text-[10px] font-black uppercase sm:hidden text-slate-900">Admin</span>
                    </a>
                @endif
            @endauth

            <div class="flex-1 min-w-0 flex bg-slate-800/50 rounded-xl px-2 md:px-4 py-1.5 items-center border border-slate-700 focus-within:border-blue-500 transition-all">
                <input id="q" type="text" value="{{ $Search ?? '' }}" placeholder="Buscar productos..."
                    class="flex-1 bg-transparent outline-none text-[13px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0 border-none focus:ring-0">
                <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-bold text-xs px-2">Buscar</button>
            </div>

            <div class="flex items-center gap-2 md:gap-6 shrink-0">
                @auth
                    <a href="{{ route('account') }}" class="flex flex-col items-end">
                        <span class="hidden md:block text-[10px] text-slate-500 font-bold uppercase tracking-widest">Mi Cuenta</span>
                        <span class="text-[12px] md:text-sm font-bold text-slate-200 truncate max-w-[60px] md:max-w-none">{{ Auth::user()->Nombre }}</span>
                    </a>
                @else
                    <button type="button" onclick="openAuthModal()" class="flex flex-col items-end">
                        <span class="text-[12px] md:text-sm font-bold text-slate-200">Ingresar</span>
                    </button>
                @endauth

                <button type="button" onclick="ToggleCart(true)" class="relative bg-blue-600 text-white p-2 md:p-2.5 rounded-lg md:rounded-xl shadow-lg active:scale-90 transition-transform">
                    <span id="CartCount" class="absolute -top-2 -right-2 hidden min-w-5 h-5 px-1 rounded-full bg-amber-400 text-[#0f172a] text-[10px] font-black items-center justify-center">0</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4 md:p-10">
        <header class="mb-8">
            <h1 class="text-xl md:text-3xl font-black text-slate-900 tracking-tighter uppercase italic">
                Explora lo <span class="text-blue-600">Último</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-500 font-bold uppercase tracking-[0.2em] mt-3">Tecnología, audio y accesorios</p>
        </header>

        <section class="mb-8 rounded-[2rem] border border-slate-200 bg-white p-4 md:p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Filtros del catálogo</p>
                    <h2 class="mt-2 text-lg font-black text-slate-900">Encuentra por categoría</h2>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-[11px] font-black uppercase tracking-[0.2em] text-slate-600 transition-colors hover:border-blue-200 hover:text-blue-600">Limpiar filtros</a>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                <div>
                    <label for="IndexCategoryFilter" class="mb-2 block text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Categoría</label>
                    <select id="IndexCategoryFilter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition-colors focus:border-blue-500 focus:bg-white">
                        <option value="">Todas</option>
                        @foreach($Categorias as $categoria)
                            <option value="{{ $categoria->Id }}" @selected((int) $SelectedCategory === (int) $categoria->Id)>{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="IndexSubcategoryFilter" class="mb-2 block text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Subcategoría</label>
                    <select id="IndexSubcategoryFilter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition-colors focus:border-blue-500 focus:bg-white">
                        <option value="">Todas</option>
                        @foreach($Categorias as $categoria)
                            @foreach($categoria->subcategorias as $subcategoria)
                                <option value="{{ $subcategoria->Id }}" data-parent="{{ $categoria->Id }}" @selected((int) $SelectedSubcategory === (int) $subcategoria->Id)>{{ $categoria->Nombre }} / {{ $subcategoria->Nombre }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="ApplyCatalogFilters()" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[11px] font-black uppercase tracking-[0.2em] text-white transition-colors hover:bg-blue-600">Filtrar</button>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($Categorias as $categoria)
                    <button type="button" class="rounded-full border px-3 py-2 text-[11px] font-black uppercase tracking-[0.16em] transition-colors {{ (int) $SelectedCategory === (int) $categoria->Id ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:text-blue-700' }}" onclick="SetCategoryFilter('{{ $categoria->Id }}')">
                        {{ $categoria->Nombre }}
                    </button>
                @endforeach
            </div>
        </section>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @forelse($Products as $P)
                <a href="{{ route('product.show', $P->Slug) }}" class="product-card p-3 md:p-5 group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all block">
                    <div class="aspect-square mb-3 md:mb-4 bg-slate-50 rounded-[1.2rem] flex items-center justify-center p-4 overflow-hidden">
                        <img src="{{ $P->image_url }}" alt="{{ $P->Nombre }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <p class="text-[8px] md:text-[9px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">
                        {{ $P->marca?->Nombre ?? 'Marca libre' }}
                    </p>
                    <h3 class="text-slate-800 font-bold text-[11px] md:text-[13px] line-clamp-2 min-h-[2.3rem] group-hover:text-blue-600 transition-colors">
                        {{ $P->Nombre }}
                    </h3>
                    <p class="text-base md:text-lg font-black text-slate-900 mt-2 italic">S/.{{ number_format($P->display_price, 2) }}</p>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-500 font-bold uppercase">No se encontraron productos.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div id="MobileCatalogOverlay" class="fixed inset-0 z-[95] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleMobileCatalog(false)"></div>
    <aside id="MobileCatalogDrawer" class="fixed left-0 top-0 z-[100] h-full w-full max-w-sm -translate-x-full bg-white shadow-2xl transition-transform duration-300">
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
                            <a href="{{ route('account') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Mi cuenta</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div id="CartOverlay" class="fixed inset-0 z-[85] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleCart(false)"></div>
    <aside id="CartDrawer" class="fixed right-0 top-0 z-[90] h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300">
        <div class="h-full flex flex-col">
            <div class="px-6 py-5 border-b flex items-center justify-between">
                <h2 class="text-xl font-black italic uppercase text-slate-900">Tu Carrito</h2>
                <button onclick="ToggleCart(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
            </div>
            <div id="CartItems" class="flex-1 overflow-y-auto px-6 py-6 space-y-4"></div>
            <div class="p-6 border-t bg-slate-50">
                <div class="flex items-center justify-between font-black uppercase text-xs mb-4 text-slate-600">
                    <span>Total estimado</span>
                    <span id="CartTotal" class="text-lg italic text-blue-600">S/.0.00</span>
                </div>
                <button class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl opacity-60 cursor-not-allowed uppercase text-[11px] tracking-widest">Próximamente Checkout</button>
            </div>
        </div>
    </aside>

    <div id="AuthModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 bg-[#0f172a]/90 backdrop-blur-xl transition-all" onclick="closeAuthModal()">
        <div id="ModalContainer" class="bg-white w-full max-w-[95%] md:max-w-4xl rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl overflow-hidden translate-y-full md:translate-y-0 md:scale-95 opacity-0 flex flex-col md:flex-row min-h-[450px] transition-all duration-500" onclick="event.stopPropagation()">

            <div class="hidden md:flex flex-1 bg-slate-50 items-center justify-center border-r">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-40 h-40 shadow-2xl rounded-full">
            </div>

            <div class="flex-1 p-8 md:p-16 flex flex-col justify-center">
                <h2 class="text-2xl font-black italic uppercase mb-2">Electro<span class="text-blue-600">Shop</span></h2>
                <p id="AuthSubtitle" class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-8">Ingresa tu correo para continuar</p>

                <div class="space-y-4">
                    <input id="AuthEmail" type="email" placeholder="Correo electrónico" class="auth-input w-full p-4 bg-slate-100 rounded-2xl border-none font-bold">

                    <div id="PassWrapper" class="hidden relative">
                        <input id="AuthPass" type="password" placeholder="Escribe tu contraseña" class="auth-input w-full p-4 bg-slate-100 rounded-2xl border-none font-bold pr-12">

                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="eyePath" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>

                    <button id="AuthBtn" onclick="handleAuthStep()" class="btn-primary-es w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[11px] tracking-widest hover:bg-blue-600 transition-all">Continuar</button>

                    <div id="AuthAlert" class="hidden p-3 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded-xl text-center"></div>
                </div>

                <button onclick="closeAuthModal()" class="mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Cerrar</button>
            </div>
        </div>
    </div>

</body>
</html>
