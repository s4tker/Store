@extends('layouts.app-account')

@section('title', 'Editar Perfil - ElectroShop')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .input-focus-effect:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    </style>
@endsection

@section('content')
        @if($errors->any())
            <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm">
                <h3 class="font-black text-red-800 text-xs uppercase tracking-widest mb-3">Revisa los siguientes campos:</h3>
                <ul class="text-red-700 text-sm space-y-1 font-medium">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="w-1 h-1 bg-red-400 rounded-full"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-2xl shadow-sm flex items-center justify-between">
                <p class="text-emerald-800 font-bold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3"></path></svg>
                    {{ session('success') }}
                </p>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 font-black">&times;</button>
            </div>
        @endif

        <header class="mb-10">
            <nav class="mb-6">
                <a href="{{ route('account') }}" class="group inline-flex items-center gap-2 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:text-blue-600 transition-colors">
                    <span class="p-2 bg-white rounded-lg shadow-sm group-hover:bg-blue-50 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3"></path></svg>
                    </span>
                    Regresar al panel
                </a>
            </nav>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight uppercase italic leading-none">
                Editar <span class="text-blue-600 not-italic">Perfil</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-400 font-black uppercase tracking-[0.3em] mt-4 flex items-center gap-2">
                <span class="w-8 h-[2px] bg-blue-600"></span>
                Información de contacto y personal
            </p>
        </header>

        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
            
            <form action="{{ route('account.update') }}" method="POST" class="space-y-8 relative z-10">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alias *</label>
                    <input type="text" name="Alias" value="{{ old('Alias', $user->Alias) }}" required
                        class="w-full p-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombre *</label>
                        <input type="text" name="Nombre" value="{{ old('Nombre', $user->Nombre) }}" required
                            class="w-full p-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Apellidos *</label>
                        <input type="text" name="Apellidos" value="{{ old('Apellidos', $user->Apellidos) }}" required
                            class="w-full p-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">DNI (Documento de Identidad) *</label>
                        <input type="text" name="Dni" value="{{ old('Dni', $user->Dni ?? '') }}" placeholder="Ej: 12345678" maxlength="15" required
                            class="w-full p-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                        <p class="text-[9px] text-slate-400 font-bold mt-1">Documento de identidad personal</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">RUC (Registro Único de Contribuyente)</label>
                        <input type="text" name="Ruc" value="{{ old('Ruc', $user->Ruc ?? '') }}" placeholder="Ej: 20123456789" maxlength="15"
                            class="w-full p-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                        <p class="text-[9px] text-slate-400 font-bold mt-1">Opcional - Para facturación</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico *</label>
                    <div class="relative">
                        <input type="email" name="Correo" value="{{ old('Correo', $user->Correo) }}" required
                            class="w-full p-4 pl-12 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2"></path></svg>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Número de Teléfono</label>
                    <div class="relative">
                        <input type="tel" name="Telefono" value="{{ old('Telefono', $user->Telefono ?? '') }}" placeholder="987654321" maxlength="9"
                            class="w-full p-4 pl-12 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-900 placeholder-slate-300 outline-none shadow-sm">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"></path></svg>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                        Formato: 9 dígitos numéricos
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl uppercase text-[11px] tracking-[0.2em] transition-all shadow-xl shadow-blue-600/20 active:scale-95">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('account') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black py-5 rounded-2xl uppercase text-[11px] tracking-[0.2em] transition-all text-center">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>

        <div class="mt-10 text-center">
            <p class="text-xs text-slate-400 font-medium">¿Deseas cambiar tu seguridad? <a href="{{ route('account.password') }}" class="text-blue-600 font-black hover:underline">Cambiar contraseña aquí</a></p>
        </div>

    <template x-teleport="body">
        <div>
            <div x-show="cartOpen" @click="cartOpen = false" x-cloak
                 class="fixed inset-0 z-[85] bg-slate-900/80 backdrop-blur-md transition-opacity duration-300"></div>

            <aside x-show="cartOpen" x-cloak
                   x-transition:enter="transition transform duration-500"
                   x-transition:enter-start="translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition transform duration-500"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="translate-x-full"
                   class="fixed right-0 top-0 z-[90] h-full w-full max-w-md bg-white shadow-2xl">
                
                <div class="h-full flex flex-col">
                    <div class="px-8 py-7 border-b flex items-center justify-between">
                        <h2 class="text-2xl font-black italic uppercase text-slate-900 leading-none">Tu Carrito</h2>
                        <button @click="cartOpen = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-slate-900 transition-all">&times;</button>
                    </div>
                    <div id="CartItems" class="flex-1 overflow-y-auto px-8 py-6"></div>
                    <div class="p-8 border-t bg-slate-50">
                        <div class="flex items-center justify-between font-black uppercase text-[10px] mb-4 text-slate-400">
                            <span>Total estimado</span>
                            <span id="CartTotal" class="text-xl italic text-blue-600 font-black">S/.0.00</span>
                        </div>
                        <button class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl opacity-40 cursor-not-allowed uppercase text-[10px] tracking-widest">Próximamente Checkout</button>
                    </div>
                </div>
            </aside>
        </div>
    </template>
@endsection
