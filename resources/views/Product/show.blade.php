<<<<<<< HEAD
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $Product->Nombre }} | ElectroShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50">

    <nav class="bg-[#0f172a] sticky top-0 z-[60] px-3 md:px-8 py-3 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-2 md:gap-8">
            <button type="button" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-white" onclick="ToggleMobileCatalog(true)" aria-label="Abrir menú">
                <span class="space-y-1.5">
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                </span>
            </button>

            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-8 h-8 md:w-10 md:h-10 rounded-full" alt="ElectroShop">
            </a>

            @auth
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('administrador'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-[#0f172a] px-3 py-1.5 rounded-lg transition-all active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4z"></path></svg>
                        <span class="text-[11px] font-black uppercase hidden sm:block text-slate-900">Panel Admin</span>
                        <span class="text-[10px] font-black uppercase sm:hidden text-slate-900">Admin</span>
                    </a>
                @endif
            @endauth

            <div class="flex-1 min-w-0 flex bg-slate-800/50 rounded-xl px-2 md:px-4 py-1.5 items-center border border-slate-700 focus-within:border-blue-500 transition-all">
                <input id="q" type="text" placeholder="Buscar productos..." class="flex-1 bg-transparent outline-none text-[13px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0 border-none focus:ring-0">
                <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-bold text-xs px-2">Buscar</button>
            </div>

            <div class="flex items-center gap-2 md:gap-6 shrink-0">
                @auth
                    <a href="{{ route('account') }}" class="flex flex-col items-end">
                        <span class="hidden md:block text-[10px] text-slate-500 font-bold uppercase tracking-widest">Mi Cuenta</span>
                        <span class="text-[12px] md:text-sm font-bold text-slate-200 truncate max-w-[60px] md:max-w-none">{{ Auth::user()->Nombre }}</span>
                    </a>
                @else
                    <button type="button" onclick="openAuthModal()" class="flex flex-col items-end">
                        <span class="text-[12px] md:text-sm font-bold text-slate-200">Ingresar</span>
                    </button>
                @endauth

                <button type="button" onclick="ToggleCart(true)" class="relative bg-blue-600 text-white p-2 md:p-2.5 rounded-lg md:rounded-xl shadow-lg active:scale-90 transition-transform">
                    <span id="CartCount" class="absolute -top-2 -right-2 hidden min-w-5 h-5 px-1 rounded-full bg-amber-400 text-[#0f172a] text-[10px] font-black items-center justify-center">0</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4 md:p-10">
=======
@extends('layouts.app')

@section('title', '{{ $Product->Nombre }} | ElectroShop')

@section('content')
>>>>>>> 9e8ad49 (Feat: soporte de DNI/RUC en usuarios y actualización de vistas)
        <div class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
            <span>/</span>
            <span>{{ $Product->categoria?->padre?->Nombre ?? $Product->categoria?->Nombre ?? 'Catálogo' }}</span>
            @if($Product->categoria?->padre)
                <span>/</span>
                <span>{{ $Product->categoria->Nombre }}</span>
            @endif
        </div>

        <section class="grid gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(320px,0.95fr)]">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-sm md:p-6">
                <button type="button" class="block w-full rounded-[1.75rem] bg-slate-50 p-4 md:p-5" onclick="OpenImageZoom()">
                    <img id="MainProductImage" src="{{ $Product->image_url }}" alt="{{ $Product->Nombre }}" class="h-[250px] w-full object-contain md:h-[400px]">
                    <span class="mt-3 inline-flex rounded-full border border-slate-200 bg-white px-3 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Toca para ampliar</span>
                </button>

                <div class="mt-4 grid grid-cols-4 gap-2 md:grid-cols-5">
                    @forelse($Product->imagenes as $image)
                        <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 p-2 transition-colors hover:border-blue-400" onclick="SetMainProductImage('{{ str_starts_with($image->Url, 'http') ? $image->Url : asset('storage/' . ltrim($image->Url, '/')) }}')">
                            <img src="{{ str_starts_with($image->Url, 'http') ? $image->Url : asset('storage/' . ltrim($image->Url, '/')) }}" alt="{{ $Product->Nombre }}" class="h-14 w-full object-contain">
                        </button>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm font-bold text-slate-400">No hay más imágenes</div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-5">
                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm md:p-7">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-blue-50 px-3 py-2 text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">{{ $Product->marca?->Nombre ?? 'Marca libre' }}</span>
                        @if($DiscountPercent > 0)
                            <span class="rounded-full bg-amber-100 px-3 py-2 text-[10px] font-black uppercase tracking-[0.16em] text-amber-800">-{{ $DiscountPercent }}%</span>
                        @endif
                        <span class="rounded-full {{ $Stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }} px-3 py-2 text-[10px] font-black uppercase tracking-[0.16em]">
                            {{ $Stock > 0 ? 'Stock disponible' : 'Sin stock' }}
                        </span>
                    </div>

                    <h1 class="mt-4 text-xl font-black tracking-tight text-slate-900 md:text-3xl">{{ $Product->Nombre }}</h1>

                    <div class="mt-6 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4">
                        @if($DiscountPercent > 0)
                            <p class="text-xs font-bold text-slate-400 line-through">Precio original: S/.{{ number_format($OriginalPrice, 2) }}</p>
                        @endif
                        <div class="mt-2 flex flex-wrap items-end gap-3">
                            <p class="text-xl font-black italic text-slate-900 md:text-2xl">S/.{{ number_format($FinalPrice, 2) }}</p>
                            <p class="pb-1 text-[10px] font-black uppercase tracking-[0.16em] text-blue-600">Precio oficial</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-[auto_minmax(0,1fr)] md:items-end">
                        <div>
                            <p class="mb-2 text-[10px] font-black uppercase tracking-[0.16em] text-slate-500">Cantidad</p>
                            <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-1">
                                <button type="button" class="h-10 w-10 rounded-xl text-lg font-black text-slate-600 transition-colors hover:bg-slate-100" onclick="ChangeProductQty(-1)">-</button>
                                <input id="ProductQty" type="number" min="1" max="{{ max($Stock, 1) }}" value="1" class="w-12 border-none bg-transparent text-center text-sm font-black text-slate-900 outline-none focus:ring-0">
                                <button type="button" class="h-10 w-10 rounded-xl text-lg font-black text-slate-600 transition-colors hover:bg-slate-100" onclick="ChangeProductQty(1)">+</button>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-4 text-[10px] font-black uppercase tracking-[0.18em] text-white transition-colors hover:bg-blue-600 {{ $Stock < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                onclick="AddCurrentProductToCart({
                                    id: {{ $Product->Id }},
                                    name: @js($Product->Nombre),
                                    price: {{ $FinalPrice }},
                                    image: @js($Product->image_url),
                                    maxQty: {{ max($Stock, 1) }}
                                })"
                                {{ $Stock < 1 ? 'disabled' : '' }}
                            >
                                Agregar al carrito
                            </button>

                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-4 text-[10px] font-black uppercase tracking-[0.18em] text-slate-700 transition-colors hover:border-blue-200 hover:text-blue-700"
                                onclick="ToggleCompareProduct({
                                    id: {{ $Product->Id }},
                                    name: @js($Product->Nombre),
                                    price: {{ $FinalPrice }},
                                    image: @js($Product->image_url),
                                    category: @js($Product->categoria?->Nombre ?? '')
                                })"
                            >
                                Comparar
                            </button>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm md:p-7">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Descripción</p>
                        <h2 class="mt-2 text-lg font-black text-slate-900 md:text-xl">Conoce mejor este producto</h2>
                    </div>

                    <div class="mt-4 rounded-[1.5rem] bg-slate-50 px-4 py-4">
                        <p class="text-[12px] leading-6 text-slate-600 md:text-[13px] md:leading-7">{{ $Product->Descripcion ?: 'Producto sin descripción detallada.' }}</p>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm md:p-7">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Detalles técnicos</p>
                            <h2 class="mt-2 text-lg font-black text-slate-900 md:text-xl">Especificaciones y estado</h2>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3">
                        @forelse($Attributes as $attribute)
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-500">{{ $attribute->nombre }}</span>
                                <span class="text-[13px] font-bold text-slate-900 md:text-sm">{{ $attribute->valor }}</span>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-5 text-sm font-bold text-slate-400">Este producto todavía no tiene atributos configurados.</div>
                        @endforelse

                    </div>
                </section>
            </div>
        </section>

        <section class="mt-10">
            <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Sigue explorando</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-900">Productos relacionados</h2>
                </div>
                <a href="{{ route('home', ['category' => $Product->categoria?->padre?->Id ?: $Product->categoria?->Id]) }}" class="text-sm font-black text-blue-600 transition-colors hover:text-blue-700">Ver misma categoría</a>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-5">
                @forelse($RelatedProducts as $related)
                    <a href="{{ route('product.show', $related->Slug) }}" class="product-card p-3 md:p-5 group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all block">
                        <div class="aspect-square mb-3 bg-slate-50 rounded-[1.2rem] flex items-center justify-center p-4 overflow-hidden">
                            <img src="{{ $related->image_url }}" alt="{{ $related->Nombre }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <p class="text-[7px] md:text-[8px] font-black text-blue-600 uppercase tracking-[0.18em] mb-1">{{ $related->marca?->Nombre ?? 'Marca libre' }}</p>
                        <h3 class="text-slate-800 font-bold text-[11px] md:text-[13px] line-clamp-2 min-h-[2.2rem] group-hover:text-blue-600 transition-colors">{{ $related->Nombre }}</h3>
                        <p class="text-base md:text-lg font-black text-slate-900 mt-2 italic">S/.{{ number_format($related->display_price, 2) }}</p>
                    </a>
                @empty
                    <div class="col-span-full rounded-[2rem] border border-dashed border-slate-200 px-6 py-10 text-center text-sm font-bold text-slate-400">No hay productos relacionados por ahora.</div>
                @endforelse
            </div>
        </section>
    </main>

    <div id="MobileCatalogOverlay" class="fixed inset-0 z-[95] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleMobileCatalog(false)"></div>
    <aside id="MobileCatalogDrawer" class="fixed left-0 top-0 z-[100] h-full w-full max-w-sm -translate-x-full bg-white shadow-2xl transition-transform duration-300">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between border-b px-5 py-5">
                <h2 class="text-lg font-black uppercase italic text-slate-900">Explorar</h2>
                <button type="button" onclick="ToggleMobileCatalog(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
            </div>

            <div class="space-y-5 overflow-y-auto px-5 py-5">
                <div>
                    <p class="mb-3 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Categorías</p>
                    <div class="space-y-2">
                        @foreach($Categorias as $categoria)
                            <a href="{{ route('home', ['category' => $categoria->Id]) }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800">
                                <span>{{ $categoria->Nombre }}</span>
                                <span class="text-xs text-slate-400">{{ $categoria->subcategorias->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Atajos</p>
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Inicio</a>
                        @auth
                            <a href="{{ route('account') }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700">Mi cuenta</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div id="CartOverlay" class="fixed inset-0 z-[85] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleCart(false)"></div>
    <aside id="CartDrawer" class="fixed right-0 top-0 z-[90] h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300">
        <div class="h-full flex flex-col">
            <div class="px-6 py-5 border-b flex items-center justify-between">
                <h2 class="text-xl font-black italic uppercase text-slate-900">Tu Carrito</h2>
                <button onclick="ToggleCart(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
            </div>
            <div id="CartItems" class="flex-1 overflow-y-auto px-6 py-6 space-y-4"></div>
            <div class="p-6 border-t bg-slate-50">
                <div class="flex items-center justify-between font-black uppercase text-xs mb-4 text-slate-600">
                    <span>Total estimado</span>
                    <span id="CartTotal" class="text-lg italic text-blue-600">S/.0.00</span>
                </div>
                <button class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl opacity-60 cursor-not-allowed uppercase text-[11px] tracking-widest">Próximamente Checkout</button>
            </div>
        </div>
    </aside>

    <div id="ImageZoomModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-[#0f172a]/90 p-4 backdrop-blur-sm" onclick="CloseImageZoom()">
        <button type="button" class="absolute right-5 top-5 text-4xl font-light leading-none text-white" onclick="CloseImageZoom()">&times;</button>
        <img id="ZoomedProductImage" src="{{ $Product->image_url }}" alt="{{ $Product->Nombre }}" class="max-h-[88vh] w-auto max-w-[92vw] object-contain" onclick="event.stopPropagation()">
    </div>

    <div id="AuthModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 bg-[#0f172a]/90 backdrop-blur-xl transition-all" onclick="closeAuthModal()">
        <div id="ModalContainer" class="bg-white w-full max-w-[95%] md:max-w-4xl rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl overflow-hidden translate-y-full md:translate-y-0 md:scale-95 opacity-0 flex flex-col md:flex-row min-h-[450px] transition-all duration-500" onclick="event.stopPropagation()">
            <div class="hidden md:flex flex-1 bg-slate-50 items-center justify-center border-r">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-40 h-40 shadow-2xl rounded-full">
            </div>
            <div class="flex-1 p-8 md:p-16 flex flex-col justify-center">
                <h2 class="text-2xl font-black italic uppercase mb-2">Electro<span class="text-blue-600">Shop</span></h2>
                <p id="AuthSubtitle" class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-8">Ingresa tu correo para continuar</p>
                <div class="space-y-4">
                    <input id="AuthEmail" type="email" placeholder="Correo electrónico" class="auth-input w-full p-4 bg-slate-100 rounded-2xl border-none font-bold">
                    <div id="PassWrapper" class="hidden relative">
                        <input id="AuthPass" type="password" placeholder="Escribe tu contraseña" class="auth-input w-full p-4 bg-slate-100 rounded-2xl border-none font-bold pr-12">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="eyePath" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                    <button id="AuthBtn" onclick="handleAuthStep()" class="btn-primary-es w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[11px] tracking-widest hover:bg-blue-600 transition-all">Continuar</button>
                    <div id="AuthAlert" class="hidden p-3 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded-xl text-center"></div>
                </div>
                <button onclick="closeAuthModal()" class="mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Cerrar</button>
            </div>
        </div>
    </div>
<<<<<<< HEAD
</body>
</html>
=======
@endsection
>>>>>>> 9e8ad49 (Feat: soporte de DNI/RUC en usuarios y actualización de vistas)
