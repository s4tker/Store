const CompraBootstrapNode = document.getElementById("CompraBootstrap");
const CompraBootstrap = CompraBootstrapNode
    ? JSON.parse(CompraBootstrapNode.dataset.bootstrap || "{}")
    : window.CompraBootstrap || {};
const PedidoStorageKey = "electroshop-orders";
const PaymentMethodsWithModal = ["Tarjeta", "Yape", "PagoEfectivo"];
const CardImages = CompraBootstrap.CardImages || [];
let PagoEfectivoTimer = null;
let PagoEfectivoDeadline = null;

window.compraFlow = function () {
    return {
        dirId: CompraBootstrap.DireccionSeleccionadaId || null,
        metodoPago: CompraBootstrap.MetodoPago || "Tarjeta",
        tieneDirecciones: Boolean(CompraBootstrap.TieneDirecciones),
        direccionesGuardadas: CompraBootstrap.Direcciones || [],
        totals: { total: 0 },
        form: {
            Documento: CompraBootstrap.Form?.Documento || "",
            Telefono: CompraBootstrap.Form?.Telefono || "",
            Nombre: CompraBootstrap.Form?.Nombre || "",
            Apellidos: CompraBootstrap.Form?.Apellidos || "",
            Correo: CompraBootstrap.Form?.Correo || "",
            Region: CompraBootstrap.Form?.Region || "",
            Ciudad: CompraBootstrap.Form?.Ciudad || "",
            Direccion: CompraBootstrap.Form?.Direccion || "",
            Referencia: CompraBootstrap.Form?.Referencia || "",
        },
        setDireccion(dir) {
            this.dirId = dir.Id;
            this.form.Region = dir.Region || "";
            this.form.Ciudad = dir.Ciudad || "";
            this.form.Direccion = dir.Direccion || "";
            this.form.Referencia = dir.Referencia || "";
        },
        async buscarCliente() {
            const dni = String(this.form.Documento || "").replace(/\D/g, "");
            this.form.Documento = dni;

            if (dni.length !== 8 || !CompraBootstrap.BuscarDniUrl) {
                return;
            }

            const response = await fetch(`${CompraBootstrap.BuscarDniUrl}/${dni}`, {
                headers: { Accept: "application/json" },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            if (!data.found) {
                return;
            }

            this.form.Nombre = data.cliente.Nombre || "";
            this.form.Apellidos = data.cliente.Apellidos || "";
            this.form.Correo = data.cliente.Correo || "";
            this.form.Telefono = data.cliente.Telefono || "";
            this.direccionesGuardadas = data.direcciones || [];
        },
    };
};

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
    BindPaymentEvents();
    BindPaymentModalEvents();
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
    SetText("PagoEfectivoTotal", Total.toFixed(2));

    const AlpineData = document.querySelector("[x-data]")?._x_dataStack?.[0];
    if (AlpineData?.totals) {
        AlpineData.totals.total = Total;
    }

    if (!ItemsContainer) {
        return;
    }

    // Manejo de estado vacío[cite: 2]
    if (!Items.length) {
        ItemsContainer.innerHTML = "";
        EmptyState?.classList.remove("hidden");
        return;
    }

    EmptyState?.classList.add("hidden");

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

function BindPaymentEvents() {
    const Methods = document.querySelectorAll("[data-payment-method]");

    Methods.forEach((Input) => {
        Input.addEventListener("change", () => {
            if (!Input.checked) {
                return;
            }

            SetPaymentMethod(Input.value);

            if (PaymentMethodsWithModal.includes(Input.value)) {
                OpenPaymentModal(Input.value);
            } else {
                ClosePaymentModal();
            }
        });
    });

    const SelectedMethod = document.querySelector("[data-payment-method]:checked")?.value || "Tarjeta";
    SetPaymentMethod(SelectedMethod);
}

function BindPaymentModalEvents() {
    const Modal = document.getElementById("paymentModal");
    if (!Modal) {
        return;
    }

    Modal.addEventListener("click", (Event) => {
        const Target = Event.target;
        if (Target.id === "paymentModal" || Target.closest("[data-modal-close]")) {
            ClosePaymentModal();
        }
    });

    document.addEventListener("keydown", (Event) => {
        if (Event.key === "Escape") {
            ClosePaymentModal();
        }
    });
}

function OpenPaymentModal(Method) {
    const Modal = document.getElementById("paymentModal");
    if (!Modal) {
        return;
    }

    const Titles = {
        Tarjeta: "Pago con tarjeta",
        Yape: "Pago con Yape / Plin",
        PagoEfectivo: "PagoEfectivo CIP",
    };

    Modal.innerHTML = `
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
            <div data-modal-close class="absolute inset-0"></div>
            <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 p-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600">${EscapeHtml(Method)}</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900">${EscapeHtml(Titles[Method] || "Metodo de pago")}</h2>
                    </div>
                    <button type="button" data-modal-close class="rounded-full border border-slate-200 px-4 py-2 text-sm font-black uppercase tracking-[0.2em] text-slate-600 hover:bg-slate-100">Cerrar</button>
                </div>
                <div class="p-6">${RenderPaymentModalContent(Method)}</div>
            </div>
        </div>
    `;

    BindModalCardFields();

    if (Method === "Yape") {
        document.getElementById("OpenYapeInstructionsButton")?.addEventListener("click", () => {
            document.getElementById("YapeInstructionPanelModal")?.classList.remove("hidden");
        });
    }

    if (Method === "PagoEfectivo") {
        InitPagoEfectivo();
    }
}

function ClosePaymentModal() {
    const Modal = document.getElementById("paymentModal");
    if (Modal) {
        Modal.innerHTML = "";
    }
}

function RenderPaymentModalContent(Method) {
    const total = document.getElementById("CompraTotal")?.textContent || "0.00";
    const cardIcons = CardImages.map((Src) => `<img src="${EscapeHtml(Src)}" alt="Tarjeta" class="h-8 w-auto"/>`).join("");
    const phoneValue = EscapeHtml(CompraBootstrap.Form?.Telefono || CompraBootstrap.Usuario?.Telefono || "");

    if (Method === "Tarjeta") {
        return `
    <div class="mt-6 animate-fadeIn w-full">
        <div class="bg-white rounded-[3rem] border-2 border-slate-100 p-8 md:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-slate-50 rounded-full -mr-32 -mt-32 opacity-50 pointer-events-none"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between mb-4 gap-3">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center shadow-2xl shadow-slate-900/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[12px] font-black text-slate-900 uppercase tracking-[0.3em] leading-none mb-2">Pago Blindado</h3>
                        <div class="flex items-center gap-2">
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-nowrap">Conexión Segura AES-256</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-y-6 gap-x-8">
                
                <div class="md:col-span-6 space-y-4">
                    <div class="flex justify-between items-end px-1">
                        <label class="text-[10px] font-black text-slate-900 uppercase tracking-[0.4em]">Número de Tarjeta</label>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic" data-card-hint="number">ID de Transacción</span>
                    </div>
                    <div class="relative group">
                        <input name="TarjetaNumero" type="text" inputmode="numeric" maxlength="19" 
                            placeholder="0000 0000 0000 0000" 
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl py-3 px-5 text-lg font-black tracking-[0.1em] text-slate-900 outline-none transition-all focus:bg-white focus:border-slate-900 focus:shadow-[0_0_0_6px_rgba(15,23,42,0.03)] placeholder:text-slate-200" 
                            data-card-field="number">
                        <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-20 group-focus-within:opacity-100 transition-opacity">
                             <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-width="2"/></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3 space-y-4">
                    <label class="text-[10px] font-black text-slate-900 uppercase tracking-[0.4em] block px-1">Vencimiento</label>
                    <input name="TarjetaExpiracion" type="text" inputmode="numeric" maxlength="5" 
                    placeholder="MM / AA" 
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl py-3 px-5 text-center text-base font-black tracking-[0.15em] text-slate-900 outline-none transition-all focus:bg-white focus:border-slate-900" 
                    data-card-field="expiry">
                </div>

                <div class="md:col-span-3 space-y-4">
                    <div class="flex justify-between items-end px-1">
                        <label class="text-[10px] font-black text-slate-900 uppercase tracking-[0.4em]">CVC / CVV</label>
                        <div class="group relative">
                            <svg class="w-4 h-4 text-slate-300 cursor-help" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                    </div>
                    <input name="TarjetaCvv" type="password" inputmode="numeric" maxlength="4" 
                    placeholder="••••" 
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl py-3 px-5 text-center text-base font-black tracking-[0.2em] text-slate-900 outline-none transition-all focus:bg-white focus:border-slate-900" 
                    data-card-field="cvv">
                </div>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 opacity-40 grayscale hover:grayscale-0 transition-all duration-700">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6" alt="Mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-5" alt="PayPal">
                <div class="h-4 w-px bg-slate-300 hidden md:block"></div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">PCI-DSS Compliant</span>
                </div>
            </div>
        </div>

        <p class="mt-6 text-center text-[9px] font-bold text-slate-400 uppercase tracking-[0.4em]">Tus datos nunca son almacenados en nuestros servidores</p>
    </div>
    `;
    }

    if (Method === "Yape") {
        return `
    <div class="mt-2 animate-fadeIn flex justify-center">
        <div class="w-full max-w-2xl space-y-4">
            
            <div class="bg-white rounded-[2.5rem] border-2 border-slate-100 p-6 shadow-sm relative overflow-hidden group transition-all">
                <div class="absolute top-0 right-0 bg-slate-900 text-white px-5 py-1.5 rounded-bl-2xl">
                    <span class="text-[9px] font-black tracking-[0.3em] uppercase italic">Yape Direct</span>
                </div>

                <div class="space-y-4">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] px-1">Número Celular</label>
                        <div class="flex items-center bg-slate-50 border-2 border-slate-100 rounded-2xl overflow-hidden focus-within:border-slate-900 focus-within:bg-white transition-all">
                            <span class="px-5 text-sm font-black text-slate-400 border-r border-slate-200">+51</span>
                            <input id="YapePhoneModal" name="YapeTelefono" type="tel" maxlength="9" 
                                value="${phoneValue}" 
                                class="w-full bg-transparent border-0 px-5 py-4 text-lg font-black text-slate-900 focus:ring-0 tracking-[0.2em]" 
                                placeholder="999 999 999">
                        </div>
                    </div>

                    <button type="button" id="OpenYapeInstructionsButton" 
                        class="w-full bg-slate-900 text-white rounded-2xl py-4 text-[10px] font-black uppercase tracking-[0.4em] shadow-xl shadow-slate-900/10 hover:bg-black transition-all active:scale-[0.98]">
                        Procesar Pago
                    </button>
                </div>
            </div>

            <div id="YapeInstructionPanelModal" class="hidden animate-fadeIn">
                <div class="bg-slate-50/50 rounded-[2rem] p-8 border border-slate-100">
                    
                    <div class="relative space-y-8">
                        <div class="absolute left-[11px] top-2 bottom-2 w-[2px] bg-slate-200"></div>

                        <div class="relative flex items-start gap-6 group">
                            <div class="w-6 h-6 rounded-full bg-white border-2 border-slate-900 flex items-center justify-center z-10">
                                <span class="text-[9px] font-black text-slate-900">1</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-widest leading-none mb-1">Abre tu App</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Ingresa a Yape y busca el menú lateral</p>
                            </div>
                        </div>

                        <div class="relative flex items-start gap-6 group">
                            <div class="w-6 h-6 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center z-10 group-hover:border-slate-900 transition-colors">
                                <span class="text-[9px] font-black text-slate-300 group-hover:text-slate-900">2</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-widest leading-none mb-1">Aprobar Compras</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Selecciona la solicitud de pago pendiente</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                <span class="text-[9px] font-black text-slate-900 uppercase tracking-[0.2em]">Validando transaccion...</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="h-1 w-4 bg-slate-200 rounded-full"></span>
                                <span class="h-1 w-8 bg-slate-900 rounded-full animate-pulse"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    `;
    }

    if (Method === "PagoEfectivo") {
        const code = GeneratePagoEfectivoCode();
        return `
        <div class="mt-6 animate-fadeIn space-y-6">
            <div class="text-center px-4">
                <p class="text-[11px] font-bold text-slate-500 leading-relaxed uppercase tracking-tight">
                    Copia tu <span class="text-slate-900 font-black">código CIP de 9 dígitos</span> para pagar en línea por transferencia, QR (Yape, Plin) o efectivo en agentes a través de <span class="text-blue-600 font-black">PagoEfectivo</span>.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="rounded-2xl bg-white border-2 border-slate-100 p-4 shadow-sm flex flex-col items-center justify-center">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Código CIP</span>
                    <p id="PagoEfectivoCode" class="text-xl font-black tracking-widest text-slate-900 select-all">${EscapeHtml(code)}</p>
                </div>

                <div class="rounded-2xl bg-red-50 border-2 border-red-100/50 p-4 flex flex-col items-center justify-center">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-red-400 mb-1">Plazo de Pago</span>
                    <p id="PagoEfectivoCountdown" class="text-xl font-black text-red-600 tracking-tighter">47:59:44</p>
                </div>

                <div class="rounded-2xl bg-slate-900 p-4 shadow-lg shadow-slate-900/20 flex flex-col items-center justify-center">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1">Total a Pagar</span>
                    <p class="text-xl font-black text-white italic tracking-tighter">S/ ${EscapeHtml(total)}</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-[2.5rem] p-6 border border-slate-100 flex flex-col items-center group transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/50">
                <div class="mb-4 p-3 bg-white rounded-2xl shadow-inner border border-slate-100">
                    <div class="h-32 w-32 flex items-center justify-center bg-slate-50 rounded-xl overflow-hidden">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Cargar QR</span>
                    </div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Escanea con tu App bancaria</p>
            </div>

            <div class="flex justify-center gap-6 pt-2">
                <button class="text-[10px] font-black text-slate-900 uppercase border-b-2 border-slate-900 pb-0.5 hover:text-blue-600 hover:border-blue-600 transition-all">¿Cómo pagar?</button>
                <button class="text-[10px] font-black text-blue-600 uppercase border-b-2 border-blue-600 pb-0.5 hover:text-blue-700 transition-all">Actualizar estado</button>
            </div>
        </div>
    `;
    }

    return `
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-slate-600">
            Selecciona un metodo de pago para continuar.
        </div>
    `;
}

function BindModalCardFields() {
    document.querySelectorAll("#paymentModal [data-card-field]").forEach((Input) => {
        Input.addEventListener("input", () => {
            FormatCardField(Input);
            ValidateCardField(Input);
        });
        ValidateCardField(Input);
    });
}

function GeneratePagoEfectivoCode() {
    return String(Math.floor(100000000 + Math.random() * 900000000));
}

function SetPaymentMethod(Method) {
    document.querySelectorAll("[data-payment-method]").forEach((Input) => {
        Input.checked = Input.value === Method;
    });

    document.querySelectorAll("[data-payment-panel]").forEach((Panel) => {
        Panel.classList.toggle("hidden", Panel.dataset.paymentPanel !== Method);
    });

    const AlpineData = document.querySelector("[x-data]")?._x_dataStack?.[0];
    if (AlpineData) {
        AlpineData.metodoPago = Method;
    }

    if (Method === "PagoEfectivo") {
        InitPagoEfectivo();
    }
}

function InitPagoEfectivo() {
    const CodeNode = document.getElementById("PagoEfectivoCode");
    if (CodeNode && CodeNode.textContent === "000000000") {
        CodeNode.textContent = String(Math.floor(100000000 + Math.random() * 900000000));
    }

    if (!PagoEfectivoDeadline) {
        PagoEfectivoDeadline = Date.now() + 48 * 60 * 60 * 1000;
    }

    UpdatePagoEfectivoCountdown();

    if (!PagoEfectivoTimer) {
        PagoEfectivoTimer = window.setInterval(UpdatePagoEfectivoCountdown, 1000);
    }
}

function UpdatePagoEfectivoCountdown() {
    const Node = document.getElementById("PagoEfectivoCountdown");
    if (!Node || !PagoEfectivoDeadline) {
        return;
    }

    const Remaining = Math.max(0, PagoEfectivoDeadline - Date.now());
    const Hours = Math.floor(Remaining / 3600000);
    const Minutes = Math.floor((Remaining % 3600000) / 60000);
    const Seconds = Math.floor((Remaining % 60000) / 1000);

    Node.textContent = [Hours, Minutes, Seconds]
        .map((Value) => String(Value).padStart(2, "0"))
        .join(":");
}

function FormatCardField(Input) {
    const Raw = Input.value.replace(/\D/g, "");

    if (Input.dataset.cardField === "number") {
        Input.value = Raw.slice(0, 16).replace(/(\d{4})(?=\d)/g, "$1 ");
        return;
    }

    if (Input.dataset.cardField === "expiry") {
        Input.value = Raw.slice(0, 4).replace(/(\d{2})(?=\d)/, "$1/");
        return;
    }

    if (Input.dataset.cardField === "cvv") {
        Input.value = Raw.slice(0, 4);
    }
}

function ValidateCardField(Input) {
    const Raw = Input.value.replace(/\D/g, "");
    const Field = Input.dataset.cardField;
    const IsValid =
        (Field === "number" && Raw.length === 16) ||
        (Field === "expiry" && IsValidExpiry(Raw)) ||
        (Field === "cvv" && Raw.length >= 3 && Raw.length <= 4);

    Input.classList.toggle("border-emerald-300", IsValid);
    Input.classList.toggle("border-red-300", Raw.length > 0 && !IsValid);

    const Hint = document.querySelector(`[data-card-hint="${Field}"]`);
    if (Hint) {
        Hint.classList.toggle("text-emerald-600", IsValid);
        Hint.classList.toggle("text-red-500", Raw.length > 0 && !IsValid);
    }
}

function IsValidExpiry(Value) {
    if (Value.length !== 4) {
        return false;
    }

    const Month = Number(Value.slice(0, 2));
    return Month >= 1 && Month <= 12;
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
                } else {
                    window.alert("Agrega productos al carrito antes de confirmar el pedido.");
                }
                return;
            }

            const AlpineData = document.querySelector("[x-data]")?._x_dataStack?.[0];
            if (AlpineData?.tieneDirecciones && !AlpineData.dirId) {
                Event.preventDefault();
                window.alert("Selecciona una direccion guardada para continuar.");
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

    if (Input && !Input.value) {
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
