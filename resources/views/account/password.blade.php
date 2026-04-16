@extends('layouts.app')

@section('title', 'Cambiar Contraseña - ElectroShop')

@section('content')
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <h3 class="font-bold text-red-800 text-sm mb-2">Errores:</h3>
                <ul class="text-red-700 text-[13px] space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl">
                <p class="text-green-800 font-bold text-sm">✓ {{ session('success') }}</p>
            </div>
        @endif

        <!-- Encabezado -->
        <header class="mb-8">
            <a href="{{ route('account') }}" class="inline-flex items-center gap-2 text-blue-600 font-bold text-sm mb-4 hover:text-blue-700 transition-colors">
                ← Volver a Mi Cuenta
            </a>
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tighter uppercase italic">
                Cambiar <span class="text-blue-600">Contraseña</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-500 font-bold uppercase tracking-[0.2em] mt-3">Por tu seguridad, actualiza tu contraseña regularmente</p>
        </header>

        <!-- Formulario de cambio de contraseña -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
            
            <form action="{{ route('account.password.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
                    <p class="text-[11px] text-blue-900 font-bold">
                        🔒 Requisitos: Mínimo 6 caracteres, incluye mayúsculas, minúsculas y números
                    </p>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Contraseña Actual *</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="currentPass" required
                            class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-bold text-slate-900 placeholder-slate-400 pr-12">
                        <button type="button" onclick="togglePasswordVisibility('currentPass', 'eye1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Nueva Contraseña *</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPass" required
                                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-bold text-slate-900 placeholder-slate-400 pr-12">
                            <button type="button" onclick="togglePasswordVisibility('newPass', 'eye2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                                <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Confirmar Contraseña *</label>
                        <div class="relative">
                            <input type="password" name="new_password_confirmation" id="confirmPass" required
                                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-bold text-slate-900 placeholder-slate-400 pr-12">
                            <button type="button" onclick="togglePasswordVisibility('confirmPass', 'eye3')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                                <svg id="eye3" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl uppercase text-[11px] tracking-widest transition-colors">
                        🔐 Cambiar Contraseña
                    </button>
                    <a href="{{ route('account') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-900 font-black py-4 rounded-xl uppercase text-[11px] tracking-widest transition-colors text-center">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>

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
@endsection

@section('scripts')
    <script>
        function togglePasswordVisibility(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eyePath = document.querySelector(`#${eyeId} path:first-child`);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyePath.style.display = 'none';
            } else {
                input.type = 'password';
                eyePath.style.display = 'block';
            }
        }
    </script>
@endsection
