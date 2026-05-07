@extends('layouts.app')

@section('title', 'Productos | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<section class="admin-page-shell admin-dashboard-shell">
    <header class="admin-page-hero">
        <div class="admin-page-hero-copy">
            <a href="{{ route('admin.dashboard') }}" class="admin-page-backlink">Volver al dashboard</a>
            <p class="admin-page-kicker">productos</p>
            <h1 class="admin-page-title">Gestión de productos</h1>
        </div>
        <div class="admin-page-stats">
            <span>{{ $Productos->count() }} productos</span>
            <span>{{ $Categorias->count() }} categorías</span>
            <span>{{ $Marcas->count() }} marcas</span>
        </div>
    </header>

    <section class="admin-sections">
        <div class="admin-section">
            {{-- se reutiliza la sección actual de productos en una página propia --}}
            @include('Admin.sections.productos')
        </div>
    </section>
</section>

<div id="Toast" class="admin-toast"></div>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
