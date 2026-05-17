const customersStats = JSON.parse(document.getElementById('CustomersStatsData')?.textContent || '[]');
const ordersStats = JSON.parse(document.getElementById('OrdersStatsData')?.textContent || '[]');

const periodLabels = {
    day: 'día',
    week: 'semana',
    month: 'mes',
    year: 'año',
    all: 'histórico',
};

const scopeLabels = {
    revenue: 'Ventas',
    orders: 'Pedidos',
    customers: 'Clientes',
};

const state = {
    currentPeriod: 'day',
    currentGranularity: 'auto',
    currentView: 'both',
    currentTimelineScope: 'revenue',
};

document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('StatsPeriodFilters')) {
        return;
    }

    bindButtonGroup('[data-period]', 'currentPeriod');
    bindButtonGroup('[data-granularity]', 'currentGranularity');
    bindButtonGroup('[data-view]', 'currentView', applyViewMode);
    bindButtonGroup('[data-scope]', 'currentTimelineScope');
    bindPanelToggles();
    bindExportButtons();
    renderStatistics();
    applyViewMode();
});

function bindButtonGroup(selector, stateKey, afterUpdate) {
    document.querySelectorAll(selector).forEach((button) => {
        button.addEventListener('click', () => {
            const datasetKey = Object.keys(button.dataset).find((key) => key !== 'panelType');
            state[stateKey] = button.dataset[datasetKey] || state[stateKey];

            document.querySelectorAll(selector).forEach((item) => {
                item.classList.toggle('is-active', item === button);
            });

            if (typeof afterUpdate === 'function') {
                afterUpdate();
            }

            renderStatistics();
        });
    });
}

function bindExportButtons() {
    document.getElementById('ExportSummaryBtn')?.addEventListener('click', () => {
        const dataset = getCurrentDataset();
        const series = buildMainSeries(dataset);

        exportCsv(`resumen-${state.currentPeriod}.csv`, [
            ['Periodo', 'Agrupacion', 'Clientes', 'Pedidos', 'Ingresos', 'Ticket promedio', 'Items vendidos'],
            [
                periodLabels[state.currentPeriod],
                granularityLabel(dataset.granularity),
                dataset.customers.length,
                dataset.orders.length,
                formatMoneyNumber(sumOrderTotals(dataset.orders)),
                formatMoneyNumber(dataset.orders.length ? sumOrderTotals(dataset.orders) / dataset.orders.length : 0),
                sumOrderItems(dataset.orders),
            ],
            [],
            ['Etiqueta', 'Valor'],
            ...series.map((point) => [point.label, point.value]),
        ]);
    });

    document.getElementById('ExportCustomersBtn')?.addEventListener('click', () => {
        const dataset = getCurrentDataset();
        const rows = buildCustomerPeriodRows(dataset.allCustomers, dataset.customers, dataset.orders);

        exportCsv(`clientes-${state.currentPeriod}.csv`, [
            ['Nombre', 'Correo', 'Alias', 'Pedidos', 'Gastado', 'Registro', 'Ultimo pedido'],
            ...rows.map((item) => [
                item.nombre,
                item.correo,
                item.alias || '',
                item.periodOrders,
                formatMoneyNumber(item.periodSpent),
                formatDateTime(item.date),
                item.latestOrderDate ? formatDateTime(item.latestOrderDate) : '',
            ]),
        ]);
    });

    document.getElementById('ExportOrdersBtn')?.addEventListener('click', () => {
        const dataset = getCurrentDataset();

        exportCsv(`pedidos-${state.currentPeriod}.csv`, [
            ['Codigo', 'Cliente', 'Correo', 'Total', 'Estado', 'Items', 'Fecha'],
            ...dataset.orders.map((item) => [
                item.codigo,
                item.customerName,
                item.customerEmail || '',
                formatMoneyNumber(item.totalValue),
                item.estadoPedido,
                item.itemsCount,
                formatDateTime(item.date),
            ]),
        ]);
    });
}

function bindPanelToggles() {
    document.querySelectorAll('[data-toggle-panel]').forEach((button) => {
        button.addEventListener('click', () => {
            const panelId = button.getAttribute('data-toggle-panel');
            const panel = panelId ? document.getElementById(panelId) : null;

            if (!panel) {
                return;
            }

            const collapsed = panel.classList.toggle('is-collapsed');
            const card = button.closest('.admin-panel');
            const header = card?.querySelector('.stats-admin-panel-head');

            card?.classList.toggle('stats-admin-card-collapsed', collapsed);
            header?.classList.toggle('stats-admin-head-collapsed', collapsed);
            button.classList.toggle('is-collapsed', collapsed);
            button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            button.textContent = collapsed ? 'Mostrar' : 'Ocultar';
        });
    });
}

