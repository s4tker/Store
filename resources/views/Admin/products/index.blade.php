@extends('layouts.app')

@section('title', 'Productos | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<section class="admin-dashboard-shell">
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
