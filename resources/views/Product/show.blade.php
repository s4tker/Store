@extends('layouts.app')

@section('title', $Product->Nombre . ' | ElectroShop')

@section('content')
@php
    $galleryImages = $Product->imagenes->isNotEmpty() ? $Product->imagenes : collect([(object) ['Url' => $Product->image_url]]);
    $mainImage = $galleryImages->first();
    $cartProductPayload = [
        'id' => $Product->Id,
        'productId' => $Product->Id,
        'variantId' => $Variant?->Id,
        'sku' => $Variant?->Sku,
        'slug' => $Product->Slug,
        'name' => $Product->Nombre,
        'price' => $FinalPrice,
        'image' => $Product->image_url,
        'maxQty' => max(1, $Stock),
    ];
@endphp

<div class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
    <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
    <span>/</span>
    <span>{{ $Product->categoria?->padre?->Nombre ?? $Product->categoria?->Nombre ?? 'Catálogo' }}</span>
</div>

<section class="grid gap-5 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
    <div class="space-y-4">
        <div class="rounded-[1.25rem] border border-slate-200 bg-white p-3 sm:p-4 shadow-sm">
            {{-- este cuadro controla el zoom con mouse y toque --}}
            <div
                class="product-image-zoom relative overflow-hidden rounded-[1.25rem] bg-slate-50 cursor-zoom-in"
                data-product-zoom
                aria-label="vista ampliable del producto"
            >
                <img
                    id="MainProductImage"
                    src="{{ str_starts_with($mainImage->Url ?? '', 'http') ? $mainImage->Url : (isset($mainImage->Id) ? asset('storage/' . ltrim($mainImage->Url, '/')) : $Product->image_url) }}"
                    alt="{{ $Product->Nombre }}"
                    class="product-image-zoom-target h-[260px] w-full object-contain sm:h-[340px] lg:h-[460px]"
                    draggable="false"
                >
            </div>
        </div>

        @if($galleryImages->count() > 1)
        <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
            @foreach($galleryImages as $image)
            @php
                $thumbUrl = str_starts_with($image->Url ?? '', 'http') ? $image->Url : (isset($image->Id) ? asset('storage/' . ltrim($image->Url, '/')) : $Product->image_url);
            @endphp
            <button
                type="button"
                class="rounded-[0.9rem] border border-slate-200 bg-white p-1.5 shadow-sm transition-colors hover:border-blue-300"
                onclick="SetMainProductImage('{{ $thumbUrl }}')"
            >
                <img src="{{ $thumbUrl }}" alt="{{ $Product->Nombre }}" class="h-14 w-full object-contain sm:h-16">
            </button>
            @endforeach
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="rounded-[1.25rem] border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.18em] text-blue-600">{{ $Product->marca?->Nombre ?? 'Marca libre' }}</p>
                    <h1 class="mt-2 text-base sm:text-xl lg:text-[1.65rem] font-black tracking-tight text-slate-900">{{ $Product->Nombre }}</h1>
                </div>

                @if($DiscountPercent > 0)
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.12em] text-emerald-700">
                    -{{ $DiscountPercent }}%
                </span>
                @endif
            </div>

            <div class="mt-3 flex flex-wrap items-end gap-2">
                <p class="text-lg sm:text-xl font-black italic text-slate-900">S/.{{ number_format($FinalPrice, 2) }}</p>
                @if($OriginalPrice > $FinalPrice)
                <p class="text-[12px] sm:text-sm font-bold text-slate-400 line-through">S/.{{ number_format($OriginalPrice, 2) }}</p>
                @endif
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-slate-600">
                    stock: {{ $Stock }}
                </span>
                @if($Variant?->Sku)
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-slate-600">
                    sku: {{ $Variant->Sku }}
                </span>
                @endif
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-slate-600">
                    {{ $Product->categoria?->Nombre ?? 'sin categoría' }}
                </span>
            </div>

            @if(!empty($Product->Descripcion))
            <div class="mt-4 rounded-[1rem] bg-slate-50 p-3.5">
                <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.14em] text-slate-400">Descripción</p>
                <p class="mt-2 text-[12px] sm:text-[13px] leading-5 text-slate-600">{{ $Product->Descripcion }}</p>
            </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row gap-2.5">
                <div class="inline-flex items-center justify-between rounded-full border border-slate-200 bg-white px-2 py-1.5 sm:min-w-[130px]">
                    <button type="button" onclick="ChangeProductQty(-1)" class="h-8 w-8 rounded-full bg-slate-100 text-[13px] font-black text-slate-700">-</button>
                    <input id="ProductQty" type="number" min="1" max="{{ max(1, $Stock) }}" value="1" class="product-qty-input w-10 border-none bg-transparent text-center text-[13px] font-black text-slate-900 focus:ring-0">
                    <button type="button" onclick="ChangeProductQty(1)" class="h-8 w-8 rounded-full bg-slate-100 text-[13px] font-black text-slate-700">+</button>
                </div>

                <button
                    type="button"
                    onclick='AddCurrentProductToCart(@json($cartProductPayload))'
                    class="btn-primary-es sm:w-auto px-5"
                >
                    agregar al carrito
                </button>
            </div>
        </div>

        @if($Attributes->isNotEmpty())
        <div x-data="{ expanded: false }" class="rounded-[1.25rem] border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.14em] text-slate-400">Características</p>
                @if($Attributes->count() > 6)
                <button type="button" @click="expanded = !expanded" class="rounded-full border border-slate-200 px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.12em] text-slate-600 transition-colors hover:border-blue-200 hover:text-blue-600" x-text="expanded ? 'ver menos' : 'ver más'"></button>
                @endif
            </div>

            <div class="mt-3 flex flex-wrap gap-2.5">
                @foreach($Attributes as $index => $attribute)
                <div x-show="expanded || {{ $index }} < 6" x-cloak class="max-w-full rounded-[0.95rem] border border-slate-200 bg-slate-50 px-3 py-2.5">
                    <p class="text-[9px] font-black uppercase tracking-[0.12em] text-slate-400 break-words">{{ $attribute->nombre }}</p>
                    <p class="mt-1 text-[12px] sm:text-[13px] font-bold leading-5 text-slate-700 break-words">{{ $attribute->valor }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@if($RelatedProducts->isNotEmpty())
<section class="mt-10">
    <div class="mb-4 flex items-end justify-between gap-3">
        <div>
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.16em] text-slate-400">Más productos</p>
            <h2 class="mt-2 text-sm sm:text-lg font-black text-slate-900">Relacionados con este producto</h2>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
        @foreach($RelatedProducts as $related)
        <a href="{{ route('product.show', $related->Slug) }}" class="product-card p-2.5 sm:p-3 group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all block">
            <div class="aspect-square mb-2.5 bg-slate-50 rounded-[1rem] flex items-center justify-center p-2.5 overflow-hidden">
                <img src="{{ $related->image_url }}" alt="{{ $related->Nombre }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform duration-700">
            </div>
            <p class="text-[8px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">{{ $related->marca?->Nombre ?? 'Marca libre' }}</p>
            <h3 class="text-slate-800 font-bold text-[9px] sm:text-[10px] line-clamp-2 min-h-[1.8rem] group-hover:text-blue-600 transition-colors">{{ $related->Nombre }}</h3>
            <p class="text-[13px] sm:text-sm font-black text-slate-900 mt-1.5 italic">S/.{{ number_format($related->display_price, 2) }}</p>
        </a>
        @endforeach
    </div>
</section>
@endif
@endsection