function renderStatistics() {
    const dataset = getCurrentDataset();

    renderGeneralSummary(dataset);
    renderMainChart(dataset);
    renderTopCustomersChart(dataset);
    renderStatusChart(dataset);
    renderCustomerSummary(dataset);
    renderOrderSummary(dataset);
    renderCustomersTable(dataset);
    renderOrdersTable(dataset);
    renderCustomerChart(dataset);
    renderOrderChart(dataset);
}

function getCurrentDataset() {
    const allCustomers = normalizeCustomers(customersStats);
    const allOrders = normalizeOrders(ordersStats);
    const customers = filterByPeriod(allCustomers, state.currentPeriod);
    const orders = filterByPeriod(allOrders, state.currentPeriod);

    return {
        allCustomers,
        customers,
        orders,
        granularity: resolveGranularity(state.currentGranularity, state.currentPeriod, customers, orders),
    };
}

function renderGeneralSummary({ customers, orders }) {
    const target = document.getElementById('GeneralSummaryCards');
    if (!target) return;

    const total = sumOrderTotals(orders);
    const avg = orders.length ? total / orders.length : 0;
    const buyers = new Set(orders.map((item) => item.customerId).filter(Boolean)).size;

    target.innerHTML = [
        buildSummaryCard('Clientes', customers.length, `Nuevos en ${periodLabels[state.currentPeriod]}`),
        buildSummaryCard('Pedidos', orders.length, `${buyers} compradores`),
        buildSummaryCard('Ingresos', formatMoney(total), `${sumOrderItems(orders)} unidades`),
        buildSummaryCard('Ticket', formatMoney(avg), granularityLabel(resolveGranularity(state.currentGranularity, state.currentPeriod, customers, orders))),
    ].join('');
}

function renderCustomerSummary({ allCustomers, customers, orders }) {
    const target = document.getElementById('CustomerSummaryCards');
    if (!target) return;

    const rows = buildCustomerPeriodRows(allCustomers, customers, orders);
    const top = [...rows].sort((a, b) => b.periodSpent - a.periodSpent)[0];
    const repeat = Array.from(groupOrdersByCustomer(orders).values()).filter((items) => items.length > 1).length;

    target.innerHTML = [
        buildSummaryCard('Nuevos', customers.length, 'Registrados'),
        buildSummaryCard('Compradores', groupOrdersByCustomer(orders).size, `${repeat} recurrentes`),
        buildSummaryCard('Top', top ? top.nombre : '-', top ? formatMoney(top.periodSpent) : 'S/. 0.00'),
    ].join('');
}

function renderOrderSummary({ customers, orders }) {
    const target = document.getElementById('OrderSummaryCards');
    const totalAmount = document.getElementById('OrderTotalAmount');
    const averageAmount = document.getElementById('OrderAverageAmount');
    const itemsSold = document.getElementById('OrderItemsSold');
    const uniqueCustomers = document.getElementById('OrderUniqueCustomers');
    if (!target || !totalAmount || !averageAmount || !itemsSold || !uniqueCustomers) return;

    const total = sumOrderTotals(orders);
    const items = sumOrderItems(orders);
    const states = countOrdersByStatus(orders);
    const topState = Object.entries(states).sort((a, b) => b[1] - a[1])[0];

    totalAmount.textContent = formatMoney(total);
    averageAmount.textContent = formatMoney(orders.length ? total / orders.length : 0);
    itemsSold.textContent = String(items);
    uniqueCustomers.textContent = String(new Set(orders.map((item) => item.customerId).filter(Boolean)).size);

    target.innerHTML = [
        buildSummaryCard('Pedidos', orders.length, `${customers.length} clientes`),
        buildSummaryCard('Estado', topState ? topState[0] : '-', topState ? `${topState[1]} registros` : 'Sin datos'),
        buildSummaryCard('Por unidad', formatMoney(items ? total / items : 0), 'Promedio'),
    ].join('');
}

