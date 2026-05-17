@extends('layouts.admin')

@section('title', 'Detalle de pedido | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
@php
    $pedido = $PedidoAdmin;
    $clienteNombre = trim(collect([
        $pedido->usuario?->Nombre,
        $pedido->usuario?->Apellidos,
    ])->filter()->implode(' ')) ?: ($pedido->usuario?->Alias ?: 'Cliente sin nombre');

    $estadoClasses = [
        'pendiente' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pagado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'entregado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'cancelado' => 'bg-rose-50 text-rose-700 border-rose-200',
        'enviado' => 'bg-blue-50 text-blue-700 border-blue-200',
    ];
    $estadoClass = $estadoClasses[$pedido->estado_normalizado] ?? 'bg-slate-50 text-slate-600 border-slate-200';
@endphp

<div class="admin-page -mx-4 md:-mx-10">
    <div class="admin-shell px-4 py-6 md:px-6 lg:px-8">
        <div class="space-y-5 pb-8">
            <section class="admin-surface p-4 md:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="min-w-0">
                        <a href="{{ route('admin.pedidos.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-slate-700">
                            <svg class="h-4 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 32 16" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 3 3 8l5 5M4 8h25"/></svg>
                            Regresar a pedidos
                        </a>
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <h1 class="admin-title">PED-{{ str_pad((string) $pedido->Id, 5, '0', STR_PAD_LEFT) }}</h1>
                            <span class="rounded-full border px-3 py-1 text-[9px] font-black uppercase tracking-[0.14em] {{ $estadoClass }}">{{ $pedido->estado_texto }}</span>
                        </div>
                        <p class="mt-2 text-sm font-medium text-slate-500">Detalle completo del pedido seleccionado.</p>
                    </div>

                    <div class="rounded-2xl bg-slate-950 px-6 py-4 text-right text-white shadow-lg">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Total del pedido</p>
                        <p class="mt-1 text-2xl font-black">S/. {{ number_format($pedido->Total, 2) }}</p>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_22rem]">
                <section class="admin-panel p-5 md:p-7">
                    <div class="flex items-start gap-4 border-b border-slate-100 pb-6">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-slate-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 4h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 0 1 2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="admin-card-kicker">Cliente</p>
                            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">{{ $clienteNombre }}</h2>
                            <p class="mt-1 text-sm font-medium text-slate-500">{{ $pedido->usuario?->Correo ?? 'Sin correo' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-blue-500">Fecha</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">{{ optional($pedido->CreatedAt)->format('d/m/Y H:i') ?? '--' }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-emerald-600">Telefono</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">{{ $pedido->usuario?->Telefono ?? 'Sin telefono' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400">DNI</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">{{ $pedido->usuario?->Dni ?? 'Sin DNI' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5">
                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-emerald-600">Direccion de envio</p>
                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-700">
                            {{ $pedido->direccion?->Direccion ?? 'Sin direccion' }}
                            <span class="block text-xs text-slate-500">
                                {{ collect([$pedido->direccion?->Ciudad, $pedido->direccion?->Region, $pedido->direccion?->Pais])->filter()->implode(' / ') ?: 'Sin ubicacion' }}
                            </span>
                            @if($pedido->direccion?->Referencia)
                                <span class="block text-xs text-slate-400">{{ $pedido->direccion->Referencia }}</span>
                            @endif
                        </p>
                    </div>
                </section>

                <aside class="admin-panel p-5 md:p-6">
                    <p class="admin-card-kicker">Estado del pedido</p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-950">Actualizar estado</h2>

                    <form action="{{ route('admin.pedidos.estado', $pedido->Id) }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="Estado" class="admin-label">Estado</label>
                            <select name="Estado" id="Estado" class="admin-select" required>
                                @foreach($EstadosPedido as $estado)
                                    <option value="{{ $estado }}" @selected($pedido->estado_normalizado === $estado)>{{ ucfirst($estado) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="admin-button-primary w-full">Guardar estado</button>
                    </form>
                </aside>
            </div>

            <section class="admin-panel p-5 md:p-7">
                <div class="flex flex-col gap-2 border-b border-slate-100 pb-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="admin-card-kicker">Productos</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Items del pedido</h2>
                    </div>
                    <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-600">{{ $pedido->detalles->sum('Cantidad') }} items</span>
                </div>

                <div class="mt-6 space-y-3">
                    @forelse($pedido->detalles as $detalle)
                        @php
                            $producto = $detalle->variante?->producto;
                            $imagen = $producto?->image_url ?? asset('img/logo/logo.png');
                        @endphp
                        <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 p-2">
                                    <img src="{{ $imagen }}" alt="{{ $producto?->Nombre ?? 'Producto' }}" class="max-h-full max-w-full object-contain">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-sm font-black text-slate-950">{{ $producto?->Nombre ?? 'Producto #' . $detalle->VarianteId }}</h3>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">SKU {{ $detalle->variante?->Sku ?? 'sin sku' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-slate-900">S/. {{ number_format($detalle->subtotal, 2) }}</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-500">{{ $detalle->Cantidad }} x S/. {{ number_format($detalle->Precio, 2) }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="admin-empty">Este pedido no tiene productos registrados.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
