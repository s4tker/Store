@extends('layouts.app')

@section('title', 'Panel Admin | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div class="flex min-h-screen bg-slate-50 font-sans selection:bg-blue-100 selection:text-blue-900">
    
    <!-- Mobile Overlay -->
    <div id="AdminNavOverlay" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity lg:hidden" onclick="ToggleAdminNav(false)"></div>
    
    <!-- Sidebar / Drawer -->
    <aside id="AdminNavDrawer" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:w-64 flex flex-col border-r border-slate-100">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black overflow-hidden group-hover:scale-105 group-hover:bg-blue-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase tracking-tight">ElectroShop</p>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Admin Panel</span>
                </div>
            </a>
            <button type="button" class="lg:hidden text-slate-400 hover:text-slate-900 text-2xl font-black transition-colors" onclick="ToggleAdminNav(false)" aria-label="Cerrar menú">&times;</button>
        </div>

        <!-- Links -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 admin-mobile-links">
            <p class="px-4 py-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Menú Principal</p>
            
            <a href="{{ route('admin.productos.index') }}" class="admin-mobile-link-anchor flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-500 hover:text-blue-600 hover:bg-blue-50/50 font-bold text-xs uppercase tracking-wider transition-all">
                <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Productos
            </a>
            
            <button type="button" class="admin-mobile-link active w-full flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all [&.active]:bg-blue-50 [&.active]:text-blue-600 [&:not(.active)]:text-slate-500 [&:not(.active):hover]:text-blue-600 [&:not(.active):hover]:bg-blue-50/50" data-section="categorias">
                <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Categorías
            </button>
            
            <button type="button" class="admin-mobile-link w-full flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-xs uppercase tracking-wider transition-all [&.active]:bg-blue-50 [&.active]:text-blue-600 [&:not(.active)]:text-slate-500 [&:not(.active):hover]:text-blue-600 [&:not(.active):hover]:bg-blue-50/50" data-section="marcas">
                <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Marcas
            </button>
            
            <div class="pt-4 pb-2">
                <p class="px-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Sistema</p>
            </div>

            <a href="{{ route('admin.usuarios.index') }}" class="admin-mobile-link-anchor flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-500 hover:text-blue-600 hover:bg-blue-50/50 font-bold text-xs uppercase tracking-wider transition-all">
                <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Gestión usuarios
            </a>
            
            <a href="{{ route('admin.estadisticas.index') }}" class="admin-mobile-link-anchor flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-500 hover:text-blue-600 hover:bg-blue-50/50 font-bold text-xs uppercase tracking-wider transition-all">
                <svg class="w-5 h-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Estadísticas
            </a>
        </nav>
        
        <!-- Footer -->
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('logout') }}" class="flex items-center justify-center gap-2 w-full bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 px-4 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border border-slate-200 hover:border-red-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar sesión
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between p-4 bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="font-black text-xs uppercase tracking-widest text-slate-900">Admin</div>
            </div>
            <button type="button" class="text-slate-500 hover:text-slate-900 bg-slate-50 p-2 rounded-xl transition-colors" onclick="ToggleAdminNav(true)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <main class="flex-1 p-5 lg:p-10 w-full space-y-6 lg:space-y-8 max-w-[1600px] mx-auto">
            
            <!-- Hero Header -->
            <header class="flex flex-col xl:flex-row xl:items-end justify-between gap-6 bg-white p-8 lg:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <!-- Decorative background elements -->
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:bg-blue-100 transition-colors duration-700"></div>
                <div class="absolute right-40 -bottom-20 w-48 h-48 bg-slate-50 rounded-full blur-3xl opacity-50"></div>
                
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-2">Panel de Control</p>
                    <h1 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight uppercase italic leading-none">
                        Gestión <span class="text-slate-400">Admin</span>
                    </h1>
                    <p class="mt-4 text-slate-500 font-medium max-w-md">
                        Controla el inventario, revisa las estadísticas y administra el catálogo de tu tienda desde un solo lugar.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3 relative z-10">
                    <div class="bg-white text-slate-700 px-5 py-3 rounded-2xl flex items-center gap-3 border border-slate-200 shadow-sm font-bold text-xs">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Productos</p>
                            <p class="text-sm font-black">{{ $Productos->count() }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white text-slate-700 px-5 py-3 rounded-2xl flex items-center gap-3 border border-slate-200 shadow-sm font-bold text-xs">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Categorías</p>
                            <p class="text-sm font-black">{{ $TodasLasCategorias->count() }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white text-slate-700 px-5 py-3 rounded-2xl flex items-center gap-3 border border-slate-200 shadow-sm font-bold text-xs">
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Marcas</p>
                            <p class="text-sm font-black">{{ $Marcas->count() }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Navigation Tabs (Sticky Mobile, Static Desktop) -->
            <div class="sticky top-[65px] lg:static z-20 bg-slate-50/90 backdrop-blur-md pb-4 pt-2 -mx-5 px-5 lg:mx-0 lg:px-0">
                <nav class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                    <a href="{{ route('admin.productos.index') }}" class="admin-nav-tab-link shrink-0 bg-white hover:bg-slate-100 text-slate-600 hover:text-blue-600 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border border-slate-200 shadow-sm">
                        Productos
                    </a>
                    <button type="button" class="admin-nav-tab shrink-0 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border shadow-sm outline-none [&.active]:bg-slate-900 [&.active]:text-white [&.active]:border-slate-900 [&:not(.active)]:bg-white [&:not(.active)]:text-slate-600 [&:not(.active)]:border-slate-200 [&:not(.active):hover]:text-blue-600 [&:not(.active):hover]:bg-slate-100 active" data-section="categorias">
                        Categorías
                    </button>
                    <button type="button" class="admin-nav-tab shrink-0 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border shadow-sm outline-none [&.active]:bg-slate-900 [&.active]:text-white [&.active]:border-slate-900 [&:not(.active)]:bg-white [&:not(.active)]:text-slate-600 [&:not(.active)]:border-slate-200 [&:not(.active):hover]:text-blue-600 [&:not(.active):hover]:bg-slate-100" data-section="marcas">
                        Marcas
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="admin-nav-tab-link shrink-0 bg-white hover:bg-slate-100 text-slate-600 hover:text-blue-600 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border border-slate-200 shadow-sm">
                        Gestión usuarios
                    </a>
                    <a href="{{ route('admin.estadisticas.index') }}" class="admin-nav-tab-link shrink-0 bg-white hover:bg-slate-100 text-slate-600 hover:text-blue-600 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all border border-slate-200 shadow-sm">
                        Estadísticas
                    </a>
                </nav>
            </div>

            <!-- Content Sections Container -->
            <section class="admin-sections space-y-6">
                <!-- Categorías -->
                <div class="admin-section bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-sm" id="section-categorias">
                    @include('Admin.sections.categorias')
                </div>

                <!-- Marcas -->
                <div class="admin-section bg-white p-6 lg:p-8 rounded-[2rem] border border-slate-100 shadow-sm hidden" id="section-marcas">
                    @include('Admin.sections.marcas')
                </div>
            </section>

        </main>
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>

<style>
/* Utilities para ocultar barra de scroll en los tabs de navegación */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
