@extends('layouts.admin')

@section('title', 'Panel Admin | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div class="admin-page -mx-4 md:-mx-10">
    <div class="flex min-h-screen">
        <div id="AdminNavOverlay" class="fixed inset-0 z-40 hidden bg-slate-950/30 backdrop-blur-sm lg:hidden" onclick="ToggleAdminNav(false)"></div>

        <aside id="AdminNavDrawer" class="admin-mobile-drawer fixed inset-y-0 left-0 z-50 flex w-[20.5rem] max-w-[92vw] flex-col bg-transparent p-3 sm:p-4 lg:relative lg:translate-x-0 lg:w-[19.25rem] lg:pl-7 lg:pr-2 lg:py-6">
            <div class="admin-sidebar-shell flex h-full flex-col rounded-[1.6rem] border p-5 text-slate-200 shadow-2xl">
                <div class="flex items-center justify-between gap-3 border-b border-white/10 pb-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <x-admin.icon tone="slate" size="md" class="admin-sidebar-brand-icon bg-white/10 text-slate-100 ring-white/10">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 4l8 3.5M5 9.5V16a2 2 0 0 0 1.27 1.86l4.98 1.98a2 2 0 0 0 1.5 0l4.98-1.98A2 2 0 0 0 19 16V9.5M12 12l7-2.5M12 12 5 9.5M12 12v7.5"/>
                            </svg>
                        </x-admin.icon>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold tracking-[0.18em] text-white uppercase">Admin</p>
                        </div>
                    </a>

                    <button type="button" class="rounded-full p-2 text-slate-400 transition hover:bg-white/10 hover:text-white lg:hidden" onclick="ToggleAdminNav(false)" aria-label="Cerrar menú">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-5 rounded-[1.2rem] bg-white/5 p-3">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between rounded-[0.9rem] bg-white/5 px-3 py-2.5">
                            <span class="text-xs text-slate-400">Productos</span>
                            <span class="text-sm font-semibold text-white">{{ $Productos->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-[0.9rem] bg-white/5 px-3 py-2.5">
                            <span class="text-xs text-slate-400">Categorías</span>
                            <span class="text-sm font-semibold text-white">{{ $TodasLasCategorias->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-[0.9rem] bg-white/5 px-3 py-2.5">
                            <span class="text-xs text-slate-400">Marcas</span>
                            <span class="text-sm font-semibold text-white">{{ $Marcas->count() }}</span>
                        </div>
                    </div>
                </div>

                <nav class="mt-5 flex-1 space-y-1.5 overflow-y-auto">
                    <a href="{{ route('admin.productos.index') }}" class="admin-sidebar-link">
                        <x-admin.icon tone="blue" size="sm" class="admin-sidebar-icon bg-sky-400/12 text-sky-200 ring-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </x-admin.icon>
                        <span class="admin-sidebar-label">Productos</span>
                    </a>

                    <button type="button" class="admin-sidebar-button active" data-section="categorias">
                        <x-admin.icon tone="violet" size="sm" class="admin-sidebar-icon bg-violet-400/12 text-violet-200 ring-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A1.75 1.75 0 0 1 5.75 5h4.5A1.75 1.75 0 0 1 12 6.75v4.5A1.75 1.75 0 0 1 10.25 13h-4.5A1.75 1.75 0 0 1 4 11.25zm8 0A1.75 1.75 0 0 1 13.75 5h4.5A1.75 1.75 0 0 1 20 6.75v4.5A1.75 1.75 0 0 1 18.25 13h-4.5A1.75 1.75 0 0 1 12 11.25zm-8 8A1.75 1.75 0 0 1 5.75 13h4.5A1.75 1.75 0 0 1 12 14.75v4.5A1.75 1.75 0 0 1 10.25 21h-4.5A1.75 1.75 0 0 1 4 19.25zm8 0A1.75 1.75 0 0 1 13.75 13h4.5A1.75 1.75 0 0 1 20 14.75v4.5A1.75 1.75 0 0 1 18.25 21h-4.5A1.75 1.75 0 0 1 12 19.25z"/>
                            </svg>
                        </x-admin.icon>
                        <span class="admin-sidebar-label">Categorías</span>
                    </button>

                    <button type="button" class="admin-sidebar-button" data-section="marcas">
                        <x-admin.icon tone="emerald" size="sm" class="admin-sidebar-icon bg-emerald-400/12 text-emerald-200 ring-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 7.5h.01M5 3h6.76a2 2 0 0 1 1.41.59l6.24 6.24a2 2 0 0 1 0 2.82l-6.76 6.76a2 2 0 0 1-2.82 0l-6-6A2 2 0 0 1 3 11.99V5a2 2 0 0 1 2-2z"/>
                            </svg>
                        </x-admin.icon>
                        <span class="admin-sidebar-label">Marcas</span>
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}" class="admin-sidebar-link">
                        <x-admin.icon tone="indigo" size="sm" class="admin-sidebar-icon bg-cyan-400/12 text-cyan-200 ring-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 19a4 4 0 0 0-8 0m11 0a3 3 0 0 0-3-3m3 3v1H5v-1m14 0a3 3 0 0 0-3-3m-8 3a3 3 0 0 0-3-3m3 3v1m0-10a4 4 0 1 0 8 0 4 4 0 0 0-8 0zm11 1a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            </svg>
                        </x-admin.icon>
                        <span class="admin-sidebar-label">Usuarios</span>
                    </a>

                    <a href="{{ route('admin.estadisticas.index') }}" class="admin-sidebar-link">
                        <x-admin.icon tone="amber" size="sm" class="admin-sidebar-icon bg-fuchsia-400/12 text-fuchsia-200 ring-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19h16M7 16V9m5 7V5m5 11v-4"/>
                            </svg>
                        </x-admin.icon>
                        <span class="admin-sidebar-label">Estadísticas</span>
                    </a>
                </nav>

                <div class="mt-5 border-t border-white/10 pt-4">
                    <a href="{{ route('logout') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-white/8 px-3.5 py-2.5 text-[11px] font-medium uppercase tracking-[0.16em] text-rose-300 transition hover:bg-white/12 hover:text-rose-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 16l4-4m0 0-4-4m4 4H9m4 8v1a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <div class="admin-shell px-4 py-4 md:px-6 lg:px-8">
                <div class="mb-4 flex items-center justify-between rounded-[1.25rem] border border-white/70 bg-white/80 px-4 py-3 backdrop-blur lg:hidden">
                    <div class="flex items-center gap-3">
                        <x-admin.icon tone="slate" size="sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 4l8 3.5M5 9.5V16a2 2 0 0 0 1.27 1.86l4.98 1.98a2 2 0 0 0 1.5 0l4.98-1.98A2 2 0 0 0 19 16V9.5"/>
                            </svg>
                        </x-admin.icon>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Panel Admin</p>
                            <p class="text-xs text-slate-400">ElectroShop</p>
                        </div>
                    </div>

                    <button type="button" class="rounded-full border border-slate-200 bg-white p-2 text-slate-600" onclick="ToggleAdminNav(true)" aria-label="Abrir menú">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16"/>
                        </svg>
                    </button>
                </div>

                <main class="space-y-5 pb-8">
                    <section class="admin-surface p-4 md:p-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div class="min-w-0">
                                <p class="admin-card-kicker">Panel</p>
                                <h1 class="admin-title mt-2">Administración</h1>
                            </div>

                            <div class="grid w-full max-w-xl gap-3 sm:grid-cols-3">
                                <x-admin.stat-card label="Productos" :value="$Productos->count()" tone="blue">
                                    <x-slot:icon>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                    </x-slot:icon>
                                </x-admin.stat-card>

                                <x-admin.stat-card label="Categorías" :value="$TodasLasCategorias->count()" tone="violet">
                                    <x-slot:icon>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A1.75 1.75 0 0 1 5.75 5h4.5A1.75 1.75 0 0 1 12 6.75v4.5A1.75 1.75 0 0 1 10.25 13h-4.5A1.75 1.75 0 0 1 4 11.25zm8 8A1.75 1.75 0 0 1 13.75 13h4.5A1.75 1.75 0 0 1 20 14.75v4.5A1.75 1.75 0 0 1 18.25 21h-4.5A1.75 1.75 0 0 1 12 19.25zm0-12A1.75 1.75 0 0 1 13.75 5h4.5A1.75 1.75 0 0 1 20 6.75v4.5A1.75 1.75 0 0 1 18.25 13h-4.5A1.75 1.75 0 0 1 12 11.25z"/>
                                        </svg>
                                    </x-slot:icon>
                                </x-admin.stat-card>

                                <x-admin.stat-card label="Marcas" :value="$Marcas->count()" tone="emerald">
                                    <x-slot:icon>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 7.5h.01M5 3h6.76a2 2 0 0 1 1.41.59l6.24 6.24a2 2 0 0 1 0 2.82l-6.76 6.76a2 2 0 0 1-2.82 0l-6-6A2 2 0 0 1 3 11.99V5a2 2 0 0 1 2-2z"/>
                                        </svg>
                                    </x-slot:icon>
                                </x-admin.stat-card>
                            </div>
                        </div>
                    </section>

                    <div class="sticky top-4 z-20 rounded-[1.5rem] border border-white/80 bg-white/75 p-2 backdrop-blur">
                        <nav class="no-scrollbar flex gap-2 overflow-x-auto">
                            <a href="{{ route('admin.productos.index') }}" class="admin-tab shrink-0">Productos</a>
                            <button type="button" class="admin-tab shrink-0" data-section="categorias">Categorías</button>
                            <button type="button" class="admin-tab shrink-0" data-section="marcas">Marcas</button>
                            <a href="{{ route('admin.usuarios.index') }}" class="admin-tab shrink-0">Usuarios</a>
                            <a href="{{ route('admin.estadisticas.index') }}" class="admin-tab shrink-0">Estadísticas</a>
                        </nav>
                    </div>

                    <section class="space-y-6">
                        <div class="admin-section" id="section-categorias">
                            @include('Admin.sections.categorias')
                        </div>

                        <div class="admin-section hidden" id="section-marcas">
                            @include('Admin.sections.marcas')
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
