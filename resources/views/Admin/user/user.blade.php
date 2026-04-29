@extends('layouts.app')

@section('title', 'Gestionar usuarios | ElectroShop')

@section('styles')
    @vite(['resources/css/AdminUser.css'])
@endsection

@section('content')
<section class="user-admin-shell">
    <div class="user-admin-grid">
        <section class="user-admin-card">
            <div class="user-admin-card-head">
                <div>
                    <p class="user-admin-kicker" id="UserFormEyebrow">panel de acceso</p>
                    <h2 id="UserFormTitle">Registrar usuario</h2>
                    <p class="user-admin-intro">Crea o edita cuentas del panel con una vista más limpia y enfocada.</p>
                </div>
                <button type="button" class="user-admin-secondary" id="BtnResetUserForm">Nuevo</button>
            </div>

            <div class="user-admin-search-box">
                <label for="UserEmailSearch" class="user-admin-label">Buscar por correo</label>
                <input
                    id="UserEmailSearch"
                    type="search"
                    class="user-admin-input"
                    list="AdminUserEmails"
                    placeholder="correo@dominio.com"
                    autocomplete="off"
                >
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
                class="user-admin-form"
                data-store-url="{{ route('admin.usuarios.store') }}"
                data-update-base="{{ url('/admin/usuarios') }}"
            >
                @csrf
                <input type="hidden" name="_method" value="POST" id="UserFormMethod">
                <input type="hidden" id="EditingUserId" value="">

                <div>
                    <label for="UserEmail" class="user-admin-label">Correo</label>
                    <input type="email" name="Correo" id="UserEmail" class="user-admin-input" required placeholder="correo@dominio.com">
                </div>

                <div>
                    <label for="UserPassword" class="user-admin-label">Contraseña</label>
                    <input type="password" name="Password" id="UserPassword" class="user-admin-input" minlength="6" required placeholder="mínimo 6 caracteres" autocomplete="new-password">
                    <p class="user-admin-helper" id="UserPasswordHelp">Es obligatoria al crear una cuenta nueva.</p>
                </div>

                <div>
                    <label for="UserRole" class="user-admin-label">Rol</label>
                    <select name="RolId" id="UserRole" class="user-admin-input" required>
                        @foreach($RolesUsuarios as $rol)
                            <option value="{{ $rol->Id }}">{{ \Illuminate\Support\Str::lower($rol->Nombre) === 'admin' ? 'Administrador' : 'Usuario' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="user-admin-actions">
                    <button type="submit" class="user-admin-primary" id="BtnSubmitUser">Registrar usuario</button>
                    <button type="button" class="user-admin-ghost" id="BtnClearUserEditor">Limpiar</button>
                </div>
            </form>
        </section>

        <section class="user-admin-card">
            <div class="user-admin-card-head user-admin-card-head-stack">
                <div>
                    <p class="user-admin-kicker">cuentas del panel</p>
                    <h2>Administradores</h2>
                </div>
                <span class="user-admin-badge" id="UserResultsCount">{{ count($UsuariosAdmin) }} resultados</span>
            </div>

            <div class="user-admin-search-box user-admin-search-box-soft">
                <label for="UserListSearch" class="user-admin-label">Filtrar la lista</label>
                <input id="UserListSearch" type="search" class="user-admin-input" placeholder="buscar por correo o rol">
            </div>

            <div id="UserCatalog" class="user-admin-list">
                @forelse($UsuariosAdmin as $usuario)
                    <article
                        class="user-admin-item"
                        data-load-user="{{ $usuario['id'] }}"
                        data-user-email="{{ \Illuminate\Support\Str::lower($usuario['correo']) }}"
                        data-user-role="{{ \Illuminate\Support\Str::lower($usuario['rol_nombre']) }}"
                    >
                        <button type="button" class="user-admin-item-main" data-load-user="{{ $usuario['id'] }}">
                            <div class="user-admin-avatar">{{ strtoupper(substr($usuario['correo'], 0, 1)) }}</div>
                            <div class="user-admin-item-copy">
                                <div class="user-admin-item-top">
                                    <h3>{{ $usuario['correo'] }}</h3>
                                    <span class="user-admin-role">{{ $usuario['rol_nombre'] }}</span>
                                </div>
                            </div>
                        </button>

                        <div class="user-admin-item-actions">
                            <button type="button" class="user-admin-ghost user-admin-ghost-small" data-load-user="{{ $usuario['id'] }}">Editar</button>
                            <button
                                type="button"
                                class="user-admin-danger"
                                data-delete-url="{{ route('admin.usuarios.destroy', $usuario['id']) }}"
                                data-delete-label="usuario {{ $usuario['correo'] }}"
                            >
                                Eliminar
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="user-admin-empty">No hay usuarios con rol administrador para mostrar.</div>
                @endforelse
            </div>

            <div class="user-admin-empty hidden" id="NoUserResults">
                No hay administradores que coincidan con el filtro actual.
            </div>
        </section>
    </div>
</section>

<script type="application/json" id="AdminUsersData">@json($UsuariosBusqueda)</script>
@endsection

@section('scripts')
    @vite(['resources/js/AdminUser.js'])
@endsection