function renderCustomersTable({ allCustomers, customers, orders }) {
    const rows = document.getElementById('CustomerTableRows');
    const empty = document.getElementById('CustomerEmpty');
    const count = document.getElementById('CustomerTableCount');
    const title = document.getElementById('CustomerTableTitle');
    if (!rows || !empty || !count || !title) return;

    const data = buildCustomerPeriodRows(allCustomers, customers, orders)
        .sort((a, b) => (b.periodSpent - a.periodSpent) || (b.date - a.date))
        .slice(0, 15);

    title.textContent = `Clientes por ${periodLabels[state.currentPeriod]}`;
    count.textContent = `${data.length} registros`;

    if (!data.length) {
        rows.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    rows.innerHTML = data.map((item) => `
        <tr>
            <td><strong>${escapeHtml(item.nombre)}</strong></td>
            <td>${escapeHtml(item.correo || '-')}</td>
            <td>${item.periodOrders}</td>
            <td>${formatMoney(item.periodSpent)}</td>
            <td>${formatDateTime(item.date)}</td>
        </tr>
    `).join('');
}

function renderOrdersTable({ orders }) {
    const rows = document.getElementById('OrderTableRows');
    const empty = document.getElementById('OrderEmpty');
    const count = document.getElementById('OrderTableCount');
    const title = document.getElementById('OrderTableTitle');
    if (!rows || !empty || !count || !title) return;

    const data = [...orders].sort((a, b) => b.date - a.date).slice(0, 15);
    title.textContent = `Pedidos por ${periodLabels[state.currentPeriod]}`;
    count.textContent = `${data.length} registros`;

    if (!data.length) {
        rows.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    rows.innerHTML = data.map((item) => `
        <tr>
            <td><strong>${escapeHtml(item.codigo)}</strong></td>
            <td>${escapeHtml(item.customerName)}</td>
            <td>${formatMoney(item.totalValue)}</td>
            <td>${escapeHtml(item.estadoPedido)}</td>
            <td>${item.itemsCount}</td>
            <td>${formatDateTime(item.date)}</td>
        </tr>
    `).join('');
}

function renderMainChart(dataset) {
    const target = document.getElementById('TimelineChart');
    const title = document.getElementById('TimelineChartTitle');
    const heading = document.getElementById('TimelineChartHeading');
    const caption = document.getElementById('TimelineChartCaption');
    const description = document.getElementById('TimelineDescription');
    const legend = document.getElementById('TimelineLegend');
    if (!target || !title || !heading || !caption || !description || !legend) return;

    const series = buildMainSeries(dataset);
    title.textContent = scopeLabels[state.currentTimelineScope];
    heading.textContent = `${scopeLabels[state.currentTimelineScope]} por ${granularityLabel(dataset.granularity).toLowerCase()}`;
    caption.textContent = `${series.length} puntos`;
    description.textContent = `Periodo: ${periodLabels[state.currentPeriod]}`;
    legend.innerHTML = buildLegend([{ label: scopeLabels[state.currentTimelineScope], value: sumSeries(series, state.currentTimelineScope === 'revenue') }]);

    const allZero = series.every((point) => point.value === 0);

    renderLineChart(target, series, state.currentTimelineScope === 'revenue' ? formatMoney : formatInteger);
}

function renderTopCustomersChart({ allCustomers, customers, orders }) {
    const target = document.getElementById('TopCustomersChart');
    const title = document.getElementById('TopCustomersChartTitle');
    const caption = document.getElementById('TopCustomersChartCaption');
    if (!target || !title || !caption) return;

    const rows = buildCustomerPeriodRows(allCustomers, customers, orders)
        .sort((a, b) => b.periodSpent - a.periodSpent)
        .slice(0, 5)
        .map((item) => ({ label: item.nombre, value: item.periodSpent }));

    title.textContent = 'Por gasto';
    caption.textContent = `${rows.length} clientes`;
    renderMetricList(target, ensureCategoryRows(rows, ['Sin datos']), formatMoney, 'blue');
}

function renderStatusChart({ orders }) {
    const target = document.getElementById('StatusChart');
    const title = document.getElementById('StatusChartTitle');
    const caption = document.getElementById('StatusChartCaption');
    if (!target || !title || !caption) return;

    const rows = Object.entries(countOrdersByStatus(orders))
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5)
        .map(([label, value]) => ({ label, value }));

    title.textContent = 'Distribución';
    caption.textContent = `${rows.length} estados`;
    renderMetricList(target, ensureCategoryRows(rows, ['Sin datos']), formatInteger, 'amber');
}

function renderMetricList(target, rows, valueFormatter, tone = 'blue') {
    const max = Math.max(...rows.map((item) => Number(item.value || 0)), 0);
    const safeMax = max > 0 ? max : 1;

    target.innerHTML = `
        <div class="stats-admin-metric-list">
            ${rows.map((item, index) => {
                const percent = Math.max(4, (Number(item.value || 0) / safeMax) * 100);
                const amber = tone === 'amber';
                return `
                    <article class="stats-admin-metric-row">
                        <div class="stats-admin-metric-rank ${amber ? 'is-amber' : ''}">${index + 1}</div>
                        <div class="min-w-0 flex-1">
                            <div class="stats-admin-metric-head">
                                <strong>${escapeHtml(trimLabel(item.label, 28))}</strong>
                                <span>${escapeHtml(valueFormatter(item.value))}</span>
                            </div>
                            <div class="stats-admin-metric-track">
                                <span class="stats-admin-metric-fill ${amber ? 'is-amber' : ''}" style="width: ${percent}%"></span>
                            </div>
                        </div>
                    </article>
                `;
            }).join('')}
        </div>
    `;
}

function renderCustomerChart(dataset) {
    const target = document.getElementById('CustomerChart');
    const title = document.getElementById('CustomerChartTitle');
    const caption = document.getElementById('CustomerChartCaption');
    if (!target || !title || !caption) return;

    const series = ensureTimeSeries(
        aggregateSeries(dataset.customers, dataset.granularity, (item) => item.date, () => 1),
        dataset.granularity,
        state.currentPeriod,
    );

    title.textContent = `Altas por ${granularityLabel(dataset.granularity).toLowerCase()}`;
    caption.textContent = `${series.length} puntos`;
    renderLineChart(target, series, formatInteger, true);
}

function renderOrderChart(dataset) {
    const target = document.getElementById('OrderChart');
    const title = document.getElementById('OrderChartTitle');
    const caption = document.getElementById('OrderChartCaption');
    if (!target || !title || !caption) return;

    const series = ensureTimeSeries(
        aggregateSeries(dataset.orders, dataset.granularity, (item) => item.date, (item) => item.totalValue),
        dataset.granularity,
        state.currentPeriod,
    );

    title.textContent = `Facturación por ${granularityLabel(dataset.granularity).toLowerCase()}`;
    caption.textContent = `${series.length} puntos`;
    renderLineChart(target, series, formatMoney, true);
}

function applyViewMode() {
    document.querySelectorAll('.stats-admin-view-panel').forEach((panel) => {
        const type = panel.dataset.panelType;
        const visible = state.currentView === 'both'
            || (state.currentView === 'charts' && type === 'chart')
            || (state.currentView === 'tables' && type === 'table');
        panel.classList.toggle('hidden', !visible);
    });
}

function normalizeCustomers(items) {
    return items
        .map((item) => ({
            id: item.id,
            nombre: item.nombre || 'Cliente sin nombre',
            correo: item.correo || '',
            alias: item.alias || '',
            date: parseDate(item.creado_en),
            latestOrderDate: parseDate(item.ultimo_pedido_en),
        }))
        .filter((item) => item.date instanceof Date && !Number.isNaN(item.date.getTime()));
}

function normalizeOrders(items) {
    return items
        .map((item) => ({
            id: item.id,
            codigo: item.codigo || `PED-${item.id}`,
            customerId: item.cliente_id,
            customerName: item.cliente_nombre || 'Cliente sin nombre',
            customerEmail: item.cliente_correo || '',
            estadoPedido: item.estado_pedido || 'Pendiente',
            totalValue: Number(item.total || 0),
            itemsCount: Number(item.items_count || 0),
            date: parseDate(item.creado_en),
        }))
        .filter((item) => item.date instanceof Date && !Number.isNaN(item.date.getTime()));
}

function filterByPeriod(items, period) {
    if (period === 'all') return [...items];
    const now = new Date();
    const start = getPeriodStart(now, period);
    return items.filter((item) => item.date >= start && item.date <= now);
}

function getPeriodStart(now, period) {
    const date = new Date(now);
    if (period === 'day') {
        date.setHours(0, 0, 0, 0);
        return date;
    }
    if (period === 'week') {
        const day = date.getDay();
        const diff = day === 0 ? 6 : day - 1;
        date.setDate(date.getDate() - diff);
        date.setHours(0, 0, 0, 0);
        return date;
    }
    if (period === 'month') return new Date(date.getFullYear(), date.getMonth(), 1);
    return new Date(date.getFullYear(), 0, 1);
}

function resolveGranularity(selectedGranularity, period, customers, orders) {
    if (selectedGranularity !== 'auto') return selectedGranularity;
    if (period === 'day') return 'hour';
    if (period === 'week' || period === 'month') return 'day';
    if (period === 'year') return 'month';

    const allDates = [...customers.map((item) => item.date), ...orders.map((item) => item.date)].sort((a, b) => a - b);
    if (!allDates.length) return 'month';
    const earliest = allDates[0];
    const latest = allDates[allDates.length - 1];
    const monthsSpan = (latest.getFullYear() - earliest.getFullYear()) * 12 + (latest.getMonth() - earliest.getMonth());
    return monthsSpan >= 24 ? 'year' : 'month';
}

function granularityLabel(granularity) {
    return { hour: 'Hora', day: 'Día', month: 'Mes', year: 'Año' }[granularity] || 'Periodo';
}

function buildMainSeries({ customers, orders, granularity }) {
    if (state.currentTimelineScope === 'customers') {
        return ensureTimeSeries(aggregateSeries(customers, granularity, (item) => item.date, () => 1), granularity, state.currentPeriod);
    }
    if (state.currentTimelineScope === 'orders') {
        return ensureTimeSeries(aggregateSeries(orders, granularity, (item) => item.date, () => 1), granularity, state.currentPeriod);
    }
    return ensureTimeSeries(aggregateSeries(orders, granularity, (item) => item.date, (item) => item.totalValue), granularity, state.currentPeriod);
}

function aggregateSeries(items, granularity, dateAccessor, valueAccessor) {
    const buckets = new Map();

    items.forEach((item) => {
        const date = dateAccessor(item);
        const key = formatBucketKey(date, granularity);
        const current = buckets.get(key) || {
            key,
            label: formatBucketLabel(date, granularity),
            value: 0,
            date: normalizeBucketDate(date, granularity),
        };

        current.value += Number(valueAccessor(item) || 0);
        buckets.set(key, current);
    });

    return Array.from(buckets.values()).sort((a, b) => a.date - b.date);
}

function ensureTimeSeries(series, granularity, period) {
    const skeleton = buildTimeSeriesSkeleton(granularity, period, series);
    const values = new Map(series.map((item) => [item.key, item]));

    return skeleton.map((item) => ({
        ...item,
        value: Number(values.get(item.key)?.value || 0),
    }));
}

function buildEmptyTimeSeries(granularity, period) {
    return buildTimeSeriesSkeleton(granularity, period, []);
}

function buildTimeSeriesSkeleton(granularity, period, series = []) {
    const now = new Date();
    const count = period === 'day' ? 12 : period === 'week' ? 7 : period === 'month' ? 30 : period === 'year' ? 12 : resolveHistoricalPointCount(granularity, series);
    const items = [];

    if (period === 'all' && series.length) {
        const first = normalizeBucketDate(series[0].date, granularity);
        const last = normalizeBucketDate(series[series.length - 1].date, granularity);
        const cursor = new Date(first);

        while (cursor <= last) {
            items.push({
                key: formatBucketKey(cursor, granularity),
                label: formatBucketLabel(cursor, granularity),
                value: 0,
                date: new Date(cursor),
            });
            advanceDate(cursor, granularity, 1);
        }

        return items;
    }

    for (let index = count - 1; index >= 0; index -= 1) {
        const date = new Date(now);
        if (granularity === 'hour') {
            date.setHours(now.getHours() - index, 0, 0, 0);
        } else if (granularity === 'day') {
            date.setDate(now.getDate() - index);
            date.setHours(0, 0, 0, 0);
        } else if (granularity === 'month') {
            date.setMonth(now.getMonth() - index, 1);
            date.setHours(0, 0, 0, 0);
        } else {
            date.setFullYear(now.getFullYear() - index, 0, 1);
            date.setHours(0, 0, 0, 0);
        }

        items.push({
            key: formatBucketKey(date, granularity),
            label: formatBucketLabel(date, granularity),
            value: 0,
            date,
        });
    }

    return items;
}

function resolveHistoricalPointCount(granularity, series) {
    if (granularity === 'year') return Math.max(series.length, 5);
    if (granularity === 'month') return Math.max(series.length, 12);
    if (granularity === 'day') return Math.max(series.length, 30);
    return Math.max(series.length, 12);
}

function advanceDate(date, granularity, amount) {
    if (granularity === 'hour') {
        date.setHours(date.getHours() + amount);
        return;
    }

    if (granularity === 'day') {
        date.setDate(date.getDate() + amount);
        return;
    }

    if (granularity === 'month') {
        date.setMonth(date.getMonth() + amount);
        return;
    }

    date.setFullYear(date.getFullYear() + amount);
}

function ensureCategoryRows(rows, fallbackLabels) {
    if (rows.length) return rows;
    return fallbackLabels.map((label) => ({ label, value: 0 }));
}

function formatBucketKey(date, granularity) {
    if (granularity === 'hour') return `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}-${date.getHours()}`;
    if (granularity === 'day') return `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`;
    if (granularity === 'month') return `${date.getFullYear()}-${date.getMonth()}`;
    return `${date.getFullYear()}`;
}

function normalizeBucketDate(date, granularity) {
    if (granularity === 'hour') return new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours());
    if (granularity === 'day') return new Date(date.getFullYear(), date.getMonth(), date.getDate());
    if (granularity === 'month') return new Date(date.getFullYear(), date.getMonth(), 1);
    return new Date(date.getFullYear(), 0, 1);
}

