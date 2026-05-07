@extends('layouts.app')

@section('title', 'Estadísticas | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css', 'resources/css/StatsAdmin.css'])
@endsection

@section('content')
<section class="admin-page-shell stats-admin-shell">
    <header class="admin-page-hero">
        <div class="admin-page-hero-copy">
            <a href="{{ route('admin.dashboard') }}" class="admin-page-backlink">Volver al dashboard</a>
            <p class="admin-page-kicker">estadísticas</p>
            <h1 class="admin-page-title">Reportes del panel</h1>
            <p class="admin-page-description">Visualiza clientes, pedidos y facturación con paneles consistentes y lectura más clara.</p>
            <div class="admin-card-band">
                <span class="admin-card-pill">gráficos + tablas</span>
                <span class="admin-card-pill is-soft">exportación csv</span>
            </div>
        </div>
        <div class="admin-page-stats">
            <span>{{ count($ClientesStats) }} clientes</span>
            <span>{{ count($PedidosStats) }} pedidos</span>
        </div>
    </header>

    <header class="stats-admin-header">
        <div>
            <p class="stats-admin-kicker">Estadísticas</p>
            <h1>Reportes</h1>
            <p class="stats-admin-copy">Cambia periodo, tipo de agrupación y modo de lectura sin salir de la pantalla.</p>
        </div>

        <div class="stats-admin-header-meta">
            <span>{{ count($ClientesStats) }} clientes</span>
            <span>{{ count($PedidosStats) }} pedidos</span>
        </div>
    </header>

    <section class="stats-admin-toolbar">
        <div class="stats-admin-toolbar-block">
            <span class="stats-admin-toolbar-label">Periodo</span>
            <div class="stats-admin-chip-group" id="StatsPeriodFilters">
                <button type="button" class="stats-admin-chip is-active" data-period="day">Día</button>
                <button type="button" class="stats-admin-chip" data-period="week">Semana</button>
                <button type="button" class="stats-admin-chip" data-period="month">Mes</button>
                <button type="button" class="stats-admin-chip" data-period="year">Año</button>
                <button type="button" class="stats-admin-chip" data-period="all">Todo</button>
            </div>
        </div>

        <div class="stats-admin-toolbar-block">
            <span class="stats-admin-toolbar-label">Agrupar</span>
            <div class="stats-admin-chip-group" id="StatsGranularityFilters">
                <button type="button" class="stats-admin-chip is-active" data-granularity="auto">Auto</button>
                <button type="button" class="stats-admin-chip" data-granularity="day">Día</button>
                <button type="button" class="stats-admin-chip" data-granularity="month">Mes</button>
                <button type="button" class="stats-admin-chip" data-granularity="year">Año</button>
            </div>
        </div>

        <div class="stats-admin-toolbar-block">
            <span class="stats-admin-toolbar-label">Vista</span>
            <div class="stats-admin-chip-group" id="StatsViewFilters">
                <button type="button" class="stats-admin-chip is-active" data-view="both">Todo</button>
                <button type="button" class="stats-admin-chip" data-view="charts">Gráficos</button>
                <button type="button" class="stats-admin-chip" data-view="tables">Tablas</button>
            </div>
        </div>

        <div class="stats-admin-toolbar-actions">
            <button type="button" class="stats-admin-button" id="ExportSummaryBtn">Resumen CSV</button>
            <button type="button" class="stats-admin-button" id="ExportCustomersBtn">Clientes CSV</button>
            <button type="button" class="stats-admin-button" id="ExportOrdersBtn">Pedidos CSV</button>
        </div>
    </section>

    <section class="stats-admin-kpis" id="GeneralSummaryCards"></section>

    <section class="stats-admin-main-grid stats-admin-view-panel" data-panel-type="chart">
        <article class="stats-admin-card stats-admin-card-main">
            <div class="stats-admin-card-head">
                <div>
                    <p class="stats-admin-card-label">Gráfico principal</p>
                    <h2 id="TimelineChartTitle">Ventas</h2>
                </div>

                <div class="stats-admin-card-controls">
                    <div class="stats-admin-chip-group" id="TimelineScopeFilters">
                        <button type="button" class="stats-admin-chip is-active" data-scope="revenue">Ventas</button>
                        <button type="button" class="stats-admin-chip" data-scope="orders">Pedidos</button>
                        <button type="button" class="stats-admin-chip" data-scope="customers">Clientes</button>
                    </div>
                    <button type="button" class="stats-admin-toggle" data-toggle-panel="MainChartPanel" aria-expanded="true" aria-label="Ocultar gráfico principal">
                        <span class="stats-admin-toggle-icon" aria-hidden="true">Ocultar</span>
                    </button>
                </div>
            </div>

            <div class="stats-admin-panel-body" id="MainChartPanel">
            <div class="stats-admin-chart-panel">
                <div class="stats-admin-chart-head">
                    <strong id="TimelineChartHeading">Serie</strong>
                    <span id="TimelineChartCaption">0 puntos</span>
                </div>
                <div class="stats-admin-chart-stage stats-admin-chart-stage-large" id="TimelineChart"></div>
                <div class="stats-admin-chart-footer">
                    <span id="TimelineDescription">Datos reales de la base.</span>
                    <div class="stats-admin-chart-legend" id="TimelineLegend"></div>
                </div>
            </div>
            </div>
        </article>

        <div class="stats-admin-side-grid">
            <article class="stats-admin-card">
                <div class="stats-admin-card-head">
                    <div>
                        <p class="stats-admin-card-label">Clientes</p>
                        <h2>Top clientes</h2>
                    </div>
                    <button type="button" class="stats-admin-toggle" data-toggle-panel="TopCustomersPanel" aria-expanded="true" aria-label="Ocultar top clientes">
                        <span class="stats-admin-toggle-icon" aria-hidden="true">Ocultar</span>
                    </button>
                </div>

                <div class="stats-admin-panel-body" id="TopCustomersPanel">
                <div class="stats-admin-chart-panel">
                    <div class="stats-admin-chart-head">
                        <strong id="TopCustomersChartTitle">Por gasto</strong>
                        <span id="TopCustomersChartCaption">0 clientes</span>
                    </div>
                    <div class="stats-admin-chart-stage" id="TopCustomersChart"></div>
                </div>
                </div>
            </article>

            <article class="stats-admin-card">
                <div class="stats-admin-card-head">
                    <div>
                        <p class="stats-admin-card-label">Pedidos</p>
                        <h2>Estados</h2>
                    </div>
                    <button type="button" class="stats-admin-toggle" data-toggle-panel="StatusPanel" aria-expanded="true" aria-label="Ocultar estados">
                        <span class="stats-admin-toggle-icon" aria-hidden="true">Ocultar</span>
                    </button>
                </div>

                <div class="stats-admin-panel-body" id="StatusPanel">
                <div class="stats-admin-chart-panel">
                    <div class="stats-admin-chart-head">
                        <strong id="StatusChartTitle">Distribución</strong>
                        <span id="StatusChartCaption">0 estados</span>
                    </div>
                    <div class="stats-admin-chart-stage" id="StatusChart"></div>
                </div>
                </div>
            </article>
        </div>
    </section>

    <section class="stats-admin-grid">
        <article class="stats-admin-card">
            <div class="stats-admin-card-head">
                <div>
                    <p class="stats-admin-card-label">Clientes</p>
                    <h2>Registros</h2>
                </div>
                <button type="button" class="stats-admin-toggle" data-toggle-panel="CustomersPanel" aria-expanded="true" aria-label="Ocultar clientes">
                    <span class="stats-admin-toggle-icon" aria-hidden="true">Ocultar</span>
                </button>
            </div>

            <div class="stats-admin-panel-body" id="CustomersPanel">
            <div class="stats-admin-summary" id="CustomerSummaryCards"></div>

            <div class="stats-admin-chart-panel stats-admin-view-panel" data-panel-type="chart">
                <div class="stats-admin-chart-head">
                    <strong id="CustomerChartTitle">Altas</strong>
                    <span id="CustomerChartCaption">0 puntos</span>
                </div>
                <div class="stats-admin-chart-stage" id="CustomerChart"></div>
            </div>

            <div class="stats-admin-table-panel stats-admin-view-panel" data-panel-type="table">
                <div class="stats-admin-chart-head">
                    <strong id="CustomerTableTitle">Clientes del periodo</strong>
                    <span id="CustomerTableCount">0 registros</span>
                </div>
                <div class="stats-admin-table-wrap">
                    <table class="stats-admin-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Correo</th>
                                <th>Pedidos</th>
                                <th>Gastado</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody id="CustomerTableRows"></tbody>
                    </table>
                </div>
                <div class="stats-admin-empty hidden" id="CustomerEmpty" aria-hidden="true"></div>
            </div>
            </div>
        </article>

        <article class="stats-admin-card">
            <div class="stats-admin-card-head">
                <div>
                    <p class="stats-admin-card-label">Ventas</p>
                    <h2>Facturación</h2>
                </div>
                <button type="button" class="stats-admin-toggle" data-toggle-panel="OrdersPanel" aria-expanded="true" aria-label="Ocultar ventas">
                    <span class="stats-admin-toggle-icon" aria-hidden="true">Ocultar</span>
                </button>
            </div>

            <div class="stats-admin-panel-body" id="OrdersPanel">
            <div class="stats-admin-summary" id="OrderSummaryCards"></div>

            <div class="stats-admin-metrics">
                <div class="stats-admin-metric">
                    <span>Total vendido</span>
                    <strong id="OrderTotalAmount">S/. 0.00</strong>
                </div>
                <div class="stats-admin-metric">
                    <span>Ticket promedio</span>
                    <strong id="OrderAverageAmount">S/. 0.00</strong>
                </div>
                <div class="stats-admin-metric">
                    <span>Unidades</span>
                    <strong id="OrderItemsSold">0</strong>
                </div>
                <div class="stats-admin-metric">
                    <span>Clientes compradores</span>
                    <strong id="OrderUniqueCustomers">0</strong>
                </div>
            </div>

            <div class="stats-admin-chart-panel stats-admin-view-panel" data-panel-type="chart">
                <div class="stats-admin-chart-head">
                    <strong id="OrderChartTitle">Facturación</strong>
                    <span id="OrderChartCaption">0 puntos</span>
                </div>
                <div class="stats-admin-chart-stage" id="OrderChart"></div>
            </div>

            <div class="stats-admin-table-panel stats-admin-view-panel" data-panel-type="table">
                <div class="stats-admin-chart-head">
                    <strong id="OrderTableTitle">Pedidos del periodo</strong>
                    <span id="OrderTableCount">0 registros</span>
                </div>
                <div class="stats-admin-table-wrap">
                    <table class="stats-admin-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Items</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="OrderTableRows"></tbody>
                    </table>
                </div>
                <div class="stats-admin-empty hidden" id="OrderEmpty" aria-hidden="true"></div>
            </div>
            </div>
        </article>
    </section>
</section>

<script type="application/json" id="CustomersStatsData">@json($ClientesStats)</script>
<script type="application/json" id="OrdersStatsData">@json($PedidosStats)</script>
@endsection

@section('scripts')
    @vite(['resources/js/StatsAdmin.js'])
@endsection
