const CompraBootstrapNode = document.getElementById('CompraBootstrap');
const CompraBootstrap = CompraBootstrapNode ? JSON.parse(CompraBootstrapNode.textContent || '{}') : {};
const PedidoStorageKey = 'electroshop-orders';

// bloque inicio
document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('CompraForm')) {
        return;
    }

    FillCompraUserData();
    RenderSavedAddresses();
    SyncTipoCliente();
    RenderCompraSummary();
    BindCompraEvents();
});

// bloque carrito
function GetCompraCart() {
    try {
        return JSON.parse(localStorage.getItem('electroshop-cart') || '[]');
    } catch {
        return [];
    }
}

// bloque usuario
function FillCompraUserData() {
    const Usuario = CompraBootstrap.Usuario || {};

    SetInputValue('CompraNombre', Usuario.Nombre || '');
    SetInputValue('CompraApellidos', Usuario.Apellidos || '');
    SetInputValue('CompraCorreo', Usuario.Correo || '');
    SetInputValue('CompraTelefono', Usuario.Telefono || '');
    SetInputValue('CompraDni', Usuario.Dni || '');
    SetInputValue('CompraRuc', Usuario.Ruc || '');
    SetInputValue('CompraRazonSocial', Usuario.RazonSocial || '');
    SetInputValue('CompraPais', 'Perú');
}

// bloque direcciones
function RenderSavedAddresses() {
    const Container = document.getElementById('CompraSavedAddresses');
    const Direcciones = CompraBootstrap.Direcciones || [];

    if (!Container || !Direcciones.length) {
        return;
    }

    Container.innerHTML = Direcciones.map((Direccion) => `
        <button type="button" class="CompraSavedAddress" data-address-id="${Direccion.Id}">
            <strong>${EscapeHtml(`${Direccion.Ciudad || ''} ${Direccion.Region ? `· ${Direccion.Region}` : ''}`.trim() || 'Dirección guardada')}</strong>
            <span>${EscapeHtml([Direccion.Pais, Direccion.Ciudad, Direccion.Direccion, Direccion.Referencia].filter(Boolean).join(' · '))}</span>
        </button>
    `).join('');

    Container.querySelectorAll('[data-address-id]').forEach((Button) => {
        Button.addEventListener('click', () => {
            const Direccion = Direcciones.find((Item) => String(Item.Id) === String(Button.dataset.addressId));

            if (!Direccion) {
                return;
            }

            Container.querySelectorAll('.CompraSavedAddress').forEach((Card) => Card.classList.remove('is-active'));
            Button.classList.add('is-active');

            SetInputValue('CompraPais', Direccion.Pais || '');
            SetInputValue('CompraRegion', Direccion.Region || '');
            SetInputValue('CompraCiudad', Direccion.Ciudad || '');
            SetInputValue('CompraDireccion', Direccion.Direccion || '');
            SetInputValue('CompraReferencia', Direccion.Referencia || '');
        });
    });
}

// bloque empresa
function SyncTipoCliente() {
    const TipoCliente = document.getElementById('CompraTipoCliente');
    const EsEmpresa = TipoCliente?.value === 'empresa';

    document.querySelectorAll('[data-empresa-field]').forEach((Field) => {
        Field.classList.toggle('is-hidden', !EsEmpresa);
    });
}

