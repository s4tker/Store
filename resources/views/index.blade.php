<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ElectroShop | Tecnología y Más</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <nav class="bg-[#0f172a] sticky top-0 z-[60] px-3 md:px-8 py-3 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-2 md:gap-8">

            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-8 h-8 md:w-10 md:h-10" alt="ElectroShop">
            </a>

            @auth
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-[#0f172a] px-3 py-1.5 rounded-lg transition-all active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4z"></path></svg>
                        <span class="text-[11px] font-black uppercase hidden sm:block">Panel Admin</span>
                        <span class="text-[10px] font-black uppercase sm:hidden">Admin</span>
                    </a>
                @endif
            @endauth

            <div class="flex-1 min-w-0 flex bg-slate-800/50 rounded-xl px-2 md:px-4 py-1.5 items-center border border-slate-700 focus-within:border-blue-500 transition-all">
                <input id="q" type="text" value="{{ $Search }}" placeholder="Buscar productos..."
                    class="flex-1 bg-transparent outline-none text-[13px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0">
                <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-bold text-xs px-2">Buscar</button>
            </div>

            <div class="flex items-center gap-2 md:gap-6 shrink-0">
                @auth
                    <a href="{{ route('account') }}" class="flex flex-col items-end">
                        <span class="hidden md:block text-[10px] text-slate-500 font-bold uppercase tracking-widest">Mi Cuenta</span>
                        <span class="text-[12px] md:text-sm font-bold text-slate-200 truncate max-w-[60px] md:max-w-none">{{ Auth::user()->Alias }}</span>
                    </a>
                @else
                    <button type="button" onclick="openAuthModal()" class="flex flex-col items-end">
                        <span class="text-[12px] md:text-sm font-bold text-slate-200">Ingresar</span>
                    </button>
                @endauth

                <button type="button" onclick="ToggleCart(true)" class="relative bg-blue-600 text-white p-2 md:p-2.5 rounded-lg md:rounded-xl shadow-lg active:scale-90 transition-transform">
                    <span id="CartCount" class="absolute -top-2 -right-2 hidden min-w-5 h-5 px-1 rounded-full bg-amber-400 text-[#0f172a] text-[10px] font-black items-center justify-center"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4 md:p-10">
        <header class="mb-8">
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tighter uppercase italic">
                Explora lo <span class="text-blue-600">Último</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-500 font-bold uppercase tracking-[0.2em] mt-3">Tecnología, audio y accesorios</p>
        </header>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @forelse($Products as $P)
                <a href="{{ route('product.show', $P) }}" class="product-card p-3 md:p-5 group">
                    <div class="aspect-square mb-3 md:mb-4 bg-slate-50 rounded-[1.2rem] md:rounded-[1.5rem] flex items-center justify-center p-4 overflow-hidden">
                        <img src="{{ $P->image_url }}" alt="{{ $P->Nombre }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <p class="text-[8px] md:text-[9px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">
                        {{ $P->marca?->Nombre ?? 'Marca libre' }}
                    </p>
                    <h3 class="text-slate-800 font-bold text-[12px] md:text-sm line-clamp-2 min-h-[2.5rem] group-hover:text-blue-600 transition-colors">
                        {{ $P->Nombre }}
                    </h3>
                    <p class="text-lg md:text-xl font-black text-slate-900 mt-2 italic">S/.{{ number_format($P->Precio, 2) }}</p>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-500 font-bold uppercase">No se encontraron productos.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div id="CartOverlay" class="fixed inset-0 z-[85] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleCart(false)"></div>
    <aside id="CartDrawer" class="fixed right-0 top-0 z-[90] h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300">
        <div class="h-full flex flex-col">
            <div class="px-6 py-5 border-b flex items-center justify-between">
                <h2 class="text-xl font-black italic uppercase text-slate-900">Tu Carrito</h2>
                <button onclick="ToggleCart(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
            </div>
            <div id="CartItems" class="flex-1 overflow-y-auto px-6 py-6 space-y-4"></div>
            <div class="p-6 border-t bg-slate-50">
                <div class="flex items-center justify-between font-black uppercase text-xs mb-4">
                    <span>Total estimado</span>
                    <span id="CartTotal" class="text-lg italic text-blue-600">S/.0.00</span>
                </div>
                <button class="btn-primary-es opacity-60 cursor-not-allowed">Próximamente Checkout</button>
            </div>
        </div>
    </aside>

    <div id="AuthModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 bg-[#0f172a]/90 backdrop-blur-xl transition-all" onclick="closeAuthModal()">
        <div id="ModalContainer" class="bg-white w-full max-w-[95%] md:max-w-4xl rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl overflow-hidden translate-y-full md:translate-y-0 md:scale-95 opacity-0 flex flex-col md:flex-row min-h-[450px] transition-all duration-500" onclick="event.stopPropagation()">
            <div class="hidden md:flex flex-1 bg-slate-50 items-center justify-center border-r">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-40 h-40 shadow-2xl">
            </div>
            <div class="flex-1 p-8 md:p-16 flex flex-col justify-center">
                <h2 class="text-2xl font-black italic uppercase mb-2">Electro<span class="text-blue-600">Shop</span></h2>
                <p id="AuthSubtitle" class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-8">Ingreso al sistema</p>

                <div class="space-y-4">
                    <input id="AuthEmail" type="email" placeholder="Correo electrónico" class="auth-input">
                    <div id="PassWrapper" class="hidden">
                        <input id="AuthPass" type="password" placeholder="Tu contraseña" class="auth-input">
                    </div>
                    <button id="AuthBtn" onclick="handleAuthStep()" class="btn-primary-es">Continuar</button>
                    <div id="AuthAlert" class="hidden p-3 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded-xl text-center"></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
