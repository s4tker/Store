@extends('layouts.app')

@section('title', 'Confirmar compra | ElectroShop')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Confirmar compra</p>
        <h1 class="text-3xl font-black">Resumen del carrito</h1>
        <p class="mt-2 text-slate-600">Revisa tu carrito y selecciona la dirección antes de generar el pedido.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@if(! $direcciones->isEmpty())
        <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-black mb-4">Productos en el carrito</h2>
                <p class="text-slate-600">Los productos se cargarán desde el carrito actual.</p>
            </section>

            <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-black mb-4">Resumen final</h2>

                <form action="{{ route('pedidos.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="DireccionId" class="block text-sm font-semibold text-slate-700">Dirección de entrega</label>
                        <select id="DireccionId" name="DireccionId" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900">
                            <option value="">Selecciona una dirección</option>
                            @foreach($direcciones as $direccion)
                                <option value="{{ $direccion->Id }}">{{ $direccion->Ciudad }}, {{ $direccion->Direccion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Total del pedido</p>
                        <p class="mt-3 text-3xl font-black text-slate-900">Se calculará automáticamente</p>
                    </div>

                    <button type="submit" class="w-full rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                        Confirmar pedido
                    </button>
                </form>
            </aside>
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
            No tienes direcciones registradas. Agrega una dirección desde tu cuenta para continuar.
        </div>
    @endif
</div>
@endsection
