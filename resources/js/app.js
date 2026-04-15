// Configuración Global
const Token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const JsonHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': Token,
    'X-Requested-With': 'XMLHttpRequest',
};

// --- Funciones de Autenticación ---
window.handleAuthStep = async function() {
    const emailInput = document.getElementById('AuthEmail');
    const Email = emailInput.value.trim();
    hideAuthAlert();

    if (!Email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(Email)) {
        showAuthAlert(Email ? 'Formato inválido' : 'Ingresa un correo');
        return;
    }

    try {
        const response = await fetch('/auth/check-email', { // Ajustar ruta según tu web.php
            method: 'POST',
            headers: JsonHeaders,
            body: JSON.stringify({ email: Email }),
        });
        const data = await response.json();

        if (!response.ok) throw new Error(data.message || 'Error de validación');

        emailInput.readOnly = true;
        document.getElementById('PassWrapper').classList.remove('hidden');
        document.getElementById('BackBtn').classList.remove('hidden');

        const button = document.getElementById('AuthBtn');
        const subtitle = document.getElementById('AuthSubtitle');

        if (data.exists) {
            subtitle.innerText = 'Ingresa tu contraseña';
            button.innerText = 'Iniciar Sesión';
            button.onclick = login;
        } else {
            subtitle.innerText = 'Crea una contraseña';
            button.innerText = 'Registrar Cuenta';
            button.onclick = register;
        }
    } catch (error) {
        showAuthAlert(error.message);
    }
}

async function login() {
    const email = document.getElementById('AuthEmail').value.trim();
    const password = document.getElementById('AuthPass').value;

    if (!password) return showAuthAlert('Ingresa la clave');

    const response = await fetch('/login', {
        method: 'POST',
        headers: JsonHeaders,
        body: JSON.stringify({ email, password }),
    });
    const data = await response.json();

    if (data.success) window.location.reload();
    else showAuthAlert(data.message || 'Credenciales incorrectas');
}

async function register() {
    const email = document.getElementById('AuthEmail').value.trim();
    const password = document.getElementById('AuthPass').value;

    if (password.length < 6) return showAuthAlert('Mínimo 6 caracteres');

    const response = await fetch('/register', {
        method: 'POST',
        headers: JsonHeaders,
        body: JSON.stringify({ email, password }),
    });
    const data = await response.json();

    if (data.success) window.location.reload();
    else showAuthAlert(data.message || 'Error al registrar');
}

// --- Gestión del Carrito ---
window.getCart = () => JSON.parse(localStorage.getItem('electroshop-cart') || '[]');

window.setCart = (cart) => {
    localStorage.setItem('electroshop-cart', JSON.stringify(cart));
    updateCartUI();
};

window.updateCartUI = function() {
    const cart = getCart();
    const count = cart.reduce((total, item) => total + item.qty, 0);
    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

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
        itemsContainer.innerHTML = `<div class="text-center py-20 text-slate-400">Carrito vacío</div>`;
        return;
    }

    itemsContainer.innerHTML = cart.map(item => `
        <article class="flex gap-4 rounded-[1.5rem] border border-slate-100 p-4">
            <img src="${item.image}" class="w-20 h-20 object-contain bg-slate-50 p-2 rounded-xl">
            <div class="flex-1">
                <h3 class="text-sm font-bold text-slate-900">${item.name}</h3>
                <p class="text-sm font-black italic">S/.${item.price.toFixed(2)}</p>
                <div class="mt-2 flex justify-between items-center">
                    <div class="flex gap-3 bg-slate-100 px-3 py-1 rounded-full text-xs">
                        <button onclick="changeQty(${item.id}, -1)">−</button>
                        <span class="font-bold">${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 text-[10px] font-black uppercase">Quitar</button>
                </div>
            </div>
        </article>
    `).join('');
};

window.changeQty = (id, delta) => {
    const cart = getCart().map(i => i.id === id ? {...i, qty: i.qty + delta} : i).filter(i => i.qty > 0);
    setCart(cart);
};

window.removeFromCart = (id) => setCart(getCart().filter(i => i.id !== id));

// --- UI Helpers ---
window.toggleCart = (show) => {
    const overlay = document.getElementById('CartOverlay');
    const drawer = document.getElementById('CartDrawer');
    overlay.classList.toggle('hidden', !show);
    drawer.classList.toggle('translate-x-full', !show);
    document.body.style.overflow = show ? 'hidden' : 'auto';
};

window.openAuthModal = () => {
    const modal = document.getElementById('AuthModal');
    const container = document.getElementById('ModalContainer');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        container.classList.remove('translate-y-full', 'opacity-0', 'md:scale-95');
        container.classList.add('translate-y-0', 'opacity-100', 'md:scale-100');
    }, 10);
};

window.closeAuthModal = () => {
    const modal = document.getElementById('AuthModal');
    const container = document.getElementById('ModalContainer');
    container.classList.add('translate-y-full', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        resetAuth();
    }, 400);
};

function showAuthAlert(msg) {
    const alert = document.getElementById('AuthAlert');
    alert.innerText = msg;
    alert.classList.remove('hidden');
}

function hideAuthAlert() {
    document.getElementById('AuthAlert').classList.add('hidden');
}

function resetAuth() {
    document.getElementById('AuthEmail').readOnly = false;
    document.getElementById('PassWrapper').classList.add('hidden');
    document.getElementById('BackBtn').classList.add('hidden');
    document.getElementById('AuthSubtitle').innerText = 'Panel de Identificación';
    document.getElementById('AuthBtn').innerText = 'Continuar';
    document.getElementById('AuthBtn').onclick = window.handleAuthStep;
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();
    const searchInput = document.getElementById('q');
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => e.key === 'Enter' && window.executeSearch());
    }
});

window.executeSearch = () => {
    const q = document.getElementById('q').value.trim();
    window.location.href = q ? `/?search=${encodeURIComponent(q)}` : '/';
};
