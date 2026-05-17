@extends('layouts.admin')

@section('title', 'Productos | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div class="admin-page -mx-4 md:-mx-10">
    <div class="admin-shell px-4 py-6 md:px-6 lg:px-8">
        <div class="space-y-5 pb-8">
            <section class="admin-surface p-4 md:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="min-w-0">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-slate-700">
                            <svg class="h-4 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 32 16" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 3 3 8l5 5M4 8h25"/></svg>
                            Panel admin
                        </a>
                        <h1 class="admin-title mt-3">Productos</h1>
                    </div>

                    <div class="grid w-full max-w-2xl gap-3 sm:grid-cols-3">
                        <x-admin.stat-card label="Productos" :value="$Productos->count()" tone="blue">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>

                        <x-admin.stat-card label="Categorías" :value="$Categorias->count()" tone="violet">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A1.75 1.75 0 0 1 5.75 5h4.5A1.75 1.75 0 0 1 12 6.75v4.5A1.75 1.75 0 0 1 10.25 13h-4.5A1.75 1.75 0 0 1 4 11.25z"/>
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

            @include('Admin.sections.productos')
        </div>
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