function formatBucketLabel(date, granularity) {
    if (granularity === 'hour') return new Intl.DateTimeFormat('es-PE', { hour: 'numeric' }).format(date);
    if (granularity === 'day') return new Intl.DateTimeFormat('es-PE', { day: '2-digit', month: 'short' }).format(date);
    if (granularity === 'month') return new Intl.DateTimeFormat('es-PE', { month: 'short', year: 'numeric' }).format(date);
    return String(date.getFullYear());
}

function buildCustomerPeriodRows(allCustomers, periodCustomers, periodOrders) {
    const relevantIds = new Set([
        ...periodCustomers.map((customer) => customer.id),
        ...periodOrders.map((order) => order.customerId).filter(Boolean),
    ]);

    const ordersByCustomer = groupOrdersByCustomer(periodOrders);

    return allCustomers
        .map((customer) => {
            const orders = ordersByCustomer.get(customer.id) || [];
            return {
                ...customer,
                periodOrders: orders.length,
                periodSpent: orders.reduce((sum, item) => sum + item.totalValue, 0),
            };
        })
        .filter((customer) => relevantIds.has(customer.id));
}

function groupOrdersByCustomer(orders) {
    return orders.reduce((map, order) => {
        if (!order.customerId) return map;
        const items = map.get(order.customerId) || [];
        items.push(order);
        map.set(order.customerId, items);
        return map;
    }, new Map());
}

