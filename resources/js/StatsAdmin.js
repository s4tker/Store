const customersStats = JSON.parse(document.getElementById('CustomersStatsData')?.textContent || '[]');
const orderStorageKey = 'electroshop-orders';
const periodLabels = {
    day: 'día',
    week: 'semana',
    month: 'mes',
    year: 'año',
    all: 'todo el historial',
};

let currentPeriod = 'day';

document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('StatsPeriodFilters')) {
        return;
    }

    bindPeriodFilters();
    bindExportButtons();
    renderStatistics();
});

function bindPeriodFilters() {
    document.querySelectorAll('[data-period]').forEach((button) => {
        button.addEventListener('click', () => {
            currentPeriod = button.dataset.period || 'day';
            document.querySelectorAll('[data-period]').forEach((item) => item.classList.toggle('is-active', item === button));
            renderStatistics();
        });
    });
}

function bindExportButtons() {
    document.getElementById('ExportCustomersBtn')?.addEventListener('click', () => {
        const rows = filterByPeriod(normalizeCustomers(customersStats), currentPeriod);
        exportCsv(`clientes-${currentPeriod}.csv`, [
            ['Nombre', 'Correo', 'Alias', 'Fecha registro'],
            ...rows.map((item) => [item.nombre, item.correo, item.alias || '', formatDateTime(item.date)]),
        ]);
    });

    document.getElementById('ExportOrdersBtn')?.addEventListener('click', () => {
        const rows = filterByPeriod(normalizeOrders(getOrders()), currentPeriod);
        exportCsv(`compras-${currentPeriod}.csv`, [
            ['Codigo', 'Total', 'Estado pedido', 'Estado pago', 'Metodo', 'Fecha'],
            ...rows.map((item) => [item.codigo, item.totalText, item.estadoPedido, item.estadoPago, item.metodoPago, formatDateTime(item.date)]),
        ]);
    });
}

function renderStatistics() {
    const customers = normalizeCustomers(customersStats);
    const orders = normalizeOrders(getOrders());

    renderSummaryCards('CustomerSummaryCards', customers, false);
    renderSummaryCards('OrderSummaryCards', orders, true);
    renderCustomersTable(filterByPeriod(customers, currentPeriod));
    renderOrdersTable(filterByPeriod(orders, currentPeriod));
}

function renderSummaryCards(targetId, items, isOrder) {
    const target = document.getElementById(targetId);

    if (!target) {
        return;
    }

    const periods = ['day', 'week', 'month', 'year', 'all'];
    target.innerHTML = periods.map((period) => {
        const filtered = filterByPeriod(items, period);
        const count = filtered.length;
        const total = isOrder ? sumOrderTotals(filtered) : null;

        return `
            <article class="stats-admin-summary-card">
                <em>${periodLabels[period]}</em>
                <strong>${count}</strong>
                <span>${isOrder ? `${formatMoney(total)} acumulado` : 'registros'}</span>
            </article>
        `;
    }).join('');
}

function renderCustomersTable(items) {
    const rows = document.getElementById('CustomerTableRows');
    const empty = document.getElementById('CustomerEmpty');
    const count = document.getElementById('CustomerTableCount');
    const title = document.getElementById('CustomerTableTitle');

    if (!rows || !empty || !count || !title) {
        return;
    }

    title.textContent = `Clientes por ${periodLabels[currentPeriod]}`;
    count.textContent = `${items.length} registro(s)`;

    if (!items.length) {
        rows.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    rows.innerHTML = items
        .sort((a, b) => b.date - a.date)
        .slice(0, 12)
        .map((item) => `
            <tr>
                <td><strong>${escapeHtml(item.nombre)}</strong></td>
                <td>${escapeHtml(item.correo || '-')}</td>
                <td>${formatDateTime(item.date)}</td>
            </tr>
        `)
        .join('');
}

function renderOrdersTable(items) {
    const rows = document.getElementById('OrderTableRows');
    const empty = document.getElementById('OrderEmpty');
    const count = document.getElementById('OrderTableCount');
    const title = document.getElementById('OrderTableTitle');
    const totalAmount = document.getElementById('OrderTotalAmount');
    const averageAmount = document.getElementById('OrderAverageAmount');

    if (!rows || !empty || !count || !title || !totalAmount || !averageAmount) {
        return;
    }

    title.textContent = `Compras por ${periodLabels[currentPeriod]}`;
    count.textContent = `${items.length} registro(s)`;

    const total = sumOrderTotals(items);
    totalAmount.textContent = formatMoney(total);
    averageAmount.textContent = formatMoney(items.length ? total / items.length : 0);

    if (!items.length) {
        rows.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    rows.innerHTML = items
        .sort((a, b) => b.date - a.date)
        .slice(0, 12)
        .map((item) => `
            <tr>
                <td><strong>${escapeHtml(item.codigo || '-')}</strong></td>
                <td>${escapeHtml(item.totalText)}</td>
                <td>${escapeHtml(item.estadoPedido || '-')}</td>
                <td>${formatDateTime(item.date)}</td>
            </tr>
        `)
        .join('');
}

function normalizeCustomers(items) {
    return items
        .map((item) => ({
            ...item,
            date: parseDate(item.creado_en),
        }))
        .filter((item) => item.date instanceof Date && !Number.isNaN(item.date.getTime()));
}

function normalizeOrders(items) {
    return items
        .map((item) => {
            const date = parseOrderDate(item);
            const totalValue = parseMoney(item.total);

            return {
                ...item,
                date,
                totalValue,
                totalText: item.total || formatMoney(totalValue),
            };
        })
        .filter((item) => item.date instanceof Date && !Number.isNaN(item.date.getTime()));
}

function filterByPeriod(items, period) {
    if (period === 'all') {
        return [...items];
    }

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

    if (period === 'month') {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    return new Date(date.getFullYear(), 0, 1);
}

function getOrders() {
    try {
        const orders = JSON.parse(localStorage.getItem(orderStorageKey) || '[]');
        return Array.isArray(orders) ? orders : [];
    } catch {
        return [];
    }
}

function parseOrderDate(order) {
    if (order.createdAtIso) {
        return parseDate(order.createdAtIso);
    }

    if (typeof order.fecha === 'string') {
        const parsed = new Date(order.fecha);
        if (!Number.isNaN(parsed.getTime())) {
            return parsed;
        }

        const localeMatch = order.fecha.match(/(\d{1,2})\/(\d{1,2})\/(\d{4}),?\s+(\d{1,2}):(\d{2}):?(\d{2})?/);
        if (localeMatch) {
            const [, day, month, year, hour, minute, second = '0'] = localeMatch;
            return new Date(Number(year), Number(month) - 1, Number(day), Number(hour), Number(minute), Number(second));
        }
    }

    return null;
}

function parseDate(value) {
    const parsed = new Date(value);
    return Number.isNaN(parsed.getTime()) ? null : parsed;
}

function parseMoney(value) {
    const normalized = String(value || '0').replace(/[^\d.,-]/g, '').replace(',', '.');
    const amount = Number.parseFloat(normalized);
    return Number.isFinite(amount) ? amount : 0;
}

function sumOrderTotals(items) {
    return items.reduce((sum, item) => sum + (item.totalValue || 0), 0);
}

function formatMoney(value) {
    return `S/. ${Number(value || 0).toFixed(2)}`;
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
