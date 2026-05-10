@extends('layouts.admin')

@section('title', 'Gestionar usuarios | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css'])
@endsection

@section('content')
<div class="admin-page -mx-4 md:-mx-10">
    <div class="admin-shell px-4 py-6 md:px-6 lg:px-8">
        <div class="space-y-5 pb-8">
            <section class="admin-surface p-4 md:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="min-w-0">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-slate-700">
                            <span class="h-px w-8 bg-slate-300"></span>
                            Panel admin
                        </a>
                        <h1 class="admin-title mt-3">Usuarios</h1>
                    </div>

                    <div class="grid w-full max-w-xl gap-3 sm:grid-cols-2">
                        <x-admin.stat-card label="Admins" :value="count($UsuariosAdmin)" tone="indigo">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 19a4 4 0 0 0-8 0m8 0h3v1H5v-1h3m8 0a3 3 0 0 0-8 0M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>

                        <x-admin.stat-card label="Usuarios" :value="count($UsuariosBusqueda)" tone="slate">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 20a5 5 0 0 1 10 0m-9-9a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,0.92fr)_minmax(0,1.18fr)]">
                <section class="admin-panel p-6 md:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="admin-card-kicker" id="UserFormEyebrow">Panel de acceso</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" id="UserFormTitle">Registrar usuario</h2>
                        </div>

                        <button type="button" class="admin-button" id="BtnResetUserForm">Nuevo</button>
                    </div>

                    <div class="admin-panel-soft mt-7 p-4">
                        <label for="UserEmailSearch" class="admin-label">Buscar correo</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M18 10.5a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0z"/>
                            </svg>
                            <input
                                id="UserEmailSearch"
                                type="search"
                                class="admin-input pl-11"
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
                        class="mt-7 space-y-5"
                        data-store-url="{{ route('admin.usuarios.store') }}"
                        data-update-base="{{ url('/admin/usuarios') }}"
                    >
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="UserFormMethod">
                        <input type="hidden" id="EditingUserId" value="">

                        <div>
                            <label for="UserEmail" class="admin-label">Correo</label>
                            <input type="email" name="Correo" id="UserEmail" class="admin-input" required placeholder="correo@dominio.com">
                        </div>

                        <div>
                            <label for="UserPassword" class="admin-label">Contraseña</label>
                            <input type="password" name="Password" id="UserPassword" class="admin-input" minlength="6" required placeholder="Min. 6 caracteres" autocomplete="new-password">
                            <p class="mt-2 text-xs text-slate-400" id="UserPasswordHelp"></p>
                        </div>

                        <div>
                            <label for="UserRole" class="admin-label">Rol</label>
                            <select name="RolId" id="UserRole" class="admin-select" required>
                                @foreach($RolesUsuarios as $rol)
                                    <option value="{{ $rol->Id }}">{{ \Illuminate\Support\Str::lower($rol->Nombre) === 'admin' ? 'Administrador' : 'Usuario' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" class="admin-button-primary flex-1" id="BtnSubmitUser">Registrar usuario</button>
                            <button type="button" class="admin-button-danger" id="BtnClearUserEditor">Limpiar</button>
                        </div>
                    </form>
                </section>

                <section class="admin-panel p-6 md:p-7">
                    <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="admin-card-kicker">Cuentas</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Administradores</h2>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600" id="UserResultsCount">{{ count($UsuariosAdmin) }}</span>
                    </div>

                    <div class="admin-panel-soft mt-7 p-4">
                        <label for="UserListSearch" class="admin-label">Filtrar lista</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M18 10.5a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0z"/>
                            </svg>
                            <input id="UserListSearch" type="search" class="admin-input pl-11" placeholder="Buscar por correo o rol">
                        </div>
                    </div>

                    <div id="UserCatalog" class="mt-7 space-y-3">
                        @forelse($UsuariosAdmin as $usuario)
                            <article
                                class="user-admin-item admin-list-card"
                                data-load-user="{{ $usuario['id'] }}"
                                data-user-email="{{ \Illuminate\Support\Str::lower($usuario['correo']) }}"
                                data-user-role="{{ \Illuminate\Support\Str::lower($usuario['rol_nombre']) }}"
                            >
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <button type="button" class="flex flex-1 items-center gap-4 text-left" data-load-user="{{ $usuario['id'] }}">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                                            {{ strtoupper(substr($usuario['correo'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="truncate text-sm font-semibold text-slate-950">{{ $usuario['correo'] }}</h3>
                                            <p class="mt-1 text-xs uppercase tracking-[0.22em] text-slate-400">{{ $usuario['rol_nombre'] }}</p>
                                        </div>
                                    </button>

                                    <div class="flex items-center gap-2">
                                        <button type="button" class="admin-button" data-load-user="{{ $usuario['id'] }}">Editar</button>
                                        <button
                                            type="button"
                                            class="admin-button-danger"
                                            data-delete-url="{{ route('admin.usuarios.destroy', $usuario['id']) }}"
                                            data-delete-label="usuario {{ $usuario['correo'] }}"
                                        >
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="admin-empty">No hay usuarios con rol administrador para mostrar.</div>
                        @endforelse
                    </div>

                    <div class="admin-empty mt-4 hidden" id="NoUserResults">
                        No hay administradores que coincidan con el filtro actual.
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<div id="Toast" class="fixed bottom-6 right-6 z-[9999]"></div>

<script type="application/json" id="AdminUsersData">@json($UsuariosBusqueda)</script>
@endsection

@section('scripts')
    @vite(['resources/js/AdminUser.js'])
@endsection