function countOrdersByStatus(orders) {
    return orders.reduce((acc, order) => {
        acc[order.estadoPedido] = (acc[order.estadoPedido] || 0) + 1;
        return acc;
    }, {});
}

function sumOrderTotals(items) {
    return items.reduce((sum, item) => sum + (item.totalValue || 0), 0);
}

function sumOrderItems(items) {
    return items.reduce((sum, item) => sum + (item.itemsCount || 0), 0);
}

function sumSeries(series, money = false) {
    const value = series.reduce((sum, item) => sum + item.value, 0);
    return money ? formatMoney(value) : formatInteger(value);
}

function buildSummaryCard(label, value, note) {
    return `
        <article class="stats-admin-summary-card">
            <em>${escapeHtml(label)}</em>
            <strong>${escapeHtml(String(value))}</strong>
            <span>${escapeHtml(note)}</span>
        </article>
    `;
}

function buildLegend(items) {
    return items.map((item) => `
        <span class="stats-admin-legend-item">
            <span class="stats-admin-legend-dot"></span>
            ${escapeHtml(item.label)}: ${escapeHtml(String(item.value))}
        </span>
    `).join('');
}

function renderColumnChart(target, series, valueFormatter, includeZeroClass = false) {
    const width = 820;
    const height = 300;
    const margin = { top: 28, right: 18, bottom: 54, left: 58 };
    const chartWidth = width - margin.left - margin.right;
    const chartHeight = height - margin.top - margin.bottom;
    const max = Math.max(...series.map((item) => item.value), 0);
    const safeMax = max > 0 ? max : 1;
    const barWidth = Math.min(Math.max((chartWidth / series.length) * 0.46, 18), 44);
    const gap = chartWidth / series.length;

    const bars = series.map((item, index) => {
        const x = margin.left + (gap * index) + ((gap - barWidth) / 2);
        const valueHeight = (item.value / safeMax) * chartHeight;
        const y = margin.top + chartHeight - valueHeight;
        const visibleHeight = Math.max(valueHeight, 4);
        return `
            <g>
                <rect class="stats-chart-track" x="${x}" y="${margin.top}" width="${barWidth}" height="${chartHeight}" rx="${barWidth / 2}"></rect>
                <rect class="${item.value === 0 && includeZeroClass ? 'stats-chart-zero' : 'stats-chart-bar'}" x="${x}" y="${margin.top + chartHeight - visibleHeight}" width="${barWidth}" height="${visibleHeight}" rx="${barWidth / 2}"></rect>
                ${item.value > 0 ? `<text class="stats-chart-value" x="${x + (barWidth / 2)}" y="${Math.max(y - 8, 14)}" text-anchor="middle">${escapeHtml(valueFormatter(item.value))}</text>` : ''}
                <text class="stats-chart-label" x="${x + (barWidth / 2)}" y="${height - 18}" text-anchor="middle">${escapeHtml(item.label)}</text>
            </g>
        `;
    }).join('');

    target.innerHTML = buildAxisChartShell({
        width,
        height,
        margin,
        max: safeMax,
        content: bars,
    });
}

