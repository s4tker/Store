<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mi Cuenta - ElectroShop</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50">

    <nav class="bg-[#0f172a] sticky top-0 z-[60] px-3 md:px-8 py-3 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-2 md:gap-8">

            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img w-8 h-8 md:w-10 md:h-10 rounded-full" alt="ElectroShop">
            </a>

            @auth
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('administrador'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-[#0f172a] px-3 py-1.5 rounded-lg transition-all active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4z"></path></svg>
                        <span class="text-[11px] font-black uppercase hidden sm:block text-slate-900">Panel Admin</span>
                        <span class="text-[10px] font-black uppercase sm:hidden text-slate-900">Admin</span>
                    </a>
                @endif
            @endauth

            <div class="flex-1 min-w-0 flex bg-slate-800/50 rounded-xl px-2 md:px-4 py-1.5 items-center border border-slate-700 focus-within:border-blue-500 transition-all">
                <input id="q" type="text" placeholder="Buscar productos..."
                    class="flex-1 bg-transparent outline-none text-[13px] md:text-sm text-slate-100 py-1 placeholder-slate-500 min-w-0 border-none focus:ring-0">
                <button type="button" onclick="Search()" class="hidden sm:block text-blue-400 font-bold text-xs px-2">Buscar</button>
            </div>

            <div class="flex items-center gap-2 md:gap-6 shrink-0">
                @auth
                    <a href="{{ route('account') }}" class="flex flex-col items-end">
                        <span class="hidden md:block text-[10px] text-slate-500 font-bold uppercase tracking-widest">Mi Cuenta</span>
                        <span class="text-[12px] md:text-sm font-bold text-slate-200 truncate max-w-[60px] md:max-w-none">{{ Auth::user()->Nombre }}</span>
                    </a>
                @else
                    <button type="button" onclick="openAuthModal()" class="flex flex-col items-end">
                        <span class="text-[12px] md:text-sm font-bold text-slate-200">Ingresar</span>
                    </button>
                @endauth

                <button type="button" onclick="ToggleCart(true)" class="relative bg-blue-600 text-white p-2 md:p-2.5 rounded-lg md:rounded-xl shadow-lg active:scale-90 transition-transform">
                    <span id="CartCount" class="absolute -top-2 -right-2 hidden min-w-5 h-5 px-1 rounded-full bg-amber-400 text-[#0f172a] text-[10px] font-black items-center justify-center">0</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-4 md:p-10">
        
        <!-- Mensajes de alerta -->
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
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tighter uppercase italic">
                Mi <span class="text-blue-600">Cuenta</span>
            </h1>
            <p class="text-[11px] md:text-xs text-slate-500 font-bold uppercase tracking-[0.2em] mt-3">Administra tu perfil y direcciones</p>
        </header>

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- PANEL IZQUIERDO - Información del usuario -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 sticky top-24">
                    
                    <!-- Avatar & Nombre -->
                    <div class="text-center mb-6 pb-6 border-b">
                        <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-black">
                            {{ strtoupper(substr($user->Nombre, 0, 1)) }}
                        </div>
                        <h2 class="text-lg font-black text-slate-900">{{ $user->Nombre }} {{ $user->Apellidos }}</h2>
                        <p class="text-[11px] text-slate-500 font-bold mt-1">{{ $user->Correo }}</p>
                    </div>

                    <!-- Info rápida -->
                    <div class="space-y-4 mb-6 pb-6 border-b">
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Teléfono</p>
                            <p class="text-sm font-bold text-slate-900">{{ $user->Telefono ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Miembro desde</p>
                            <p class="text-sm font-bold text-slate-900">{{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('d/m/Y') : 'Sin fecha' }}</p>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="space-y-3">
                        <a href="{{ route('account.edit') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-center text-[12px] uppercase tracking-widest transition-colors">
                            ✏️ Editar Perfil
                        </a>
                        <a href="{{ route('account.password') }}" class="block w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 rounded-xl text-center text-[12px] uppercase tracking-widest transition-colors">
                            🔒 Cambiar Contraseña
                        </a>
                        <a href="{{ route('logout') }}" class="block w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 rounded-xl text-center text-[12px] uppercase tracking-widest transition-colors">
                            Cerrar Sesión
                        </a>
                    </div>

                </div>
            </div>

            <!-- PANEL DERECHO - Direcciones -->
            <div class="lg:col-span-2">
                
                <!-- Sección de direcciones guardadas -->
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
                    <h3 class="text-lg md:text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                        📍 Mis Direcciones
                        <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-lg ml-auto">{{ count($addresses) }}</span>
                    </h3>

                    @if($addresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($addresses as $address)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl relative group hover:border-blue-400 transition-colors">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">
                                        {{ $address->Pais }} • {{ $address->Region }}
                                    </p>
                                    <p class="text-sm font-bold text-slate-900 mb-2">
                                        {{ $address->Ciudad }}
                                    </p>
                                    <p class="text-[12px] text-slate-600 mb-3">
                                        {{ $address->Direccion }}
                                        @if($address->Referencia)
                                            <br><span class="text-[11px] text-slate-500">Ref: {{ $address->Referencia }}</span>
                                        @endif
                                    </p>
                                    
                                    <form action="{{ route('account.addresses.delete', $address->Id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('¿Eliminar esta dirección?');">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-lg">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center bg-slate-50 rounded-2xl mb-6">
                            <p class="text-slate-500 font-bold text-sm">No tienes direcciones guardadas</p>
                        </div>
                    @endif

                </div>

                <!-- Formulario agregar nueva dirección -->
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <h3 class="text-lg md:text-xl font-black text-slate-900 mb-6">➕ Agregar Nueva Dirección</h3>

                    <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">País *</label>
                                <input type="text" name="Pais" value="{{ old('Pais') }}" placeholder="Perú"
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-900 placeholder-slate-400">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Región *</label>
                                <input type="text" name="Region" value="{{ old('Region') }}" placeholder="Lima"
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-900 placeholder-slate-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Ciudad *</label>
                                <input type="text" name="Ciudad" value="{{ old('Ciudad') }}" placeholder="San Isidro"
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-900 placeholder-slate-400">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Código Postal</label>
                                <input type="text" name="Referencia" value="{{ old('Referencia') }}" placeholder="Opcional"
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-900 placeholder-slate-400">
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-2 block">Dirección Completa *</label>
                            <input type="text" name="Direccion" value="{{ old('Direccion') }}" placeholder="Av. Principal 123, Apto 4B"
                                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-900 placeholder-slate-400">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl uppercase text-[11px] tracking-widest transition-colors mt-6">
                            Guardar Dirección
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </main>

    <!-- Cart Overlay y Drawer (igual que en index) -->
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

</body>
</html>
