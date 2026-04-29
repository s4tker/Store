@extends('layouts.app')

@section('title', 'Estadísticas | ElectroShop')

@section('styles')
    @vite(['resources/css/StatsAdmin.css'])
@endsection

@section('content')
<section class="stats-admin-shell">
    <header class="stats-admin-hero">
        <div>
            <p class="stats-admin-kicker">estadísticas</p>
            <h1>Clientes y compras del sistema</h1>
            <p>Revisa registros por día, semana, mes, año o todo el historial. También puedes exportar los datos a Excel en CSV.</p>
        </div>
        <div class="stats-admin-hero-tags">
            <span>{{ count($ClientesStats) }} clientes detectados</span>
            <span>Compras desde historial del navegador</span>
        </div>
    </header>

    <section class="stats-admin-toolbar">
        <div class="stats-admin-periods" id="StatsPeriodFilters">
            <button type="button" class="stats-admin-period is-active" data-period="day">Día</button>
            <button type="button" class="stats-admin-period" data-period="week">Semana</button>
            <button type="button" class="stats-admin-period" data-period="month">Mes</button>
            <button type="button" class="stats-admin-period" data-period="year">Año</button>
            <button type="button" class="stats-admin-period" data-period="all">Todo</button>
        </div>

        <div class="stats-admin-actions">
            <button type="button" class="stats-admin-action" id="ExportCustomersBtn">Exportar clientes</button>
            <button type="button" class="stats-admin-action" id="ExportOrdersBtn">Exportar compras</button>
        </div>
    </section>

    <div class="stats-admin-grid">
        <section class="stats-admin-card">
            <div class="stats-admin-card-head">
                <div>
                    <p class="stats-admin-kicker">clientes</p>
                    <h2>Clientes registrados</h2>
                    <p class="stats-admin-copy">Resumen general y últimos registros según el periodo elegido.</p>
                </div>
            </div>

            <div class="stats-admin-summary" id="CustomerSummaryCards"></div>

            <div class="stats-admin-table-shell">
                <div class="stats-admin-table-head">
                    <strong id="CustomerTableTitle">Últimos clientes</strong>
                    <span id="CustomerTableCount">0 registros</span>
                </div>
                <div class="stats-admin-table-wrap">
                    <table class="stats-admin-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Correo</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody id="CustomerTableRows"></tbody>
                    </table>
                </div>
                <div class="stats-admin-empty hidden" id="CustomerEmpty">
                    No hay clientes en el periodo seleccionado.
                </div>
            </div>
        </section>

        <section class="stats-admin-card">
            <div class="stats-admin-card-head">
                <div>
                    <p class="stats-admin-kicker">compras</p>
                    <h2>Compras simuladas</h2>
                    <p class="stats-admin-copy">Se leen desde el historial guardado en este navegador.</p>
                </div>
            </div>

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
            </div>

            <div class="stats-admin-table-shell">
                <div class="stats-admin-table-head">
                    <strong id="OrderTableTitle">Últimas compras</strong>
                    <span id="OrderTableCount">0 registros</span>
                </div>
                <div class="stats-admin-table-wrap">
                    <table class="stats-admin-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="OrderTableRows"></tbody>
                    </table>
                </div>
                <div class="stats-admin-empty hidden" id="OrderEmpty">
                    No hay compras en el periodo seleccionado.
                </div>
            </div>
        </section>
    </div>
</section>

<script type="application/json" id="CustomersStatsData">@json($ClientesStats)</script>
@endsection

@section('scripts')
    @vite(['resources/js/StatsAdmin.js'])
@endsection
