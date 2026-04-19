<nav class="bg-[#0f172a] sticky top-0 z-[60] px-4 md:px-8 py-4 shadow-2xl shadow-blue-900/10">
    <div class="max-w-7xl mx-auto flex items-center gap-4 md:gap-10">

        <a href="{{ route('home') }}" class="shrink-0 transition-transform hover:scale-105">
            <img src="{{ asset('img/logo/logo.png') }}" class="w-9 h-9 md:w-11 md:h-11 rounded-xl shadow-lg shadow-blue-500/20" alt="ElectroShop">
        </a>

        @auth
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('administrador'))
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 px-4 py-2 rounded-xl transition-all active:scale-95 shrink-0 shadow-lg shadow-amber-500/20">
                    <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span class="text-[11px] font-black uppercase hidden sm:block text-slate-900">Panel Admin</span>
                </a>
            @endif
        @endauth

        <div class="flex-1 min-w-0 flex bg-slate-800/40 rounded-2xl px-4 py-2 items-center border border-slate-700/50 focus-within:border-blue-500/50 focus-within:bg-slate-800/60 transition-all group">
            <svg class="w-4 h-4 text-slate-500 group-focus-within:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input id="q" type="text" placeholder="¿Qué estás buscando hoy?"
                class="flex-1 bg-transparent outline-none text-[13px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0 border-none focus:ring-0">
            <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-extrabold text-xs px-2 hover:text-blue-300">BUSCAR</button>
        </div>

        <div class="flex items-center gap-3 md:gap-8 shrink-0">
            @auth
                <a href="{{ route('account') }}" class="flex flex-col items-end group">
                    <span class="hidden md:block text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] group-hover:text-blue-400 transition-colors">Mi Perfil</span>
                    <span class="text-[13px] md:text-sm font-bold text-slate-100 group-hover:text-white">{{ Auth::user()->Nombre }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-[13px] md:text-sm font-bold text-slate-200 hover:text-white bg-slate-800 px-4 py-2 rounded-xl">Ingresar</a>
            @endauth

            <button type="button" onclick="ToggleCart(true)" class="relative bg-blue-600 hover:bg-blue-500 text-white p-2.5 rounded-xl shadow-xl shadow-blue-600/20 active:scale-90 transition-all">
                <span id="CartCount" class="absolute -top-1.5 -right-1.5 hidden min-w-[20px] h-5 px-1 rounded-full bg-amber-400 text-slate-900 text-[10px] font-black flex items-center justify-center border-2 border-[#0f172a]">0</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
            </button>
        </div>
    </div>
</nav>
