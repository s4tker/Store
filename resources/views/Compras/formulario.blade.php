@extends('layouts.app')

@section('title', 'Finalizar Compra - ElectroShop')

@section('styles')
    @vite('resources/css/compra.css')
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12" x-data="compraFlow()">

    <header class="mb-12 relative">
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

        <!-- COLUMNA IZQUIERDA: FORMULARIOS -->
        <div class="lg:col-span-8 space-y-8">
            <form id="CompraForm" method="POST" action="{{ route('pedidos.store') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="carrito" id="CompraCarrito">

                <!-- PASO 1: IDENTIFICACIÓN -->
                <section class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black italic">01</span>
                        <h2 class="text-xl font-black text-slate-900 uppercase italic">Identificación</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">DNI / RUC</label>
                            <input type="text" name="Documento" x-model="form.Documento" required placeholder="N° de documento" class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Teléfono</label>
                            <input type="tel" name="Telefono" x-model="form.Telefono" required placeholder="999 999 999" class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombres</label>
                            <input type="text" name="Nombre" x-model="form.Nombre" required class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Apellidos</label>
                            <input type="text" name="Apellidos" x-model="form.Apellidos" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                            <input type="email" name="Correo" x-model="form.Correo" required class="industrial-input">
                        </div>
                    </div>
                </section>

                <!-- PASO 2: ENTREGA -->
                <section class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black italic">02</span>
                        <h2 class="text-xl font-black text-slate-900 uppercase italic">Datos de Envío</h2>
                    </div>

                    <!-- Direcciones Guardadas -->
                    <template x-if="direccionesGuardadas.length > 0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <template x-for="dir in direccionesGuardadas" :key="dir.Id">
                                <button type="button"
                                    @click="setDireccion(dir)"
                                    class="address-card p-5 border-2 border-slate-100 rounded-[1.8rem] text-left transition-all group"
                                    :class="dirId === dir.Id ? 'is-active' : 'hover:border-slate-200'">
                                    <div class="flex justify-between items-start">
                                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1" x-text="dir.Ciudad"></p>
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center" :class="dirId === dir.Id ? 'border-blue-500' : ''">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full" x-show="dirId === dir.Id"></div>
                                        </div>
                                    </div>
                                    <p class="text-sm font-black text-slate-800 truncate" x-text="dir.Direccion"></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1" x-text="dir.Region"></p>
                                </button>
                            </template>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Departamento / Región</label>
                            <input type="text" name="Region" x-model="form.Region" required class="industrial-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ciudad / Distrito</label>
                            <input type="text" name="Ciudad" x-model="form.Ciudad" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dirección Exacta</label>
                            <input type="text" name="Direccion" x-model="form.Direccion" required class="industrial-input">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Notas de Entrega / Referencias</label>
                            <textarea name="Notas" x-model="form.Notas" rows="3" class="industrial-input resize-none" placeholder="Ej. Casa de fachada azul, frente al parque..."></textarea>
                        </div>
                    </div>
                </section>

                <!-- PASO 3: PAGO -->
                <section class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black italic">03</span>
                        <h2 class="text-xl font-black text-slate-900 uppercase italic">Método de Pago</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Tarjeta" class="peer sr-only" checked x-model="metodoPago">
                            <div class="p-6 border-2 border-slate-50 rounded-3xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest block">Tarjeta</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Transferencia" class="peer sr-only" x-model="metodoPago">
                            <div class="p-6 border-2 border-slate-50 rounded-3xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest block">Transferencia</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="MetodoPago" value="Yape" class="peer sr-only" x-model="metodoPago">
                            <div class="p-6 border-2 border-slate-50 rounded-3xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest block">Yape / Plin</span>
                            </div>
                        </label>
                    </div>
                </section>

                <button type="submit" class="btn-confirm group flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-6 rounded-[2rem] text-sm uppercase tracking-[0.3em] transition-all shadow-2xl shadow-blue-500/40">
                    Confirmar Pedido S/. <span x-text="totals.total.toFixed(2)" class="ml-2">0.00</span>
                    <svg class="w-5 h-5 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="3"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- COLUMNA DERECHA: RESUMEN (SIDEBAR) -->
        <aside class="lg:col-span-4 lg:sticky lg:top-28">
            <section class="bg-[#0f172a] rounded-[2.5rem] p-8 shadow-2xl shadow-slate-900/50 text-white relative overflow-hidden border border-white/5">

                <!-- HEADER DEL RESUMEN -->
                <div class="flex justify-between items-center mb-6 relative">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mb-1">Pedido</p>
                        <h2 class="text-xl font-black italic uppercase tracking-tighter">Resumen del carrito</h2>
                    </div>
                    <span class="bg-blue-600/20 text-blue-400 px-4 py-1.5 rounded-full text-[10px] font-black uppercase" id="CompraItemCount">
                        0 ITEMS
                    </span>
                </div>

                <!-- LISTA DE PRODUCTOS CON SCROLL INDUSTRIAL -->
                <!-- La altura máxima (max-h-96) asegura que el panel sea compacto -->
                <div id="CompraItems" class="CompraItems mb-8 max-h-80 overflow-y-auto pr-1 space-y-3 custom-scrollbar">
                    {{-- El JS inyectará el contenido aquí --}}
                </div>

                <!-- SECCIÓN DE TOTALES (Cards Independientes) -->
                <div class="pt-6 border-t border-slate-800 space-y-3 relative">
                    <!-- Efecto de desvanecimiento para indicar que hay más productos arriba -->
                    <div class="absolute -top-10 left-0 w-full h-10 bg-gradient-to-t from-[#0f172a] to-transparent pointer-events-none"></div>

                    <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subtotal</span>
                        <strong id="CompraSubtotal" class="text-sm font-black text-white font-mono uppercase">S/. 0.00</strong>
                    </div>

                    <!-- TOTAL DESTACADO (Estilo Premium) -->
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