@extends('layouts.app')

@section('title', $Product->Nombre . ' | ElectroShop')

@section('content')

<div class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
    <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
    <span>/</span>
    <span>{{ $Product->categoria?->padre?->Nombre ?? $Product->categoria?->Nombre ?? 'Catálogo' }}</span>
</div>

<section class="grid gap-5 lg:grid-cols-2">
    <div class="bg-white p-4 rounded-xl shadow">
        <img src="{{ $Product->image_url }}" class="w-full h-[300px] object-contain">
    </div>

    <div>
        <h1 class="text-2xl font-black">{{ $Product->Nombre }}</h1>
        <p class="text-lg font-bold mt-2">S/.{{ number_format($FinalPrice, 2) }}</p>
    </div>
</section>

@endsection