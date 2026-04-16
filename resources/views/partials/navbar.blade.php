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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-[#0f172a] px-2.5 md:px-3 py-1.5 rounded-lg transition-all active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4z"></path></svg>
                    <span class="text-[10px] md:text-[11px] font-black uppercase hidden sm:block text-slate-900">Panel Admin</span>
                    <span class="text-[9px] font-black uppercase sm:hidden text-slate-900">Admin</span>
                </a>
            @endif
        @endauth

        <div class="flex-1 min-w-0 flex bg-slate-800/50 rounded-xl px-2 md:px-4 py-1.5 items-center border border-slate-700 focus-within:border-blue-500 transition-all">
            <input id="q" type="text" value="{{ $Search ?? '' }}" placeholder="Buscar productos..."
                class="flex-1 bg-transparent outline-none text-[12px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0 border-none focus:ring-0">
            <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-bold text-[11px] px-2">Buscar</button>
        </div>

        <div class="flex items-center gap-2 md:gap-6 shrink-0">
            @auth
                <a href="{{ route('account') }}" class="flex flex-col items-end">
                    <span class="hidden md:block text-[9px] text-slate-500 font-bold uppercase tracking-widest">Mi Cuenta</span>
                    <span class="text-[11px] md:text-sm font-bold text-slate-200 truncate max-w-[60px] md:max-w-none">{{ Auth::user()->Nombre }}</span>
                </a>
            @else
                <button type="button" onclick="openAuthModal()" class="flex flex-col items-end">
                    <span class="text-[11px] md:text-sm font-bold text-slate-200">Ingresar</span>
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
