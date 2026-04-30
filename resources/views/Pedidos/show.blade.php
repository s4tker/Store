@extends('layouts.app')

@section('title', 'Detalle de pedido | ElectroShop')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Pedido #{{ $pedido->Id }}</p>
            <h1 class="text-3xl font-black">Detalle del pedido</h1>
            <p class="mt-2 text-slate-600">Consulta el estado, los productos y la dirección de entrega.</p>
        </div>

        @if($pedido->Estado === 'Pendiente')
            <form action="{{ route('pedidos.cancelar', $pedido->Id) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-full bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-600">
                    Cancelar pedido
                </button>
            </form>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-500">Estado</p>
                    <p class="text-xl font-black text-slate-900">{{ $pedido->Estado }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total</p>
                    <p class="text-xl font-black text-slate-900">S/.{{ number_format($pedido->Total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Fecha</p>
                    <p class="text-xl font-black text-slate-900">{{ optional($pedido->CreatedAt)->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm font-semibold text-slate-700">Dirección de entrega</p>
                <p class="mt-2 text-slate-700">{{ $pedido->direccion?->Ciudad ?? 'N/A' }}, {{ $pedido->direccion?->Direccion ?? '-' }}</p>
                <p class="text-slate-600">{{ $pedido->direccion?->Region ?? '' }} {{ $pedido->direccion?->Pais ?? '' }}</p>
            </div>
        </section>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-black mb-4">Productos</h2>
            <div class="space-y-4">
                @foreach($pedido->detalles as $detalle)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <p class="font-semibold text-slate-900">{{ $detalle->variante?->producto?->Nombre ?? 'Variante #' . $detalle->VarianteId }}</p>
                        <p class="text-sm text-slate-600">Cantidad: {{ $detalle->Cantidad }}</p>
                        <p class="text-sm text-slate-600">Precio unitario: S/.{{ number_format($detalle->Precio, 2) }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">Subtotal: S/.{{ number_format($detalle->subtotal, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </aside>
    </div>
</div>
@endsection