// bloque resumen
function RenderCompraSummary() {
    const Items = GetCompraCart();
    const ItemsContainer = document.getElementById('CompraItems');
    const EmptyState = document.getElementById('CompraEmptyState');
    const ItemCount = document.getElementById('CompraItemCount');
    const Subtotal = Items.reduce((Total, Item) => Total + ((Number(Item.price) || 0) * (Number(Item.qty) || 0)), 0);
    const Shipping = Items.length ? 18.90 : 0;
    const Total = Subtotal + Shipping;

    if (ItemCount) {
        ItemCount.textContent = `${Items.reduce((Carry, Item) => Carry + (Number(Item.qty) || 0), 0)} items`;
    }

    SetText('CompraSubtotal', `S/.${Subtotal.toFixed(2)}`);
    SetText('CompraEnvio', `S/.${Shipping.toFixed(2)}`);
    SetText('CompraTotal', `S/.${Total.toFixed(2)}`);

    if (!ItemsContainer || !EmptyState) {
        return;
    }

    if (!Items.length) {
        ItemsContainer.innerHTML = '';
        EmptyState.classList.remove('hidden');
        return;
    }

    EmptyState.classList.add('hidden');
    ItemsContainer.innerHTML = Items.map((Item) => `
        <article class="CompraItem">
            <img src="${EscapeHtml(Item.image || '')}" alt="${EscapeHtml(Item.name || 'Producto')}" class="CompraItemImage">
            <div class="CompraItemMeta">
                <h3>${EscapeHtml(Item.name || 'Producto')}</h3>
                <p>cantidad: ${Number(Item.qty) || 0}</p>
                <div class="CompraItemPrice">S/.${((Number(Item.price) || 0) * (Number(Item.qty) || 0)).toFixed(2)}</div>
            </div>
        </article>
    `).join('');
}

// bloque acciones
function BindCompraEvents() {
    document.getElementById('CompraTipoCliente')?.addEventListener('change', SyncTipoCliente);

    document.getElementById('CompraForm')?.addEventListener('submit', (Event) => {
        Event.preventDefault();

        const Items = GetCompraCart();
        const Result = document.getElementById('CompraResult');

        if (!Items.length) {
            if (Result) {
                Result.classList.remove('hidden');
                Result.innerHTML = 'Agrega productos al carrito antes de simular el pedido.';
            }
            return;
        }

        const Codigo = `PED-${Math.floor(Date.now() / 1000)}`;
        const Metodo = document.getElementById('CompraMetodoPago')?.value || 'Tarjeta';
        const EstadoPedido = document.getElementById('CompraEstadoPedido')?.value || 'Pendiente';
        const EstadoPago = document.getElementById('CompraEstadoPago')?.value || 'Pendiente';
        const Total = document.getElementById('CompraTotal')?.textContent || 'S/.0.00';
        const Pedido = {
            id: crypto.randomUUID ? crypto.randomUUID() : `pedido-${Date.now()}`,
            codigo: Codigo,
            fecha: new Date().toLocaleString('es-PE'),
            metodoPago: Metodo,
            estadoPedido: EstadoPedido,
            estadoPago: EstadoPago,
            total: Total,
            items: Items,
        };

        SavePedido(Pedido);

        if (Result) {
            Result.classList.remove('hidden');
            Result.innerHTML = `
                <strong>pedido simulado generado</strong><br>
                código: ${EscapeHtml(Codigo)}<br>
                método: ${EscapeHtml(Metodo)}<br>
                estado pedido: ${EscapeHtml(EstadoPedido)}<br>
                estado pago: ${EscapeHtml(EstadoPago)}<br>
                total: ${EscapeHtml(Total)}<br>
                <a href="/pedidos" class="underline font-black">ver pedidos</a>
            `;
        }

        localStorage.removeItem('electroshop-cart');
        window.dispatchEvent(new Event('storage'));
        RenderCompraSummary();
    });
}

// bloque limpiar
window.ClearCompraSimulation = function() {
    document.getElementById('CompraForm')?.reset();
    FillCompraUserData();
    SyncTipoCliente();
    RenderSavedAddresses();
    SetText('CompraResult', '');
    document.getElementById('CompraResult')?.classList.add('hidden');
};

// bloque ayuda
function SetInputValue(Id, Value) {
    const Input = document.getElementById(Id);

    if (Input) {
        Input.value = Value;
    }
}

function SetText(Id, Value) {
    const Node = document.getElementById(Id);

    if (Node) {
        Node.textContent = Value;
    }
}

// bloque pedidos
function SavePedido(Pedido) {
    const Pedidos = GetPedidos();
    Pedidos.unshift(Pedido);
    localStorage.setItem(PedidoStorageKey, JSON.stringify(Pedidos));
}

// bloque lectura
function GetPedidos() {
    try {
        const Pedidos = JSON.parse(localStorage.getItem(PedidoStorageKey) || '[]');
        return Array.isArray(Pedidos) ? Pedidos : [];
    } catch {
        return [];
    }
}

// bloque texto
function EscapeHtml(Value) {
    return String(Value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