function renderLineChart(target, series, valueFormatter, includeZeroClass = false) {
    const width = 820;
    const height = 300;
    const margin = { top: 28, right: 18, bottom: 54, left: 58 };
    const chartWidth = width - margin.left - margin.right;
    const chartHeight = height - margin.top - margin.bottom;
    const max = Math.max(...series.map((item) => item.value), 0);
    const safeMax = max > 0 ? max : 1;
    const gap = series.length > 1 ? chartWidth / (series.length - 1) : chartWidth / 2;

    const points = series.map((item, index) => {
        const x = margin.left + (series.length > 1 ? gap * index : chartWidth / 2);
        const y = margin.top + chartHeight - ((item.value / safeMax) * chartHeight);
        return { ...item, x, y };
    });

    const line = buildSmoothPath(points);
    const area = `${line} L ${points[points.length - 1].x} ${margin.top + chartHeight} L ${points[0].x} ${margin.top + chartHeight} Z`;

    const content = `
        <path class="stats-chart-area" d="${area}"></path>
        <path class="stats-chart-line" d="${line}"></path>
        ${points.map((point) => `
            <g>
                <circle class="${point.value === 0 && includeZeroClass ? 'stats-chart-zero' : 'stats-chart-point'}" cx="${point.x}" cy="${point.y}" r="4.5"></circle>
                <text class="stats-chart-label" x="${point.x}" y="${height - 18}" text-anchor="middle">${escapeHtml(point.label)}</text>
            </g>
        `).join('')}
    `;

    target.innerHTML = buildAxisChartShell({
        width,
        height,
        margin,
        max: safeMax,
        content,
    });
}

