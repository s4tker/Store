@extends('layouts.app')

@section('title', 'Panel Admin | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div id="AdminNavOverlay" class="admin-mobile-overlay hidden"></div>
<aside id="AdminNavDrawer" class="admin-mobile-drawer">
    <div class="admin-mobile-drawer-head">
        <a href="{{ route('home') }}" class="admin-mobile-brand">
            <img src="{{ asset('img/logo/logo.png') }}" alt="ElectroShop">
            <div>
                <p>ElectroShop</p>
                <span>Panel administrativo</span>
            </div>
        </a>
        <button type="button" class="admin-mobile-close" onclick="ToggleAdminNav(false)" aria-label="Cerrar menú">&times;</button>
    </div>

    <nav class="admin-mobile-links">
        <button type="button" class="admin-mobile-link active" data-section="productos">Productos</button>
        <button type="button" class="admin-mobile-link" data-section="categorias">Categorías</button>
        <button type="button" class="admin-mobile-link" data-section="marcas">Marcas</button>
        <a href="{{ route('admin.usuarios.index') }}" class="admin-mobile-link admin-mobile-link-anchor">Gestion usuarios</a>
        <a href="{{ route('admin.estadisticas.index') }}" class="admin-mobile-link admin-mobile-link-anchor">Estadísticas</a>
    </nav>
    <a href="{{ route('logout') }}" class="admin-mobile-logout">Cerrar sesión</a>
</aside>

<section class="admin-dashboard-shell">
    <header class="admin-hero">
        <div>
            <p class="admin-hero-kicker">panel admin</p>
            <h1 class="admin-hero-title">Gestiona productos, categorías y marcas.</h1>
            <p class="admin-hero-copy">Todo el panel reunido en una vista simple y más fácil de leer.</p>
        </div>
        <div class="admin-summary">
            <span>{{ $Productos->count() }} productos</span>
            <span>{{ $TodasLasCategorias->count() }} categorías</span>
            <span>{{ $Marcas->count() }} marcas</span>
        </div>
    </header>

    <div class="admin-sticky-nav-wrap">
        <nav class="admin-sticky-nav">
            <button type="button" class="admin-nav-tab active" data-section="productos">Productos</button>
            <button type="button" class="admin-nav-tab" data-section="categorias">Categorías</button>
            <button type="button" class="admin-nav-tab" data-section="marcas">Marcas</button>
            <a href="{{ route('admin.usuarios.index') }}" class="admin-nav-tab admin-nav-tab-link">Gestion usuarios</a>
            <a href="{{ route('admin.estadisticas.index') }}" class="admin-nav-tab admin-nav-tab-link">Estadísticas</a>
        </nav>
    </div>

    <section class="admin-sections">
        <div class="admin-section" id="section-productos">
            @include('Admin.sections.productos')
        </div>

        <div class="admin-section hidden" id="section-categorias">
            @include('Admin.sections.categorias')
        </div>

        <div class="admin-section hidden" id="section-marcas">
            @include('Admin.sections.marcas')
        </div>
    </section>
</section>

<div id="Toast" class="admin-toast"></div>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
