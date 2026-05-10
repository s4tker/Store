@extends('layouts.app')

@section('title', 'Productos | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 font-sans selection:bg-blue-100 selection:text-blue-900 pb-12">
    <header class="bg-white border-b border-slate-200 px-6 py-8 md:px-12 md:py-10 mb-8 shadow-sm">
        <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition-colors mb-4 gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al dashboard
                </a>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-1">Catálogo</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight uppercase italic leading-none">Gestión de <span class="text-slate-400">productos</span></h1>
            </div>
            <div class="flex gap-2">
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $Productos->count() }} prod.</span>
                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $Categorias->count() }} cat.</span>
            </div>
        </div>
    </header>

    <div class="max-w-[1600px] mx-auto px-4 md:px-8">
        @include('Admin.sections.productos')
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>
@endsection

@section('scripts')
    @vite(['resources/js/AdminControl.js'])
@endsection