function renderHorizontalBarChart(target, rows, valueFormatter) {
    const width = 520;
    const height = Math.max(210, rows.length * 44 + 36);
    const margin = { top: 16, right: 18, bottom: 16, left: 130 };
    const chartWidth = width - margin.left - margin.right;
    const rowHeight = (height - margin.top - margin.bottom) / rows.length;
    const max = Math.max(...rows.map((item) => item.value), 0);
    const safeMax = max > 0 ? max : 1;

    const content = rows.map((item, index) => {
        const y = margin.top + (rowHeight * index) + 8;
        const barHeight = Math.max(rowHeight - 18, 12);
        const valueWidth = (item.value / safeMax) * chartWidth;

        return `
            <g>
                <text class="stats-chart-label" x="${margin.left - 10}" y="${y + (barHeight / 2) + 4}" text-anchor="end">${escapeHtml(trimLabel(item.label, 20))}</text>
                <rect class="stats-chart-track" x="${margin.left}" y="${y}" width="${chartWidth}" height="${barHeight}" rx="${barHeight / 2}"></rect>
                <rect class="${item.value === 0 ? 'stats-chart-zero' : 'stats-chart-bar-soft'}" x="${margin.left}" y="${y}" width="${Math.max(valueWidth, 3)}" height="${barHeight}" rx="${barHeight / 2}"></rect>
                <text class="stats-chart-value" x="${margin.left + Math.min(valueWidth + 8, chartWidth - 4)}" y="${y + (barHeight / 2) + 4}">${escapeHtml(valueFormatter(item.value))}</text>
            </g>
        `;
    }).join('');

    target.innerHTML = `
        <svg viewBox="0 0 ${width} ${height}" class="stats-admin-chart-svg" role="img" aria-label="Grafico">
            ${buildChartDefs()}
            ${content}
        </svg>
    `;
}

