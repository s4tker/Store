const CompraBootstrapNode = document.getElementById("CompraBootstrap");
const CompraBootstrap = CompraBootstrapNode
    ? JSON.parse(CompraBootstrapNode.textContent || "{}")
    : {};
const PedidoStorageKey = "electroshop-orders";

// bloque inicio
document.addEventListener("DOMContentLoaded", () => {
    if (!document.getElementById("CompraForm")) {
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
        return JSON.parse(localStorage.getItem("electroshop-cart") || "[]");
    } catch {
        return [];
    }
}

// bloque usuario
function FillCompraUserData() {
    const Usuario = CompraBootstrap.Usuario || {};

    SetInputValue("CompraNombre", Usuario.Nombre || "");
    SetInputValue("CompraApellidos", Usuario.Apellidos || "");
    SetInputValue("CompraCorreo", Usuario.Correo || "");
    SetInputValue("CompraTelefono", Usuario.Telefono || "");
    SetInputValue("CompraDni", Usuario.Dni || "");
    SetInputValue("CompraRuc", Usuario.Ruc || "");
    SetInputValue("CompraRazonSocial", Usuario.RazonSocial || "");
    SetInputValue("CompraPais", "Perú");
}

// bloque direcciones
function RenderSavedAddresses() {
    const Container = document.getElementById("CompraSavedAddresses");
    const Direcciones = CompraBootstrap.Direcciones || [];

    if (!Container || !Direcciones.length) {
        return;
    }

    Container.innerHTML = Direcciones.map(
        (Direccion) => `
        <button type="button" class="CompraSavedAddress" data-address-id="${Direccion.Id}">
            <strong>${EscapeHtml(`${Direccion.Ciudad || ""} ${Direccion.Region ? `· ${Direccion.Region}` : ""}`.trim() || "Dirección guardada")}</strong>
            <span>${EscapeHtml([Direccion.Pais, Direccion.Ciudad, Direccion.Direccion, Direccion.Referencia].filter(Boolean).join(" · "))}</span>
        </button>
    `,
    ).join("");

    Container.querySelectorAll("[data-address-id]").forEach((Button) => {
        Button.addEventListener("click", () => {
            const Direccion = Direcciones.find(
                (Item) => String(Item.Id) === String(Button.dataset.addressId),
            );

            if (!Direccion) {
                return;
            }

            Container.querySelectorAll(".CompraSavedAddress").forEach((Card) =>
                Card.classList.remove("is-active"),
            );
            Button.classList.add("is-active");

            SetInputValue("CompraPais", Direccion.Pais || "");
            SetInputValue("CompraRegion", Direccion.Region || "");
            SetInputValue("CompraCiudad", Direccion.Ciudad || "");
            SetInputValue("CompraDireccion", Direccion.Direccion || "");
            SetInputValue("CompraReferencia", Direccion.Referencia || "");
        });
    });
}

// bloque empresa
function SyncTipoCliente() {
    const TipoCliente = document.getElementById("CompraTipoCliente");
    const EsEmpresa = TipoCliente?.value === "empresa";

    document.querySelectorAll("[data-empresa-field]").forEach((Field) => {
        Field.classList.toggle("is-hidden", !EsEmpresa);
    });
}

// bloque resumen
function RenderCompraSummary() {
    const Items = GetCompraCart();
    const ItemsContainer = document.getElementById("CompraItems");
    const EmptyState = document.getElementById("CompraEmptyState");
    const ItemCount = document.getElementById("CompraItemCount");

    // Cálculos de totales
    const Subtotal = Items.reduce(
        (Total, Item) =>
            Total + (Number(Item.price) || 0) * (Number(Item.qty) || 0),
        0,
    );
    const Shipping = Items.length ? 18.9 : 0;
    const Total = Subtotal + Shipping;

    // Actualizar contadores y textos de totales
    if (ItemCount) {
        const totalQty = Items.reduce(
            (Carry, Item) => Carry + (Number(Item.qty) || 0),
            0,
        );
        ItemCount.textContent = `${totalQty} items`;
    }

    SetText("CompraSubtotal", `S/. ${Subtotal.toFixed(2)}`);
    SetText("CompraEnvio", `S/. ${Shipping.toFixed(2)}`);
    SetText("CompraTotal", `${Total.toFixed(2)}`);

    if (!ItemsContainer || !EmptyState) {
        return;
    }

    // Manejo de estado vacío[cite: 2]
    if (!Items.length) {
        ItemsContainer.innerHTML = "";
        EmptyState.classList.remove("hidden");
        return;
    }

    EmptyState.classList.add("hidden");

    ItemsContainer.innerHTML = Items.map((Item) => {
        const totalPrice = (
            (Number(Item.price) || 0) * (Number(Item.qty) || 0)
        ).toFixed(2);

        return `
        <article class="flex items-center gap-4 rounded-[1.75rem] border border-white/10 bg-white/5 backdrop-blur p-4 text-white shadow-sm">
            <div class="flex h-20 w-20 flex-none items-center justify-center rounded-3xl bg-white p-2">
                <img 
                    src="${EscapeHtml(Item.image || "")}" 
                    alt="${EscapeHtml(Item.name || "Producto")}" 
                    class="max-h-full max-w-full object-contain"/>
            </div>

            <div class="min-w-0 flex-1">
                <h3 class="truncate text-sm font-black uppercase tracking-[0.06em] text-white">${EscapeHtml(Item.name || "Producto")}</h3>
                <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Cantidad: ${Number(Item.qty) || 0}</p>
            </div>

            <div class="flex flex-none flex-col items-end justify-between text-right">
                <span class="text-sm font-black text-white">S/. ${totalPrice}</span>
                <span class="text-[10px] uppercase tracking-[0.25em] text-slate-500">Total</span>
            </div>
        </article>
        `;
    }).join("");
}

// bloque acciones
function BindCompraEvents() {
    document
        .getElementById("CompraTipoCliente")
        ?.addEventListener("change", SyncTipoCliente);

    document
        .getElementById("CompraForm")
        ?.addEventListener("submit", (Event) => {
            const Items = GetCompraCart();
            const Result = document.getElementById("CompraResult");

            if (!Items.length) {
                Event.preventDefault();
                if (Result) {
                    Result.classList.remove("hidden");
                    Result.innerHTML =
                        "Agrega productos al carrito antes de confirmar el pedido.";
                }
                return;
            }

            // Llenar el campo oculto con el carrito en JSON
            document.getElementById("CompraCarrito").value =
                JSON.stringify(Items);
        });
}

// bloque limpiar
window.ClearCompraSimulation = function () {
    document.getElementById("CompraForm")?.reset();
    FillCompraUserData();
    SyncTipoCliente();
    RenderSavedAddresses();
    SetText("CompraResult", "");
    document.getElementById("CompraResult")?.classList.add("hidden");
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
        const Pedidos = JSON.parse(
            localStorage.getItem(PedidoStorageKey) || "[]",
        );
        return Array.isArray(Pedidos) ? Pedidos : [];
    } catch {
        return [];
    }
}

// bloque texto
function EscapeHtml(Value) {
    return String(Value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}
