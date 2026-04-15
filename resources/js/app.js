const Token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const JsonHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': Token,
    'X-Requested-With': 'XMLHttpRequest',
};

let authMode = 'login';

// --- 1. BUSCADOR ---
window.Search = function() {
    const q = document.getElementById('q').value.trim();
    const url = q ? `/?search=${encodeURIComponent(q)}` : '/';
    window.location.href = url;
};

// --- 2. CARRITO ---
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
        itemsContainer.innerHTML = `<div class="py-20 text-center opacity-50"><p class="font-black uppercase text-xs">Vacío</p></div>`;
        return;
    }

    itemsContainer.innerHTML = cart.map(item => `
        <article class="flex gap-4 p-4 bg-white border border-slate-100 rounded-3xl animate-fade-in">
            <img src="${item.image}" class="w-16 h-16 object-contain">
            <div class="flex-1">
                <h4 class="text-xs font-bold truncate text-slate-800">${item.name}</h4>
                <p class="font-black text-sm italic text-blue-600">S/.${item.price.toFixed(2)}</p>
                <div class="flex justify-between items-center mt-2">
                    <div class="flex gap-4 bg-slate-100 px-3 py-1 rounded-full text-xs font-bold text-slate-600">
                        <button onclick="changeQty(${item.id}, -1)">−</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 text-[9px] font-black uppercase underline">Borrar</button>
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
        document.body.classList.add('overflow-hidden');
    } else {
        drawer.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
};

// --- 3. AUTENTICACIÓN ---

window.openAuthModal = function() {
    const modal = document.getElementById('AuthModal');
    const container = document.getElementById('ModalContainer');
    modal.classList.replace('hidden', 'flex');
    setTimeout(() => {
        container.classList.remove('translate-y-full', 'opacity-0', 'md:scale-95');
        document.getElementById('AuthEmail').focus(); // Foco al abrir
    }, 10);
};

window.closeAuthModal = function() {
    const container = document.getElementById('ModalContainer');
    container.classList.add('translate-y-full', 'opacity-0');
    setTimeout(() => {
        document.getElementById('AuthModal').classList.replace('flex', 'hidden');
        resetAuthForm();
    }, 400);
};

function resetAuthForm() {
    const emailInput = document.getElementById('AuthEmail');
    emailInput.readOnly = false;
    emailInput.value = '';
    emailInput.classList.remove('opacity-50');
    document.getElementById('AuthPass').value = '';
    document.getElementById('PassWrapper').classList.add('hidden');
    document.getElementById('AuthSubtitle').innerText = 'Ingresa tu correo para continuar';
    document.getElementById('AuthBtn').innerText = 'Continuar';
    document.getElementById('AuthAlert').classList.add('hidden');
}

window.togglePassword = function() {
    const passInput = document.getElementById('AuthPass');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passInput.type === 'password') {
        passInput.type = 'text';
        eyeIcon.classList.add('text-blue-600');
    } else {
        passInput.type = 'password';
        eyeIcon.classList.remove('text-blue-600');
    }
};

// Función para validar formato de correo
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

window.handleAuthStep = async function() {
    const emailInput = document.getElementById('AuthEmail');
    const passInput = document.getElementById('AuthPass');
    const passWrapper = document.getElementById('PassWrapper');
    const alertBox = document.getElementById('AuthAlert');
    const btn = document.getElementById('AuthBtn');

    const email = emailInput.value.trim();
    const password = passInput.value.trim();

    alertBox.classList.add('hidden');

    // Validación de formato de correo
    if (!validateEmail(email)) {
        alertBox.innerText = 'Ingresa un correo válido (ejemplo@correo.com)';
        alertBox.classList.remove('hidden');
        return;
    }

    // Paso 1: Verificar correo
    if (passWrapper.classList.contains('hidden')) {
        btn.innerText = 'Verificando...';
        btn.disabled = true;

        try {
            const res = await fetch('/auth/check', {
                method: 'POST',
                headers: JsonHeaders,
                body: JSON.stringify({ email })
            });
            const data = await res.json();

            passWrapper.classList.remove('hidden');
            emailInput.readOnly = true;
            emailInput.classList.add('opacity-50');
            btn.disabled = false;

            if (data.exists) {
                authMode = 'login';
                document.getElementById('AuthSubtitle').innerText = 'Bienvenido, ingresa tu clave';
                btn.innerText = 'Iniciar Sesión';
            } else {
                authMode = 'register';
                document.getElementById('AuthSubtitle').innerText = 'Correo nuevo: Crea tu clave';
                btn.innerText = 'Crear Cuenta';
            }

            setTimeout(() => passInput.focus(), 100); // Foco al password

        } catch (e) {
            btn.disabled = false;
            console.error(e);
        }
        return;
    }

    // Paso 2: Autenticar
    if (!password) {
        alertBox.innerText = 'Ingresa tu contraseña';
        alertBox.classList.remove('hidden');
        return;
    }

    btn.innerText = 'Procesando...';
    btn.disabled = true;

    try {
        const res = await fetch('/auth/process', {
            method: 'POST',
            headers: JsonHeaders,
            body: JSON.stringify({ email, password, mode: authMode })
        });
        const result = await res.json();

        if (result.success) {
            window.location.reload();
        } else {
            alertBox.innerText = result.message || 'Error';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = (authMode === 'login') ? 'Iniciar Sesión' : 'Crear Cuenta';
        }
    } catch (e) {
        btn.disabled = false;
        console.error(e);
    }
};

// Inicializar y Eventos de Teclado
document.addEventListener('DOMContentLoaded', () => {
    window.updateCartUI();

    // Enter en Buscador
    document.getElementById('q')?.addEventListener('keydown', (e) => e.key === 'Enter' && window.Search());

    // Enter en Modal de Auth
    ['AuthEmail', 'AuthPass'].forEach(id => {
        document.getElementById(id)?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.handleAuthStep();
            }
        });
    });
});
