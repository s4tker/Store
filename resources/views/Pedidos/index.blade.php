@extends('layouts.app')

@section('title', 'Mis pedidos | ElectroShop')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <!-- Header de la Sección -->
    <header class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-2">Historial</p>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight uppercase italic leading-none">
                Mis <span class="text-slate-400">Pedidos</span>
            </h1>
            <p class="mt-4 text-slate-500 font-medium max-w-md">
                Gestiona tus compras recientes, descarga facturas y sigue el estado de tus envíos.
            </p>
        </div>
    </header>

    <!-- Notificaciones -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="bg-emerald-50 border-l-4 border-emerald-500 p-5 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="bg-emerald-500 text-white p-1 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3"/></svg>
                </span>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 text-xl font-bold">&times;</button>
        </div>
    @endif

    <!-- Contenido Principal -->
    @if($pedidos->isEmpty())
        <div class="relative overflow-hidden bg-white border-2 border-dashed border-slate-200 rounded-[3rem] p-16 text-center">
            <div class="relative z-10">
                <div class="bg-slate-50 w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="1.5"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase">Sin pedidos aún</h3>
                <p class="text-slate-500 mt-2 font-medium">Parece que todavía no has realizado ninguna compra.</p>
                <a href="{{ route('home') }}" class="mt-8 inline-block text-blue-600 font-black text-xs uppercase tracking-widest hover:underline">Explorar productos &rarr;</a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            <!-- Header de tabla "Invisible" en móvil, visible en Desktop -->
            <div class="hidden md:grid grid-cols-5 px-8 mb-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                <div class="col-span-1">Referencia</div>
                <div>Fecha</div>
                <div>Estado</div>
                <div>Total</div>
                <div class="text-right">Acciones</div>
            </div>

            <!-- Lista de Pedidos -->
            @foreach($pedidos as $pedido)
                <div class="group bg-white border border-slate-100 rounded-3xl p-6 md:px-8 md:py-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60 hover:-translate-y-1 flex flex-col md:grid md:grid-cols-5 items-center gap-4">
                    
                    <!-- ID / Referencia -->
                    <div class="col-span-1 flex items-center gap-4 w-full md:w-auto">
                        <div class="bg-slate-900 text-white w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xs shrink-0 group-hover:bg-blue-600 transition-colors">
                            #{{ $pedido->Id }}
                        </div>
                        <div class="md:hidden">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Referencia</p>
                            <p class="font-bold text-slate-900 text-lg">Pedido #{{ $pedido->Id }}</p>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="w-full md:w-auto">
                        <p class="md:hidden text-[10px] font-black uppercase tracking-widest text-slate-400">Fecha</p>
                        <p class="font-bold text-slate-700 italic capitalize">
                            {{ optional($pedido->CreatedAt)->translatedFormat('d M, Y') ?? '--' }}
                        </p>
                    </div>

                    <!-- Estado con Badge Dinámico -->
                    <div class="w-full md:w-auto">
                        @php
                            $statusClasses = [
                                'pendiente' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'pagado' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'entregado' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'cancelado' => 'bg-red-100 text-red-700 border-red-200',
                                'enviado' => 'bg-blue-100 text-blue-700 border-blue-200',
                            ];
                            $estado = $pedido->estado_normalizado;
                            $class = $statusClasses[$estado] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="px-4 py-1.5 rounded-full border {{ $class }} text-[10px] font-black uppercase tracking-tighter">
                            {{ $pedido->estado_texto }}
                        </span>
                    </div>

                    <!-- Total -->
                    <div class="w-full md:w-auto">
                        <p class="md:hidden text-[10px] font-black uppercase tracking-widest text-slate-400">Total</p>
                        <p class="text-xl font-black text-slate-900">
                            <span class="text-[10px] text-slate-400 font-normal">S/.</span>{{ number_format($pedido->Total, 2) }}
                        </p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex items-center justify-end gap-2 w-full md:w-auto">
                        <a href="{{ route('pedidos.show', $pedido->Id) }}" 
                           class="flex-1 md:flex-none text-center bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Detalles
                        </a>

                        @if($pedido->estado_normalizado === 'pendiente')
                            <form action="{{ route('pedidos.cancelar', $pedido->Id) }}" method="POST" 
                                  onsubmit="return confirm('¿Seguro que deseas cancelar este pedido?')"
                                  class="flex-1 md:flex-none">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-red-50 hover:bg-red-500 hover:text-white text-red-500 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all border border-red-100">
                                    Cancelar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación si fuera necesaria -->
        <div class="mt-8">
            {{-- $pedidos->links() --}}
        </div>
    @endif
</div>
@endsection