function buildAxisChartShell({ width, height, margin, max, content }) {
    const ySteps = 4;
    const rows = Array.from({ length: ySteps + 1 }, (_, index) => {
        const value = (max / ySteps) * (ySteps - index);
        const y = margin.top + (((height - margin.top - margin.bottom) / ySteps) * index);
        return `
            <g class="stats-chart-grid">
                <line x1="${margin.left}" y1="${y}" x2="${width - margin.right}" y2="${y}"></line>
                <text class="stats-chart-label" x="${margin.left - 8}" y="${y + 4}" text-anchor="end">${escapeHtml(formatCompactNumber(value))}</text>
            </g>
        `;
    }).join('');

    return `
        <svg viewBox="0 0 ${width} ${height}" class="stats-admin-chart-svg" role="img" aria-label="Grafico">
            ${buildChartDefs()}
            ${rows}
            <g class="stats-chart-axis">
                <line x1="${margin.left}" y1="${height - margin.bottom}" x2="${width - margin.right}" y2="${height - margin.bottom}"></line>
            </g>
            ${content}
        </svg>
    `;
}

function buildChartDefs() {
    return `
        <defs>
            <linearGradient id="statsBarGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#0586c9"></stop>
                <stop offset="100%" stop-color="#0586c9"></stop>
            </linearGradient>
            <linearGradient id="statsSoftGradient" x1="0" x2="1" y1="0" y2="0">
                <stop offset="0%" stop-color="#0586c9"></stop>
                <stop offset="100%" stop-color="#35aee2"></stop>
            </linearGradient>
            <linearGradient id="statsLineGradient" x1="0" x2="1" y1="0" y2="0">
                <stop offset="0%" stop-color="#0586c9"></stop>
                <stop offset="100%" stop-color="#0586c9"></stop>
            </linearGradient>
            <linearGradient id="statsAreaGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#0586c9" stop-opacity="0.16"></stop>
                <stop offset="100%" stop-color="#0586c9" stop-opacity="0.03"></stop>
            </linearGradient>
        </defs>
    `;
}

function buildSmoothPath(points) {
    if (!points.length) {
        return '';
    }

    if (points.length === 1) {
        return `M ${points[0].x} ${points[0].y}`;
    }

    return points.reduce((path, point, index) => {
        if (index === 0) {
            return `M ${point.x} ${point.y}`;
        }

        const previous = points[index - 1];
        const controlX = previous.x + ((point.x - previous.x) / 2);
        return `${path} C ${controlX} ${previous.y}, ${controlX} ${point.y}, ${point.x} ${point.y}`;
    }, '');
}

function trimLabel(value, maxLength) {
    return String(value).length > maxLength ? `${String(value).slice(0, maxLength - 1)}…` : String(value);
}

function parseDate(value) {
    const parsed = new Date(value);
    return Number.isNaN(parsed.getTime()) ? null : parsed;
}

function formatMoney(value) {
    return `S/. ${Number(value || 0).toFixed(2)}`;
}

function formatMoneyNumber(value) {
    return Number(value || 0).toFixed(2);
}

function formatInteger(value) {
    return String(Math.round(Number(value || 0)));
}

function formatCompactNumber(value) {
    return Number(value || 0).toLocaleString('es-PE', { maximumFractionDigits: value >= 10 ? 0 : 1 });
}

function formatDateTime(date) {
    return new Intl.DateTimeFormat('es-PE', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(date);
}

function exportCsv(filename, rows) {
    const csvContent = rows
        .map((row) => row.map((cell) => `"${String(cell ?? '').replaceAll('"', '""')}"`).join(','))
        .join('\n');

    const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    URL.revokeObjectURL(url);
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
