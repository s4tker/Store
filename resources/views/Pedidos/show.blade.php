@extends('layouts.app')

@section('title', 'Detalle de pedido | ElectroShop')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12 space-y-12">
    
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-8 border-b border-slate-100 pb-10">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-slate-900 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-md">Order Log</span>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID: #{{ $pedido->Id }}</p>
            </div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase italic">Detalle <span class="text-slate-300">Pedido</span></h1>
        </div>

        @if($pedido->estado_normalizado === 'pendiente')
            <form action="{{ route('pedidos.cancelar', $pedido->Id) }}" method="POST" onsubmit="return confirm('¿Confirmar cancelación?')">
                @csrf
                <button type="submit" class="group flex items-center gap-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    Anular Transacción
                </button>
            </form>
        @endif
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <div class="lg:col-span-7 space-y-10">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Estado Actual</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        <p class="text-sm font-black text-slate-900 uppercase italic">{{ $pedido->estado_texto }}</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Importe Total</p>
                    <p class="text-xl font-black text-slate-900 leading-none">S/{{ number_format($pedido->Total, 2) }}</p>
                </div>
                <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Fecha Registro</p>
                    <p class="text-sm font-black text-slate-900 uppercase leading-none">{{ optional($pedido->CreatedAt)->format('d.m.y / H:i') }}</p>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <div class="relative z-10 space-y-6">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-400">Shipping Details</h2>
                    <div class="space-y-2">
                        <p class="text-3xl font-black tracking-tighter uppercase italic leading-tight">
                            {{ $pedido->direccion?->Ciudad ?? 'Desconocido' }}
                        </p>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs leading-relaxed max-w-md">
                            {{ $pedido->direccion?->Direccion ?? '-' }}<br>
                            {{ $pedido->direccion?->Region ?? '' }}, {{ $pedido->direccion?->Pais ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white border border-slate-100 rounded-[3rem] p-8 md:p-10 shadow-xl shadow-slate-200/50">
                <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-10 border-b border-slate-50 pb-6 text-center">Manifest / Items</h2>
                
                <div class="space-y-8">
                    @foreach($pedido->detalles as $detalle)
                        <div class="group flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h3 class="font-black text-slate-900 uppercase text-xs tracking-tight leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ $detalle->variante?->producto?->Nombre ?? 'Item #' . $detalle->VarianteId }}
                                </h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">
                                    CANT. {{ $pedido->Cantidad }} <span class="mx-2">/</span> P.U: S/{{ number_format($detalle->Precio, 2) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-slate-900 text-sm tracking-tighter">
                                    S/{{ number_format($detalle->subtotal, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 pt-8 border-t-2 border-dashed border-slate-100 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subtotal</span>
                        <span class="text-xs font-bold text-slate-600 italic">S/{{ number_format($pedido->Total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Envío</span>
                        <span class="text-xs font-bold text-emerald-500 italic uppercase">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                        <span class="text-[11px] font-black text-slate-900 uppercase tracking-[0.3em]">Total Facturado</span>
                        <span class="text-2xl font-black text-slate-900 tracking-tighter">S/{{ number_format($pedido->Total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-10 bg-slate-50 rounded-2xl p-4 flex items-center justify-center gap-3">
                    <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Garantía ElectroShop Secure</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection