/**
 * Lógica Global de ElectroShop
 * Maneja Carrito, Autenticación y Buscador
 */

const Token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const JsonHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': Token,
    'X-Requested-With': 'XMLHttpRequest',
};

// --- BUSCADOR ---
window.Search = function() {
    const q = document.getElementById('q').value.trim();
    const url = q ? `/?search=${encodeURIComponent(q)}` : '/';
    window.location.href = url;
};

// --- CARRITO ---
window.getCart = () => {
    try {
        return JSON.parse(localStorage.getItem('electroshop-cart') || '[]');
    } catch { return []; }
};

window.setCart = (cart) => {
    localStorage.setItem('electroshop-cart', JSON.stringify(cart));
    window.updateCartUI();
};

window.updateCartUI = function() {
    const cart = window.getCart();
    const count = cart.reduce((t, i) => t + i.qty, 0);
    const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);

    const countBadge = document.getElementById('CartCount');
    if (countBadge) {
        countBadge.innerText = count;
        countBadge.classList.toggle('hidden', count === 0);
        countBadge.classList.toggle('inline-flex', count > 0);
    }

    const totalEl = document.getElementById('CartTotal');
    if (totalEl) totalEl.innerText = `S/.${total.toFixed(2)}`;

    const itemsContainer = document.getElementById('CartItems');
    if (!itemsContainer) return;

    if (cart.length === 0) {
        itemsContainer.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-center opacity-50 py-20">
                <p class="font-black uppercase text-xs">Tu carrito está vacío</p>
            </div>`;
        return;
    }

    itemsContainer.innerHTML = cart.map(item => `
        <article class="flex gap-4 p-4 bg-white border border-slate-100 rounded-3xl">
            <img src="${item.image}" class="w-16 h-16 object-contain">
            <div class="flex-1">
                <h4 class="text-xs font-bold truncate">${item.name}</h4>
                <p class="font-black text-sm italic">S/.${item.price.toFixed(2)}</p>
                <div class="flex justify-between items-center mt-2">
                    <div class="flex gap-4 bg-slate-100 px-3 py-1 rounded-full text-xs font-bold">
                        <button onclick="changeQty(${item.id}, -1)">−</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 text-[9px] font-black uppercase">Borrar</button>
                </div>
            </div>
        </article>
    `).join('');
};

window.changeQty = (id, delta) => {
    const cart = window.getCart().map(i => i.id === id ? {...i, qty: i.qty + delta} : i).filter(i => i.qty > 0);
    window.setCart(cart);
};

window.removeFromCart = (id) => window.setCart(window.getCart().filter(i => i.id !== id));

window.ToggleCart = function(show) {
    const drawer = document.getElementById('CartDrawer');
    const overlay = document.getElementById('CartOverlay');
    if (show) {
        drawer.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('modal-open');
    } else {
        drawer.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }
};

// --- AUTENTICACIÓN ---
window.openAuthModal = function() {
    const modal = document.getElementById('AuthModal');
    const container = document.getElementById('ModalContainer');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        container.classList.remove('translate-y-full', 'opacity-0', 'md:scale-95');
    }, 10);
};

window.closeAuthModal = function() {
    const modal = document.getElementById('AuthModal');
    const container = document.getElementById('ModalContainer');
    container.classList.add('translate-y-full', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 400);
};

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', () => {
    window.updateCartUI();

    // Escuchar Enter en buscador
    document.getElementById('q')?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') window.Search();
    });
});
