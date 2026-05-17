@extends('layouts.admin')

@section('title', 'Gestion de pedidos | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
@php
    $totalFacturado = $PedidosAdmin->sum(fn ($pedido) => (float) $pedido->Total);
    $pedidosPendientes = $PedidosAdmin->filter(fn ($pedido) => $pedido->estado_normalizado === 'pendiente')->count();
    $itemsVendidos = $PedidosAdmin->sum(fn ($pedido) => $pedido->detalles->sum('Cantidad'));
@endphp

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
                        <h1 class="admin-title mt-3">Gestion de pedidos</h1>
                        <p class="mt-2 text-sm font-medium text-slate-500">Pedidos ordenados del mas antiguo al mas nuevo.</p>
                    </div>

                    <div class="grid w-full max-w-3xl gap-3 sm:grid-cols-3">
                        <x-admin.stat-card label="Pedidos" :value="$PedidosAdmin->count()" tone="amber">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 4h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 0 1 2-2z"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>

                        <x-admin.stat-card label="Pendientes" :value="$pedidosPendientes" tone="slate">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>

                        <x-admin.stat-card label="Items" :value="$itemsVendidos" tone="blue">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>
                    </div>
                </div>
            </section>

            <section class="admin-panel p-5 md:p-7">
                <form action="{{ route('admin.pedidos.index') }}" method="GET" class="mb-6 rounded-[1.5rem] border border-slate-200 bg-slate-50/70 p-4">
                    <div class="grid gap-4 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,0.9fr)_minmax(0,0.8fr)_minmax(0,0.8fr)_auto_auto] xl:items-end">
                        <div>
                            <label for="OrderStatusFilter" class="admin-label">Filtrar por estado</label>
                            <select id="OrderStatusFilter" name="estado" class="admin-select">
                                @foreach($EstadosPedido as $estado)
                                    <option value="{{ $estado }}" @selected($FiltrosPedidos['estado'] === $estado)>{{ $estado === 'todos' ? 'Todos los estados' : ucfirst($estado) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="OrderPeriodFilter" class="admin-label">Filtrar por tiempo</label>
                            <select id="OrderPeriodFilter" name="periodo" class="admin-select">
                                @foreach($PeriodosPedido as $periodo)
                                    <option value="{{ $periodo }}" @selected($FiltrosPedidos['periodo'] === $periodo)>
                                        @if($periodo === 'todos')
                                            Todo el historial
                                        @elseif($periodo === 'hora')
                                            Última hora
                                        @elseif($periodo === 'dia')
                                            Hoy
                                        @elseif($periodo === 'ayer')
                                            Ayer
                                        @elseif($periodo === 'semana')
                                            Esta semana
                                        @elseif($periodo === 'mes')
                                            Este mes
                                        @elseif($periodo === 'anio')
                                            Este año
                                        @else
                                            Personalizado
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="OrderDateFrom" class="admin-label">Desde</label>
                            <input id="OrderDateFrom" type="datetime-local" name="desde" value="{{ $FiltrosPedidos['desde_input'] }}" class="admin-input">
                        </div>

                        <div>
                            <label for="OrderDateTo" class="admin-label">Hasta</label>
                            <input id="OrderDateTo" type="datetime-local" name="hasta" value="{{ $FiltrosPedidos['hasta_input'] }}" class="admin-input">
                        </div>

                        <button type="submit" class="admin-button-primary h-[46px] px-6">Filtrar</button>

                        <a href="{{ route('admin.pedidos.export', request()->only(['estado', 'periodo', 'desde', 'hasta'])) }}" class="admin-button h-[46px] px-6">
                            Exportar Excel
                        </a>
                    </div>

                    <p class="mt-3 text-xs font-semibold text-slate-400">
                        Para usar fechas u horas exactas, selecciona Personalizado y completa Desde / Hasta.
                    </p>
                </form>

                <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="admin-card-kicker">Orden cronologico</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Pedidos realizados</h2>
                    </div>

                    <div class="rounded-2xl bg-slate-50 px-5 py-3 text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Total registrado</p>
                        <p class="mt-1 text-xl font-black text-slate-900">S/. {{ number_format($totalFacturado, 2) }}</p>
                    </div>
                </div>

                <div class="mt-7 space-y-4">
                    @forelse($PedidosAdmin as $pedido)
                        @php
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

                        <article class="admin-list-card overflow-hidden">
                            <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)_auto] xl:items-center">
                                <div class="flex min-w-0 items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-slate-900 shadow-sm">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 4h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 0 1 2-2z"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-950">PED-{{ str_pad((string) $pedido->Id, 5, '0', STR_PAD_LEFT) }}</h3>
                                            <span class="rounded-full border px-3 py-1 text-[9px] font-black uppercase tracking-[0.14em] {{ $estadoClass }}">{{ $pedido->estado_texto }}</span>
                                        </div>
                                        <p class="mt-2 truncate text-sm font-semibold text-slate-700">{{ $clienteNombre }}</p>
                                        <p class="mt-1 truncate text-xs font-medium text-slate-400">{{ $pedido->usuario?->Correo ?? 'Sin correo' }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400">Fecha</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900">{{ optional($pedido->CreatedAt)->format('d/m/Y H:i') ?? '--' }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400">Envio</p>
                                        <p class="mt-1 truncate text-sm font-bold text-slate-900">{{ $pedido->direccion?->Ciudad ?? 'Sin ciudad' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-950 p-4 text-right text-white xl:min-w-[11rem]">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-slate-400">Total</p>
                                        <p class="mt-1 text-xl font-black">S/. {{ number_format($pedido->Total, 2) }}</p>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400">{{ $pedido->detalles->sum('Cantidad') }} items</p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col justify-end gap-2 border-t border-slate-100 pt-5 sm:flex-row">
                                <a
                                    href="{{ route('admin.pedidos.export-one', $pedido->Id) }}"
                                    class="admin-button px-5 py-2.5 text-[11px] uppercase tracking-[0.16em]"
                                >
                                    Exportar
                                </a>
                                <a
                                    href="{{ route('admin.pedidos.show', $pedido->Id) }}"
                                    class="admin-button-primary px-5 py-2.5 text-[11px] uppercase tracking-[0.16em]"
                                >
                                    Detalles
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="admin-empty">Todavia no hay pedidos realizados.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
