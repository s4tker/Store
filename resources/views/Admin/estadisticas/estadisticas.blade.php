@extends('layouts.app')

@section('title', 'Estadísticas | ElectroShop')

@section('styles')
    @vite(['resources/css/admin.css', 'resources/css/StatsAdmin.css'])
    <style>
        /* Specific overwrites for charts to inherit tailwind sizing nicely */
        .stats-admin-chart-stage { min-height: 300px; width: 100%; }
        .stats-admin-chart-stage-large { min-height: 400px; }
        
        /* ApexCharts customization to look more like the tailwind UI */
        .apexcharts-tooltip { @apply rounded-xl shadow-lg border-slate-100 bg-white/90 backdrop-blur !important; }
        .apexcharts-tooltip-title { @apply bg-slate-50 border-b border-slate-100 font-bold text-slate-700 font-sans text-xs uppercase tracking-widest px-3 py-2 !important; }
        .apexcharts-tooltip-text { @apply font-medium text-slate-600 font-sans text-sm !important; }
        .apexcharts-menu { @apply rounded-xl shadow-lg border-slate-100 bg-white p-1 !important; }
        .apexcharts-menu-item { @apply rounded-lg font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors text-xs px-3 py-2 !important; }
        
        /* Stats specific classes used by JS */
        .stats-admin-chip.is-active { @apply bg-white text-blue-600 shadow-sm; }
        .stats-admin-metric-card { @apply bg-white border border-slate-200 rounded-2xl p-4 flex flex-col justify-between; }
        .stats-admin-metric-card .metric-label { @apply text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block; }
        .stats-admin-metric-card .metric-value { @apply text-2xl font-black text-slate-900 leading-none; }
        .stats-admin-metric-card .metric-trend { @apply text-xs font-bold mt-2 flex items-center gap-1; }
        .stats-admin-metric-card .metric-trend.is-positive { @apply text-emerald-500; }
        .stats-admin-metric-card .metric-trend.is-negative { @apply text-red-500; }
        .stats-admin-metric-card .metric-trend.is-neutral { @apply text-slate-400; }
    </style>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 font-sans selection:bg-blue-100 selection:text-blue-900 pb-12">
    <header class="bg-white border-b border-slate-200 px-6 py-8 md:px-12 md:py-10 mb-8 shadow-sm">
        <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition-colors mb-4 gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al dashboard
                </a>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-1">Estadísticas</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight uppercase italic leading-none">Reportes del <span class="text-slate-400">panel</span></h1>
                <p class="mt-3 text-sm font-medium text-slate-500 max-w-md">Visualiza clientes, pedidos y facturación con paneles consistentes y lectura más clara.</p>
                <div class="flex gap-2 mt-4">
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-indigo-100">gráficos + tablas</span>
                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">exportación csv</span>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex flex-col items-center"><span class="text-lg leading-none">{{ count($ClientesStats) }}</span> clientes</span>
                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex flex-col items-center"><span class="text-lg leading-none">{{ count($PedidosStats) }}</span> pedidos</span>
            </div>
        </div>
    </header>

    <div class="max-w-[1600px] mx-auto px-4 md:px-8 space-y-8">
        
        <!-- Toolbar -->
        <section class="bg-white rounded-3xl border border-slate-100 p-4 lg:p-6 shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 sticky top-6 z-20">
            <div class="flex flex-wrap items-center gap-6 lg:gap-8">
                <!-- Period -->
                <div class="flex flex-col gap-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Periodo</span>
                    <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200" id="StatsPeriodFilters">
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all is-active" data-period="day">Día</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-period="week">Semana</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-period="month">Mes</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-period="year">Año</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-period="all">Todo</button>
                    </div>
                </div>

                <!-- Granularity -->
                <div class="flex flex-col gap-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Agrupar</span>
                    <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200" id="StatsGranularityFilters">
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all is-active" data-granularity="auto">Auto</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-granularity="day">Día</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-granularity="month">Mes</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-granularity="year">Año</button>
                    </div>
                </div>

                <!-- View -->
                <div class="flex flex-col gap-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Vista</span>
                    <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200" id="StatsViewFilters">
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all is-active" data-view="both">Todo</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-view="charts">Gráficos</button>
                        <button type="button" class="stats-admin-chip px-4 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900 transition-all" data-view="tables">Tablas</button>
                    </div>
                </div>
            </div>

            <!-- Export Actions -->
            <div class="flex flex-wrap lg:flex-nowrap gap-2 self-stretch lg:self-end mt-4 lg:mt-0 pt-4 lg:pt-0 border-t border-slate-100 lg:border-t-0">
                <button type="button" class="flex-1 lg:flex-none bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200 flex items-center justify-center gap-2" id="ExportSummaryBtn">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Resumen
                </button>
                <button type="button" class="flex-1 lg:flex-none bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200 flex items-center justify-center gap-2" id="ExportCustomersBtn">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Clientes
                </button>
                <button type="button" class="flex-1 lg:flex-none bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-colors border border-slate-200 flex items-center justify-center gap-2" id="ExportOrdersBtn">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Pedidos
                </button>
            </div>
        </section>

        <!-- Dynamic KPIs Header -->
        <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4" id="GeneralSummaryCards"></section>

        <!-- Main Dashboard Area -->
        <section class="grid xl:grid-cols-3 gap-8 stats-admin-view-panel" data-panel-type="chart">
            <!-- Timeline Chart -->
            <article class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex flex-col">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Gráfico principal</p>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight" id="TimelineChartTitle">Ventas</h2>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200 hidden sm:flex" id="TimelineScopeFilters">
                            <button type="button" class="stats-admin-chip px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-slate-500 hover:text-slate-900 transition-all is-active" data-scope="revenue">Ventas</button>
                            <button type="button" class="stats-admin-chip px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-slate-500 hover:text-slate-900 transition-all" data-scope="orders">Pedidos</button>
                            <button type="button" class="stats-admin-chip px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-slate-500 hover:text-slate-900 transition-all" data-scope="customers">Clientes</button>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-900 transition-colors" data-toggle-panel="MainChartPanel" aria-expanded="true" aria-label="Ocultar gráfico principal">
                            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-hidden transition-all duration-300 origin-top" id="MainChartPanel">
                    <div class="h-full flex flex-col">
                        <div class="flex justify-between items-end mb-4 px-2">
                            <strong class="text-sm font-black text-slate-800" id="TimelineChartHeading">Serie</strong>
                            <span class="text-xs font-medium text-slate-400" id="TimelineChartCaption">0 puntos</span>
                        </div>
                        <div class="flex-1 min-h-[400px] w-full" id="TimelineChart"></div>
                        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                            <span class="text-xs font-medium text-slate-400" id="TimelineDescription">Datos reales de la base.</span>
                            <div class="flex flex-wrap gap-4 text-xs font-bold text-slate-600" id="TimelineLegend"></div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Secondary Charts Sidebar -->
            <div class="xl:col-span-1 flex flex-col gap-8">
                <!-- Top Customers -->
                <article class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex-1">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Clientes</p>
                            <h2 class="text-lg font-black text-slate-900 tracking-tight">Top clientes</h2>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-900 transition-colors" data-toggle-panel="TopCustomersPanel" aria-expanded="true" aria-label="Ocultar top clientes">
                            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>

                    <div class="overflow-hidden transition-all duration-300 origin-top" id="TopCustomersPanel">
                        <div>
                            <div class="flex justify-between items-end mb-4 px-2">
                                <strong class="text-sm font-black text-slate-800" id="TopCustomersChartTitle">Por gasto</strong>
                                <span class="text-xs font-medium text-slate-400" id="TopCustomersChartCaption">0 clientes</span>
                            </div>
                            <div class="min-h-[250px] w-full" id="TopCustomersChart"></div>
                        </div>
                    </div>
                </article>

                <!-- Order Statuses -->
                <article class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex-1">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Pedidos</p>
                            <h2 class="text-lg font-black text-slate-900 tracking-tight">Estados</h2>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-900 transition-colors" data-toggle-panel="StatusPanel" aria-expanded="true" aria-label="Ocultar estados">
                            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>

                    <div class="overflow-hidden transition-all duration-300 origin-top" id="StatusPanel">
                        <div>
                            <div class="flex justify-between items-end mb-4 px-2">
                                <strong class="text-sm font-black text-slate-800" id="StatusChartTitle">Distribución</strong>
                                <span class="text-xs font-medium text-slate-400" id="StatusChartCaption">0 estados</span>
                            </div>
                            <div class="min-h-[250px] w-full flex justify-center" id="StatusChart"></div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Detailed Grids Area -->
        <section class="grid xl:grid-cols-2 gap-8">
            <!-- Customers Detail -->
            <article class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Clientes</p>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Registros</h2>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-900 transition-colors" data-toggle-panel="CustomersPanel" aria-expanded="true" aria-label="Ocultar clientes">
                        <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <div class="overflow-hidden transition-all duration-300 origin-top" id="CustomersPanel">
                    <div class="grid grid-cols-2 gap-4 mb-8" id="CustomerSummaryCards"></div>

                    <div class="stats-admin-view-panel mb-8" data-panel-type="chart">
                        <div class="flex justify-between items-end mb-4 px-2 border-t border-slate-100 pt-6">
                            <strong class="text-sm font-black text-slate-800" id="CustomerChartTitle">Altas</strong>
                            <span class="text-xs font-medium text-slate-400" id="CustomerChartCaption">0 puntos</span>
                        </div>
                        <div class="min-h-[250px] w-full" id="CustomerChart"></div>
                    </div>

                    <div class="stats-admin-view-panel" data-panel-type="table">
                        <div class="flex justify-between items-end mb-4 px-2 border-t border-slate-100 pt-6">
                            <strong class="text-sm font-black text-slate-800" id="CustomerTableTitle">Clientes del periodo</strong>
                            <span class="text-xs font-medium text-slate-400" id="CustomerTableCount">0 registros</span>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-slate-100">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 whitespace-nowrap">Cliente</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Correo</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-center">Pedidos</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-right">Gastado</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-right">Registro</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700 bg-white" id="CustomerTableRows"></tbody>
                            </table>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl p-6 text-center text-sm font-medium hidden mt-4" id="CustomerEmpty" aria-hidden="true"></div>
                    </div>
                </div>
            </article>

            <!-- Orders Detail -->
            <article class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Ventas</p>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Facturación</h2>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-900 transition-colors" data-toggle-panel="OrdersPanel" aria-expanded="true" aria-label="Ocultar ventas">
                        <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <div class="overflow-hidden transition-all duration-300 origin-top" id="OrdersPanel">
                    <div class="grid grid-cols-2 gap-4 mb-8" id="OrderSummaryCards"></div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 border-t border-slate-100 pt-6">
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total vendido</span>
                            <strong class="text-lg font-black text-slate-900" id="OrderTotalAmount">S/. 0.00</strong>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Ticket promedio</span>
                            <strong class="text-lg font-black text-slate-900" id="OrderAverageAmount">S/. 0.00</strong>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Unidades</span>
                            <strong class="text-lg font-black text-slate-900" id="OrderItemsSold">0</strong>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Clientes comp.</span>
                            <strong class="text-lg font-black text-slate-900" id="OrderUniqueCustomers">0</strong>
                        </div>
                    </div>

                    <div class="stats-admin-view-panel mb-8" data-panel-type="chart">
                        <div class="flex justify-between items-end mb-4 px-2 border-t border-slate-100 pt-6">
                            <strong class="text-sm font-black text-slate-800" id="OrderChartTitle">Facturación</strong>
                            <span class="text-xs font-medium text-slate-400" id="OrderChartCaption">0 puntos</span>
                        </div>
                        <div class="min-h-[250px] w-full" id="OrderChart"></div>
                    </div>

                    <div class="stats-admin-view-panel" data-panel-type="table">
                        <div class="flex justify-between items-end mb-4 px-2 border-t border-slate-100 pt-6">
                            <strong class="text-sm font-black text-slate-800" id="OrderTableTitle">Pedidos del periodo</strong>
                            <span class="text-xs font-medium text-slate-400" id="OrderTableCount">0 registros</span>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-slate-100">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 whitespace-nowrap">Código</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Cliente</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-right">Total</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-center">Estado</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-center">Items</th>
                                        <th class="px-4 py-3 whitespace-nowrap text-right">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700 bg-white" id="OrderTableRows"></tbody>
                            </table>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl p-6 text-center text-sm font-medium hidden mt-4" id="OrderEmpty" aria-hidden="true"></div>
                    </div>
                </div>
            </article>
        </section>
    </div>
</div>

<script type="application/json" id="CustomersStatsData">@json($ClientesStats)</script>
<script type="application/json" id="OrdersStatsData">@json($PedidosStats)</script>
@endsection

@section('scripts')
    @vite(['resources/js/StatsAdmin.js'])
    <script>
        // Inline patch for the dynamic toggle panel animation
        document.addEventListener('DOMContentLoaded', () => {
            const toggles = document.querySelectorAll('[data-toggle-panel]');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-toggle-panel');
                    const panel = document.getElementById(targetId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    const icon = this.querySelector('svg');
                    
                    if(isExpanded) {
                        this.setAttribute('aria-expanded', 'false');
                        panel.style.maxHeight = '0px';
                        panel.style.opacity = '0';
                        if(icon) icon.classList.add('rotate-180');
                    } else {
                        this.setAttribute('aria-expanded', 'true');
                        panel.style.maxHeight = '2000px';
                        panel.style.opacity = '1';
                        if(icon) icon.classList.remove('rotate-180');
                    }
                });
            });
        });
    </script>
@endsection
