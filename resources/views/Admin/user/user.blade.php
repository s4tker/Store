@extends('layouts.app')

@section('title', 'Gestionar usuarios | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css', 'resources/css/AdminUser.css'])
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 font-sans selection:bg-blue-100 selection:text-blue-900 pb-12">
    <header class="bg-white border-b border-slate-200 px-6 py-8 md:px-12 md:py-10 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition-colors mb-4 gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al dashboard
                </a>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-1">Usuarios</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight uppercase italic leading-none">Gestión de <span class="text-slate-400">usuarios</span></h1>
                <p class="mt-3 text-sm font-medium text-slate-500 max-w-md">Crea, corrige y filtra accesos administrativos con una interfaz más limpia y directa.</p>
            </div>
            <div class="flex gap-2">
                <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex flex-col items-center"><span class="text-lg leading-none">{{ count($UsuariosAdmin) }}</span> admins</span>
                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex flex-col items-center"><span class="text-lg leading-none">{{ count($UsuariosBusqueda) }}</span> total</span>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- Panel Izquierdo: Formulario -->
            <section class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 p-6 md:p-8 shadow-sm lg:sticky lg:top-6">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1" id="UserFormEyebrow">panel de acceso</p>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight" id="UserFormTitle">Registrar usuario</h2>
                    </div>
                    <button type="button" class="shrink-0 bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200" id="BtnResetUserForm">Nuevo</button>
                </div>

                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label for="UserEmailSearch" class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2">Buscar para editar</label>
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input
                            id="UserEmailSearch"
                            type="search"
                            class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400"
                            list="AdminUserEmails"
                            placeholder="correo@dominio.com"
                            autocomplete="off"
                        >
                    </div>
                    <datalist id="AdminUserEmails">
                        @foreach($UsuariosBusqueda as $usuario)
                            <option value="{{ $usuario['correo'] }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <form
                    id="FormAdminUsuario"
                    action="{{ route('admin.usuarios.store') }}"
                    method="POST"
                    class="space-y-5"
                    data-store-url="{{ route('admin.usuarios.store') }}"
                    data-update-base="{{ url('/admin/usuarios') }}"
                >
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="UserFormMethod">
                    <input type="hidden" id="EditingUserId" value="">

                    <div>
                        <label for="UserEmail" class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2">Correo</label>
                        <input type="email" name="Correo" id="UserEmail" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" required placeholder="correo@dominio.com">
                    </div>

                    <div>
                        <label for="UserPassword" class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2">Contraseña</label>
                        <input type="password" name="Password" id="UserPassword" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" minlength="6" required placeholder="mínimo 6 caracteres" autocomplete="new-password">
                        <p class="text-[10px] font-medium text-slate-400 mt-2" id="UserPasswordHelp">Es obligatoria al crear una cuenta nueva.</p>
                    </div>

                    <div>
                        <label for="UserRole" class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2">Rol</label>
                        <select name="RolId" id="UserRole" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer" required>
                            @foreach($RolesUsuarios as $rol)
                                <option value="{{ $rol->Id }}">{{ \Illuminate\Support\Str::lower($rol->Nombre) === 'admin' ? 'Administrador' : 'Usuario' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-md" id="BtnSubmitUser">Guardar</button>
                        <button type="button" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-slate-200" id="BtnClearUserEditor">Limpiar</button>
                    </div>
                </form>
            </section>

            <!-- Panel Derecho: Lista -->
            <section class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 p-6 md:p-8 shadow-sm">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">cuentas</p>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Administradores</h2>
                    </div>
                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-black" id="UserResultsCount">{{ count($UsuariosAdmin) }}</span>
                </div>

                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label for="UserListSearch" class="block text-[10px] font-black uppercase tracking-[0.15em] text-slate-500 mb-2">Filtrar la lista</label>
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <input id="UserListSearch" type="search" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" placeholder="buscar por correo o rol">
                    </div>
                </div>

                <div id="UserCatalog" class="space-y-3">
                    @forelse($UsuariosAdmin as $usuario)
                        <article
                            class="user-admin-item flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white border border-slate-100 hover:border-blue-200 rounded-2xl shadow-sm hover:shadow transition-all group"
                            data-load-user="{{ $usuario['id'] }}"
                            data-user-email="{{ \Illuminate\Support\Str::lower($usuario['correo']) }}"
                            data-user-role="{{ \Illuminate\Support\Str::lower($usuario['rol_nombre']) }}"
                        >
                            <button type="button" class="flex items-center gap-4 text-left flex-1" data-load-user="{{ $usuario['id'] }}">
                                <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-black text-lg shadow-inner shrink-0">
                                    {{ strtoupper(substr($usuario['correo'], 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-black text-slate-900 mb-0.5 group-hover:text-blue-600 transition-colors truncate">{{ $usuario['correo'] }}</h3>
                                    <span class="inline-block bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest">{{ $usuario['rol_nombre'] }}</span>
                                </div>
                            </button>

                            <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity self-end sm:self-auto">
                                <button type="button" class="bg-slate-50 hover:bg-blue-50 text-slate-500 hover:text-blue-600 px-3 py-1.5 rounded-lg font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200 hover:border-blue-200" data-load-user="{{ $usuario['id'] }}">Editar</button>
                                <button
                                    type="button"
                                    class="bg-white hover:bg-red-50 text-red-500 px-3 py-1.5 rounded-lg font-black text-[10px] uppercase tracking-widest transition-colors border border-red-100"
                                    data-delete-url="{{ route('admin.usuarios.destroy', $usuario['id']) }}"
                                    data-delete-label="usuario {{ $usuario['correo'] }}"
                                >
                                    Borrar
                                </button>
                            </div>
                        </article>
                    @empty
                        <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm font-medium text-slate-500">No hay usuarios con rol administrador para mostrar.</div>
                    @endforelse
                </div>

                <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl p-6 text-center text-sm font-medium hidden" id="NoUserResults">
                    No hay administradores que coincidan con el filtro actual.
                </div>
            </section>
        </div>
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>

<script type="application/json" id="AdminUsersData">@json($UsuariosBusqueda)</script>
@endsection

@section('scripts')
    @vite(['resources/js/AdminUser.js'])
@endsection
