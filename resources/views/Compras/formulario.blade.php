@extends('layouts.app')

@section('title', 'Finalizar Compra - ElectroShop')

@section('styles')
@vite('resources/css/compra.css')
@endsection

@section('content')
@php
$tieneDirecciones = $DireccionesCompra->isNotEmpty();
$direccionSeleccionadaId = old('DireccionId', $DireccionesCompra->first()?->Id);
$compraBootstrap = [
'Usuario' => $UsuarioCompra,
'Direcciones' => $DireccionesCompra,
'BuscarDniUrl' => url('/clientes/dni'),
'TieneDirecciones' => $tieneDirecciones,
'DireccionSeleccionadaId' => $direccionSeleccionadaId,
'Form' => [
'Documento' => old('Documento', $UsuarioCompra?->Dni ?? ''),
'Telefono' => old('Telefono', $UsuarioCompra?->Telefono ?? ''),
'Nombre' => old('Nombre', $UsuarioCompra?->Nombre ?? ''),
'Apellidos' => old('Apellidos', $UsuarioCompra?->Apellidos ?? ''),
'Correo' => old('Correo', $UsuarioCompra?->Correo ?? ''),
'Region' => old('Region', ''),
'Ciudad' => old('Ciudad', ''),
'Direccion' => old('Direccion', ''),
'Referencia' => old('Referencia', ''),
],
'MetodoPago' => old('MetodoPago', 'Tarjeta'),
'CardImages' => [
asset('img/cards/visa.png'),
asset('img/cards/mastercard.png'),
asset('img/cards/amex.png'),
],
];
@endphp

<div id="CompraBootstrap" hidden data-bootstrap='@json($compraBootstrap, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'></div>

