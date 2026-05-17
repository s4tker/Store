@extends('layouts.admin')

@section('title', 'Estadísticas | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css', 'resources/css/StatsAdmin.css'])
@endsection

@section('content')
<div class="admin-page -mx-4 md:-mx-10">
    <div class="admin-shell px-4 py-6 md:px-6 lg:px-8">
        <div class="space-y-5 pb-8">
            <section class="admin-surface p-4 md:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="min-w-0">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-slate-700">
                            <svg class="h-4 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 32 16" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 3 3 8l5 5M4 8h25"/></svg>
                            Panel admin
                        </a>
                        <h1 class="admin-title mt-3">Estadísticas</h1>
                    </div>

                    <div class="grid w-full max-w-xl gap-3 sm:grid-cols-2">
                        <x-admin.stat-card label="Clientes" :value="count($ClientesStats)" tone="indigo">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 19a4 4 0 0 0-8 0m8 0h3v1H5v-1h3m8 0a3 3 0 0 0-8 0M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>

                        <x-admin.stat-card label="Pedidos" :value="count($PedidosStats)" tone="amber">
                            <x-slot:icon>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5.75A1.75 1.75 0 0 1 6.75 4h10.5A1.75 1.75 0 0 1 19 5.75v12.5A1.75 1.75 0 0 1 17.25 20H6.75A1.75 1.75 0 0 1 5 18.25zM8 8h8M8 12h8M8 16h5"/>
                                </svg>
                            </x-slot:icon>
                        </x-admin.stat-card>
                    </div>
                </div>
            </section>

            <section class="admin-panel sticky top-4 z-20 p-5">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <p class="admin-label mb-3">Periodo</p>
                            <div class="flex flex-wrap gap-2" id="StatsPeriodFilters">
                                <button type="button" class="stats-admin-chip is-active" data-period="day">Día</button>
                                <button type="button" class="stats-admin-chip" data-period="week">Semana</button>
                                <button type="button" class="stats-admin-chip" data-period="month">Mes</button>
                                <button type="button" class="stats-admin-chip" data-period="year">Año</button>
                                <button type="button" class="stats-admin-chip" data-period="all">Todo</button>
                            </div>
                        </div>

                        <div>
                            <p class="admin-label mb-3">Agrupar</p>
                            <div class="flex flex-wrap gap-2" id="StatsGranularityFilters">
                                <button type="button" class="stats-admin-chip is-active" data-granularity="auto">Auto</button>
                                <button type="button" class="stats-admin-chip" data-granularity="day">Día</button>
                                <button type="button" class="stats-admin-chip" data-granularity="month">Mes</button>
                                <button type="button" class="stats-admin-chip" data-granularity="year">Año</button>
                            </div>
                        </div>

                        <div>
                            <p class="admin-label mb-3">Vista</p>
                            <div class="flex flex-wrap gap-2" id="StatsViewFilters">
                                <button type="button" class="stats-admin-chip is-active" data-view="both">Todo</button>
                                <button type="button" class="stats-admin-chip" data-view="charts">Gráficos</button>
                                <button type="button" class="stats-admin-chip" data-view="tables">Tablas</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 xl:justify-end">
                        <button type="button" class="stats-admin-button" id="ExportSummaryBtn">Resumen</button>
                        <button type="button" class="stats-admin-button" id="ExportCustomersBtn">Clientes</button>
                        <button type="button" class="stats-admin-button" id="ExportOrdersBtn">Pedidos</button>
                    </div>
                </div>
            </section>

            <section class="admin-stat-grid" id="GeneralSummaryCards"></section>

            <section class="stats-admin-reflow-grid grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(0,0.78fr)] stats-admin-view-panel" data-panel-type="chart">
                <article class="admin-panel p-6 md:p-7">
                    <div class="stats-admin-panel-head flex flex-col gap-4 border-b border-slate-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="admin-card-kicker">Serie principal</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950" id="TimelineChartTitle">Ventas</h2>
                        </div>

                        <div class="flex flex-col gap-3 sm:items-end">
                            <div class="flex flex-wrap justify-start gap-2 sm:justify-end" id="TimelineScopeFilters">
                                <button type="button" class="stats-admin-chip is-active" data-scope="revenue">Ventas</button>
                                <button type="button" class="stats-admin-chip" data-scope="orders">Pedidos</button>
                                <button type="button" class="stats-admin-chip" data-scope="customers">Clientes</button>
                            </div>
                            <button type="button" class="stats-admin-toggle self-start sm:self-end" data-toggle-panel="MainChartPanel" aria-expanded="true" aria-label="Ocultar gráfico principal">Ocultar</button>
                        </div>
                    </div>

                    <div class="stats-admin-panel-body pt-6" id="MainChartPanel">
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
                </article>

                <div class="stats-admin-reflow-grid grid gap-6">
                    <article class="admin-panel p-6">
                        <div class="stats-admin-panel-head flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <p class="admin-card-kicker">Clientes</p>
                                <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-950">Top clientes</h2>
                            </div>
                            <button type="button" class="stats-admin-toggle" data-toggle-panel="TopCustomersPanel" aria-expanded="true" aria-label="Ocultar top clientes">Ocultar</button>
                        </div>

                        <div class="stats-admin-panel-body pt-5" id="TopCustomersPanel">
                            <div class="stats-admin-chart-head">
                                <strong id="TopCustomersChartTitle">Por gasto</strong>
                                <span id="TopCustomersChartCaption">0 clientes</span>
                            </div>
                            <div class="stats-admin-chart-stage stats-admin-compact-stage" id="TopCustomersChart"></div>
                        </div>
                    </article>

                    <article class="admin-panel p-6">
                        <div class="stats-admin-panel-head flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <p class="admin-card-kicker">Pedidos</p>
                                <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-950">Estados</h2>
                            </div>
                            <button type="button" class="stats-admin-toggle" data-toggle-panel="StatusPanel" aria-expanded="true" aria-label="Ocultar estados">Ocultar</button>
                        </div>

                        <div class="stats-admin-panel-body pt-5" id="StatusPanel">
                            <div class="stats-admin-chart-head">
                                <strong id="StatusChartTitle">Distribución</strong>
                                <span id="StatusChartCaption">0 estados</span>
                            </div>
                            <div class="stats-admin-chart-stage stats-admin-compact-stage" id="StatusChart"></div>
                        </div>
                    </article>
                </div>
            </section>

            <section class="stats-admin-reflow-grid grid gap-6 xl:grid-cols-2">
                <article class="admin-panel p-6 md:p-7">
                    <div class="stats-admin-panel-head flex items-start justify-between gap-4 border-b border-slate-100 pb-6">
                        <div>
                            <p class="admin-card-kicker">Clientes</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Registros</h2>
                        </div>
                        <button type="button" class="stats-admin-toggle" data-toggle-panel="CustomersPanel" aria-expanded="true" aria-label="Ocultar clientes">Ocultar</button>
                    </div>

                    <div class="stats-admin-panel-body pt-6" id="CustomersPanel">
                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" id="CustomerSummaryCards"></div>

                        <div class="stats-admin-view-panel mt-6 border-t border-slate-100 pt-6" data-panel-type="chart">
                            <div class="stats-admin-chart-head">
                                <strong id="CustomerChartTitle">Altas</strong>
                                <span id="CustomerChartCaption">0 puntos</span>
                            </div>
                            <div class="stats-admin-chart-stage" id="CustomerChart"></div>
                        </div>

                        <div class="stats-admin-view-panel mt-6 border-t border-slate-100 pt-6" data-panel-type="table">
                            <div class="stats-admin-chart-head">
                                <strong id="CustomerTableTitle">Clientes del periodo</strong>
                                <span id="CustomerTableCount">0 registros</span>
                            </div>
                            <div class="admin-table-wrap stats-admin-table-scroll stats-admin-table-compact">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Correo</th>
                                            <th class="text-center">Pedidos</th>
                                            <th class="text-right">Gastado</th>
                                            <th class="text-right">Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody id="CustomerTableRows"></tbody>
                                </table>
                            </div>
                            <div class="admin-empty mt-4 hidden" id="CustomerEmpty" aria-hidden="true"></div>
                        </div>
                    </div>
                </article>

                <article class="admin-panel p-6 md:p-7">
                    <div class="stats-admin-panel-head flex items-start justify-between gap-4 border-b border-slate-100 pb-6">
                        <div>
                            <p class="admin-card-kicker">Ventas</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Facturación</h2>
                        </div>
                        <button type="button" class="stats-admin-toggle" data-toggle-panel="OrdersPanel" aria-expanded="true" aria-label="Ocultar ventas">Ocultar</button>
                    </div>

                    <div class="stats-admin-panel-body pt-6" id="OrdersPanel">
                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" id="OrderSummaryCards"></div>

                        <div class="mt-6 grid gap-4 rounded-[1.5rem] border border-slate-200/70 bg-slate-50/70 p-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div>
                                <p class="admin-card-kicker">Total vendido</p>
                                <strong class="mt-2 block text-lg font-semibold text-slate-900" id="OrderTotalAmount">S/. 0.00</strong>
                            </div>
                            <div>
                                <p class="admin-card-kicker">Ticket promedio</p>
                                <strong class="mt-2 block text-lg font-semibold text-slate-900" id="OrderAverageAmount">S/. 0.00</strong>
                            </div>
                            <div>
                                <p class="admin-card-kicker">Unidades</p>
                                <strong class="mt-2 block text-lg font-semibold text-slate-900" id="OrderItemsSold">0</strong>
                            </div>
                            <div>
                                <p class="admin-card-kicker">Clientes</p>
                                <strong class="mt-2 block text-lg font-semibold text-slate-900" id="OrderUniqueCustomers">0</strong>
                            </div>
                        </div>

                        <div class="stats-admin-view-panel mt-6 border-t border-slate-100 pt-6" data-panel-type="chart">
                            <div class="stats-admin-chart-head">
                                <strong id="OrderChartTitle">Facturación</strong>
                                <span id="OrderChartCaption">0 puntos</span>
                            </div>
                            <div class="stats-admin-chart-stage" id="OrderChart"></div>
                        </div>

                        <div class="stats-admin-view-panel mt-6 border-t border-slate-100 pt-6" data-panel-type="table">
                            <div class="stats-admin-chart-head">
                                <strong id="OrderTableTitle">Pedidos del periodo</strong>
                                <span id="OrderTableCount">0 registros</span>
                            </div>
                            <div class="admin-table-wrap stats-admin-table-scroll stats-admin-table-compact">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Cliente</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Items</th>
                                            <th class="text-right">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody id="OrderTableRows"></tbody>
                                </table>
                            </div>
                            <div class="admin-empty mt-4 hidden" id="OrderEmpty" aria-hidden="true"></div>
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </div>
</div>

<script type="application/json" id="CustomersStatsData">@json($ClientesStats)</script>
<script type="application/json" id="OrdersStatsData">@json($PedidosStats)</script>
@endsection

@section('scripts')
    @vite(['resources/js/StatsAdmin.js'])
@endsection
