@extends('layouts.app-account')

@section('title', 'Mi Cuenta - ElectroShop')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
@endsection

@section('content')
        @if($errors->any())
            <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm animate-pulse">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <h3 class="font-black text-red-800 text-sm uppercase tracking-wider">Atención</h3>
                </div>
                <ul class="text-red-700 text-xs font-medium space-y-1 ml-7">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-2xl shadow-sm flex items-center">
                <div class="bg-emerald-500 p-1 rounded-full mr-3 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <header class="mb-12 relative">
            <div class="absolute -left-4 top-0 w-1 h-full bg-blue-600 rounded-full"></div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight uppercase italic leading-none">
                Mi <span class="text-blue-600 not-italic">Cuenta</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-400 font-black uppercase tracking-[0.3em] mt-4 flex items-center gap-2">
                <span class="w-8 h-[2px] bg-slate-200"></span>
                Gestión de perfil y logística
            </p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/60 border border-slate-100 sticky top-28 transition-all hover:shadow-2xl">
                    
                    <div class="text-center mb-8 pb-8 border-b border-slate-50">
                        <div class="relative inline-block group">
                            <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-blue-500/40 rotate-3 group-hover:rotate-0 transition-transform duration-500">
                                {{ strtoupper(substr($user->Nombre, 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center border border-slate-100">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                            </div>
                        </div>
                        <h2 class="text-xl font-black text-slate-900 mt-2">{{ $user->Nombre }} {{ $user->Apellidos }}</h2>
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-tighter mt-2">{{ $user->Correo }}</span>
                    </div>

                    <div class="space-y-6 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h5m0 0h5m-5 0a2 2 0 002-2V8a2 2 0 012-2h5"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">DNI</p>
                                <p class="text-sm font-bold text-slate-700">{{ $user->Dni ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">RUC</p>
                                <p class="text-sm font-bold text-slate-700">{{ $user->Ruc ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Teléfono móvil</p>
                                <p class="text-sm font-bold text-slate-700">{{ $user->Telefono ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Antigüedad</p>
                                <p class="text-sm font-bold text-slate-700">{{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('M Y') : 'Sin fecha' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('account.edit') }}" class="group flex items-center justify-center w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 rounded-2xl text-[11px] uppercase tracking-widest transition-all shadow-lg shadow-slate-200">
                            Editar Perfil
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"></path></svg>
                        </a>
                        <a href="{{ route('account.password') }}" class="block w-full bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl text-center text-[11px] uppercase tracking-widest transition-colors">
                             Seguridad
                        </a>
                        <a href="{{ route('logout') }}" class="block w-full border-2 border-red-50 hover:bg-red-50 text-red-500 font-black py-4 rounded-2xl text-center text-[11px] uppercase tracking-widest transition-all">
                            Cerrar Sesión
                        </a>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-8 space-y-8">
                
                <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 uppercase italic">Mis Direcciones</h3>
                            <p class="text-xs text-slate-400 font-medium">Lugares guardados para tus entregas</p>
                        </div>
                        <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-2xl text-xs font-black">{{ count($addresses) }} Total</span>
                    </div>

                    @if($addresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                            @foreach($addresses as $address)
                                <div class="group p-6 bg-[#fdfdfe] border-2 border-slate-50 rounded-[1.5rem] relative hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="bg-white p-2 rounded-lg shadow-sm border border-slate-100">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path></svg>
                                        </div>
                                        <form action="{{ route('account.addresses.delete', $address->Id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta dirección?');">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.15em] mb-1">
                                        {{ $address->Pais }} • {{ $address->Region }}
                                    </p>
                                    <p class="text-base font-black text-slate-800 mb-1 capitalize">{{ strtolower($address->Ciudad) }}</p>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                        {{ $address->Direccion }}
                                        @if($address->Referencia)
                                            <span class="block mt-2 py-1 px-2 bg-white border border-slate-100 rounded-md text-[10px] text-slate-400 italic">Ref: {{ $address->Referencia }}</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                            </div>
                            <p class="text-slate-400 font-bold text-sm">No hay direcciones registradas todavía</p>
                        </div>
                    @endif
                </div>

                <div class="bg-slate-900 rounded-[2rem] p-6 md:p-10 shadow-2xl shadow-slate-400/20 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-xl font-black mb-8 flex items-center gap-3 italic uppercase">
                         <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center not-italic text-sm"> + </span>
                         Añadir Nueva Dirección
                    </h3>

                    <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-5 relative z-10">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">País *</label>
                                <input type="text" name="Pais" value="{{ old('Pais') }}" placeholder="Ej. Perú"
                                    class="w-full p-4 bg-slate-800/50 border border-slate-700 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all font-bold text-sm text-white placeholder-slate-600">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Región / Estado *</label>
                                <input type="text" name="Region" value="{{ old('Region') }}" placeholder="Ej. Lima"
                                    class="w-full p-4 bg-slate-800/50 border border-slate-700 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all font-bold text-sm text-white placeholder-slate-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ciudad *</label>
                                <input type="text" name="Ciudad" value="{{ old('Ciudad') }}" placeholder="Ej. Miraflores"
                                    class="w-full p-4 bg-slate-800/50 border border-slate-700 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all font-bold text-sm text-white placeholder-slate-600">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Código Postal / Referencia</label>
                                <input type="text" name="Referencia" value="{{ old('Referencia') }}" placeholder="Opcional"
                                    class="w-full p-4 bg-slate-800/50 border border-slate-700 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all font-bold text-sm text-white placeholder-slate-600">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dirección Exacta *</label>
                            <input type="text" name="Direccion" value="{{ old('Direccion') }}" placeholder="Calle, número, departamento..."
                                class="w-full p-4 bg-slate-800/50 border border-slate-700 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all font-bold text-sm text-white placeholder-slate-600">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-5 rounded-2xl uppercase text-xs tracking-[0.2em] transition-all shadow-xl shadow-blue-600/20 active:scale-95 mt-4">
                            Guardar Dirección
                        </button>
                    </form>
                </div>

            </div>

        </div>

    <div id="CartOverlay" class="fixed inset-0 z-[85] hidden bg-slate-900/80 backdrop-blur-md transition-opacity duration-300" onclick="ToggleCart(false)"></div>
    <aside id="CartDrawer" class="fixed right-0 top-0 z-[90] h-full w-full max-w-md translate-x-full bg-white shadow-[-20px_0_50px_-10px_rgba(0,0,0,0.1)] transition-transform duration-500 ease-in-out">
        <div class="h-full flex flex-col">
            <div class="px-8 py-7 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black italic uppercase text-slate-900 leading-none">Tu Carrito</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Ready to checkout?</p>
                </div>
                <button onclick="ToggleCart(false)" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all">&times;</button>
            </div>
            
            <div id="CartItems" class="flex-1 overflow-y-auto px-8 py-6 space-y-6 custom-scrollbar"></div>
            
            <div class="p-8 border-t bg-slate-50/50">
                <div class="flex items-center justify-between font-black uppercase text-[10px] mb-4 text-slate-400 tracking-tighter">
                    <span>Subtotal estimado</span>
                    <span id="CartTotal" class="text-xl italic text-blue-600 font-black">S/.0.00</span>
                </div>
                <button class="w-full bg-slate-900 text-white font-black py-5 rounded-[1.5rem] opacity-40 cursor-not-allowed uppercase text-[10px] tracking-[0.2em] shadow-lg shadow-slate-200">
                    Próximamente Checkout
                </button>
            </div>
        </div>
    </aside>
@endsection