<div class="max-w-7xl mx-auto px-4 py-12" x-data="compraFlow()">
    <header class="mb-12 relative">
        <div class="mb-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-600 transition-colors text-sm font-bold uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Regresar al Inicio
            </a>
        </div>
        <div class="absolute -left-4 top-0 w-1 h-full bg-blue-600 rounded-full"></div>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight uppercase italic leading-none">
            Checkout <span class="text-blue-600 not-italic">Seguro</span>
        </h1>
        <p class="text-[11px] md:text-xs text-slate-400 font-black uppercase tracking-[0.3em] mt-4 flex items-center gap-2">
            <span class="w-8 h-[2px] bg-slate-200"></span>
            Finaliza tu pedido industrial
        </p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <div class="lg:col-span-8 space-y-8">
            <form id="CompraForm" method="POST" action="{{ route('pedidos.store') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="carrito" id="CompraCarrito">
                <input type="hidden" name="Pais" value="Peru">

                @if($errors->any())
                <div class="rounded-3xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <section class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black italic">01</span>
                        <h2 class="text-xl font-black text-slate-900 uppercase italic">Identificacion</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">DNI</label>
                            <input id="CompraDocumento" type="text" name="Documento" x-model="form.Documento" x-on:blur="buscarCliente" inputmode="numeric" maxlength="8" required placeholder="DNI de 8 digitos" class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Telefono</label>
                            <input id="CompraTelefono" type="tel" name="Telefono" x-model="form.Telefono" inputmode="numeric" maxlength="9" required placeholder="999 999 999" class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombres</label>
                            <input id="CompraNombre" type="text" name="Nombre" x-model="form.Nombre" required class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Apellidos</label>
                            <input id="CompraApellidos" type="text" name="Apellidos" x-model="form.Apellidos" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo electronico</label>
                            <input id="CompraCorreo" type="email" name="Correo" x-model="form.Correo" required class="industrial-input">
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black italic">02</span>
                        <h2 class="text-xl font-black text-slate-900 uppercase italic">Datos de Envio</h2>
                    </div>

                    @if($tieneDirecciones)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($DireccionesCompra as $direccion)
                        <label
                            class="address-card p-5 border-2 rounded-[1.8rem] text-left transition-all cursor-pointer"
                            :class="Number(dirId) === {{ $direccion->Id }} ? 'is-active border-blue-500 bg-blue-50' : 'border-slate-100 hover:border-slate-200'">
                            <input
                                type="radio"
                                name="DireccionId"
                                value="{{ $direccion->Id }}"
                                x-model="dirId"
                                class="sr-only"
                                required
                                @checked((int) $direccionSeleccionadaId===(int) $direccion->Id)>
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">{{ $direccion->Ciudad }}</p>
                                    <p class="text-sm font-black text-slate-800">{{ $direccion->Direccion }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $direccion->Region }} · {{ $direccion->Pais }}</p>
                                    @if($direccion->Referencia)
                                    <p class="mt-3 text-xs font-semibold text-slate-500">{{ $direccion->Referencia }}</p>
                                    @endif
                                </div>
                                <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2"
                                    :class="Number(dirId) === {{ $direccion->Id }} ? 'border-blue-500' : 'border-slate-200'">
                                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500" x-show="Number(dirId) === {{ $direccion->Id }}"></span>
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('account') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-white transition-colors hover:bg-blue-600">
                            Cambiar direccion
                        </a>
                    </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Departamento / Region</label>
                            <input id="CompraRegion" type="text" name="Region" x-model="form.Region" required class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ciudad / Distrito</label>
                            <input id="CompraCiudad" type="text" name="Ciudad" x-model="form.Ciudad" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Direccion exacta</label>
                            <input id="CompraDireccion" type="text" name="Direccion" x-model="form.Direccion" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Notas de entrega / referencias</label>
                            <textarea id="CompraReferencia" name="Referencia" x-model="form.Referencia" rows="3" class="industrial-input resize-none" placeholder="Ej. Casa de fachada azul, frente al parque..."></textarea>
                        </div>
                    </div>
                    @endif
                </section>

                <section id="PaymentModule" class="mt-10 mb-16 bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16 pointer-events-none"></div>

                    <div class="flex items-center gap-4 mb-10 relative">
                        <span class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black italic shadow-lg shadow-slate-900/20">03</span>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tight">Método de Pago</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Transacción encriptada grado industrial</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 relative">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Tarjeta" class="peer sr-only" x-model="metodoPago" data-payment-method="Tarjeta" @checked(old('MetodoPago', 'Tarjeta' )==='Tarjeta' )>
                            <div class="min-h-[110px] p-5 border-2 border-slate-100 rounded-[2rem] bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md transition-all flex flex-col justify-between group-hover:border-slate-200">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-slate-900">Tarjeta</span>
                                    <div class="w-2 h-2 rounded-full bg-slate-200 peer-checked:bg-blue-600 transition-colors"></div>
                                </div>
                                <div>
                                    <span class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-tight">Visa, Mastercard</span>
                                    <div class="mt-1 flex gap-1">
                                        <div class="w-6 h-1 bg-slate-200 rounded-full"></div>
                                        <div class="w-4 h-1 bg-slate-200 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Transferencia" class="peer sr-only" x-model="metodoPago" data-payment-method="Transferencia" @checked(old('MetodoPago', 'Tarjeta' )==='Transferencia' )>
                            <div class="min-h-[110px] p-5 border-2 border-slate-100 rounded-[2rem] bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md transition-all flex flex-col justify-between group-hover:border-slate-200">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-slate-900">Transferencia</span>
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                </div>
                                <span class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-tight">Banca Móvil</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Yape" class="peer sr-only" x-model="metodoPago" data-payment-method="Yape" @checked(old('MetodoPago', 'Tarjeta' )==='Yape' )>
                            <div class="min-h-[110px] p-5 border-2 border-slate-100 rounded-[2rem] bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md transition-all flex flex-col justify-between group-hover:border-slate-200">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-slate-900">Yape / Plin</span>
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                </div>
                                <span class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-tight">Código QR</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="PagoEfectivo" class="peer sr-only" x-model="metodoPago" data-payment-method="PagoEfectivo" @checked(old('MetodoPago', 'Tarjeta' )==='PagoEfectivo' )>
                            <div class="min-h-[110px] p-5 border-2 border-slate-100 rounded-[2rem] bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md transition-all flex flex-col justify-between group-hover:border-slate-200">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-slate-900">PagoEfectivo</span>
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                </div>
                                <span class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-tight">Código CIP</span>
                            </div>
                        </label>
                    </div>

                    <div id="paymentModal" class="mt-8 transition-all duration-500"></div>
                </section>

                <button type="submit" class="btn-confirm group flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-6 rounded-[2rem] text-sm uppercase tracking-[0.3em] transition-all shadow-2xl shadow-blue-500/40">
                    Confirmar Pedido S/. <span x-text="totals.total.toFixed(2)" class="ml-2">0.00</span>
                    <svg class="w-5 h-5 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="3"></path>
                    </svg>
                </button>
            </form>
        </div>

        <aside class="lg:col-span-4 lg:sticky lg:top-28">
            <section class="bg-[#0f172a] rounded-[2.5rem] p-8 shadow-2xl shadow-slate-900/50 text-white relative overflow-hidden border border-white/5">
                <div class="flex justify-between items-center mb-6 relative">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mb-1">Pedido</p>
                        <h2 class="text-xl font-black italic uppercase tracking-tighter">Resumen del carrito</h2>
                    </div>
                    <span class="bg-blue-600/20 text-blue-400 px-4 py-1.5 rounded-full text-[10px] font-black uppercase" id="CompraItemCount">
                        0 ITEMS
                    </span>
                </div>

                <div id="CompraItems" class="CompraItems mb-8 max-h-80 overflow-y-auto pr-1 space-y-3 custom-scrollbar">
                    {{-- El JS inyectara el contenido aqui --}}
                </div>

                <div class="pt-6 border-t border-slate-800 space-y-3 relative">
                    <div class="absolute -top-10 left-0 w-full h-10 bg-linear-to-t from-[#0f172a] to-transparent pointer-events-none"></div>

                    <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subtotal</span>
                        <strong id="CompraSubtotal" class="text-sm font-black text-white font-mono uppercase">S/. 0.00</strong>
                    </div>

                    <div class="p-6 bg-blue-600/10 border border-blue-500/20 rounded-[2rem] flex flex-col gap-1">
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em]">Total a pagar</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black italic text-blue-500">S/.</span>
                            <strong id="CompraTotal" class="text-5xl font-black italic tracking-tighter leading-none text-white font-mono">0.00</strong>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/compra.js')
@endsection