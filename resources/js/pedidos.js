const PedidoStorageKey = 'electroshop-orders';

// bloque inicio
document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('PedidosList')) {
        return;
    }

    RenderPedidos();
});

// bloque lista
function RenderPedidos() {
    const Pedidos = GetPedidos();
    const List = document.getElementById('PedidosList');
    const Empty = document.getElementById('PedidosEmpty');
    const Count = document.getElementById('PedidosCount');

    if (!List || !Empty || !Count) {
        return;
    }

    Count.textContent = `${Pedidos.length} pedidos`;

    if (!Pedidos.length) {
        List.innerHTML = '';
        Empty.classList.remove('hidden');
        return;
    }

    Empty.classList.add('hidden');
    List.innerHTML = Pedidos.map((Pedido) => `
        <article class="PedidosCard" data-pedido-id="${Pedido.id}">
            <div class="PedidosCardTop">
                <div>
                    <p class="PedidosCode">${EscapeHtml(Pedido.codigo)}</p>
                    <p class="mt-2 text-[12px] font-bold text-slate-500">${EscapeHtml(Pedido.fecha)}</p>
                </div>
                <span class="PedidosState" data-state="${EscapeHtml(Pedido.estadoPedido)}">${EscapeHtml(Pedido.estadoPedido)}</span>
            </div>

            <div class="PedidosMeta">
                <div class="PedidosMetaItem">
                    <span>total</span>
                    <strong>${EscapeHtml(Pedido.total)}</strong>
                </div>
                <div class="PedidosMetaItem">
                    <span>pago</span>
                    <strong>${EscapeHtml(Pedido.metodoPago)} · ${EscapeHtml(Pedido.estadoPago)}</strong>
                </div>
            </div>

            <div class="PedidosItems">
                ${Pedido.items.map((Item) => `
                    <div class="PedidosItem">
                        <img src="${EscapeHtml(Item.image || '')}" alt="${EscapeHtml(Item.name || 'Producto')}">
                        <div class="PedidosItemCopy">
                            <h3>${EscapeHtml(Item.name || 'Producto')}</h3>
                            <p>cantidad: ${Number(Item.qty) || 0}${Item.sku ? ` · sku: ${EscapeHtml(Item.sku)}` : ''}</p>
                        </div>
                    </div>
                `).join('')}
            </div>

            <div class="PedidosActions">
                <button
                    type="button"
                    class="PedidosCancelButton"
                    onclick="CancelPedido('${Pedido.id}')"
                    ${Pedido.estadoPedido === 'Cancelado' ? 'disabled' : ''}
                >
                    ${Pedido.estadoPedido === 'Cancelado' ? 'Pedido cancelado' : 'Cancelar pedido'}
                </button>
            </div>
        </article>
    `).join('');
}

// bloque acciones
window.CancelPedido = function(PedidoId) {
    const Pedidos = GetPedidos();
    const Pedido = Pedidos.find((Item) => String(Item.id) === String(PedidoId));

    if (!Pedido || Pedido.estadoPedido === 'Cancelado') {
        return;
    }

    Pedido.estadoPedido = 'Cancelado';
    Pedido.estadoPago = 'Rechazado';

    localStorage.setItem(PedidoStorageKey, JSON.stringify(Pedidos));
    RenderPedidos();
};

// bloque ayuda
function GetPedidos() {
    try {
        const Pedidos = JSON.parse(localStorage.getItem(PedidoStorageKey) || '[]');
        return Array.isArray(Pedidos) ? Pedidos : [];
    } catch {
        return [];
    }
}

function EscapeHtml(Value) {
    return String(Value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
