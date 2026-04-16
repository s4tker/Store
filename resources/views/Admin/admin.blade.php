<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Admin | ElectroShop</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/AdminControl.js'])
</head>
<body class="admin-body antialiased">
    <div id="AdminSidebarOverlay" class="admin-sidebar-overlay hidden"></div>

    <div class="admin-shell">
        <aside id="AdminSidebar" class="admin-sidebar">
            <div class="admin-sidebar-top">
                <a href="{{ route('home') }}" class="admin-logo-link">
                    <span class="admin-logo-text">Electro<span>Shop</span></span>
                </a>
                <p class="admin-kicker">Panel administrativo</p>
            </div>

            <nav class="admin-nav">
                <button type="button" class="admin-nav-item active" data-section="productos">Productos</button>
                <button type="button" class="admin-nav-item" data-section="categorias">Categorías</button>
                <button type="button" class="admin-nav-item" data-section="marcas">Marcas</button>
            </nav>

            <section class="admin-sidebar-card">
                <div class="admin-sidebar-card-head">
                    <h2>Categorías</h2>
                    <span>{{ $Categorias->count() }}</span>
                </div>

                <div class="admin-sidebar-list">
                    @forelse($Categorias as $categoria)
                        <div class="admin-sidebar-list-item">
                            <strong>{{ $categoria->Nombre }}</strong>
                            <small>{{ $categoria->subcategorias->count() }} subcategoría(s)</small>
                        </div>
                    @empty
                        <p class="admin-sidebar-empty">Aún no hay categorías principales.</p>
                    @endforelse
                </div>
            </section>

            <div class="admin-user">
                <div class="admin-avatar">
                    {{ strtoupper(substr(Auth::user()->Nombre ?? 'A', 0, 1)) }}
                </div>
                <div class="admin-user-copy">
                    <p class="admin-user-name">{{ Auth::user()->Nombre ?: 'Administrador' }}</p>
                    <a href="{{ route('logout') }}" class="admin-user-link">Cerrar sesión</a>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <button type="button" id="AdminSidebarToggle" class="admin-menu-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <p class="admin-kicker">Gestión central</p>
                    <h1 class="admin-title">Organiza tu tienda sin recargar la vista.</h1>
                </div>

                <div class="admin-summary">
                    <span>{{ $Productos->count() }} productos</span>
                    <span>{{ $TodasLasCategorias->count() }} categorías</span>
                    <span>{{ $Marcas->count() }} marcas</span>
                </div>
            </header>

            <section class="admin-sections">
                <div class="admin-section" id="section-productos">
                    @include('Admin.sections.productos')
                </div>

                <div class="admin-section hidden" id="section-categorias">
                    @include('Admin.sections.categorias')
                </div>

                <div class="admin-section hidden" id="section-marcas">
                    @include('Admin.sections.marcas')
                </div>
            </section>
        </main>
    </div>

    <div id="Toast" class="admin-toast"></div>
</body>
</html>
