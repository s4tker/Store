<div id="CartOverlay" class="fixed inset-0 z-[85] hidden bg-[#0f172a]/70 backdrop-blur-sm" onclick="ToggleCart(false)"></div>
<aside id="CartDrawer" class="fixed right-0 top-0 z-[90] h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300">
    <div class="h-full flex flex-col">
        <div class="px-6 py-5 border-b flex items-center justify-between">
            <h2 class="text-xl font-black italic uppercase text-slate-900">Tu Carrito</h2>
            <button type="button" onclick="ToggleCart(false)" class="text-2xl text-slate-400 hover:text-slate-900">&times;</button>
        </div>
        <div id="CartItems" class="flex-1 overflow-y-auto px-6 py-6 space-y-4"></div>
        <div class="p-6 border-t bg-slate-50">
            <div class="flex items-center justify-between font-black uppercase text-xs mb-4 text-slate-600">
                <span>Total estimado</span>
                <span id="CartTotal" class="text-lg italic text-blue-600">S/.0.00</span>
            </div>
            <button
                type="button"
                id="BtnCheckout"
                data-checkout-url="{{ route('compras.formulario') }}"
                onclick="GoToCompraForm()"
                class="w-full rounded-2xl bg-slate-900 px-4 py-4 text-[11px] font-black uppercase tracking-widest text-white transition-all hover:bg-blue-600"
            >
                Ir a compra
            </button>
        </div>
    </div>
</aside>
