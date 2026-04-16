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
    const params = new URLSearchParams(window.location.search);

    if (q) {
        params.set('search', q);
    } else {
        params.delete('search');
    }

    const category = document.getElementById('IndexCategoryFilter')?.value || '';
    const subcategory = document.getElementById('IndexSubcategoryFilter')?.value || '';

    if (category) {
        params.set('category', category);
    }

    if (subcategory) {
        params.set('subcategory', subcategory);
    }

    const query = params.toString();
    const url = query ? `/?${query}` : '/';
    window.location.href = url;
};

window.ApplyCatalogFilters = function() {
    const params = new URLSearchParams(window.location.search);
    const category = document.getElementById('IndexCategoryFilter')?.value || '';
    const subcategory = document.getElementById('IndexSubcategoryFilter')?.value || '';
    const search = document.getElementById('q')?.value.trim() || '';

    if (search) {
        params.set('search', search);
    } else {
        params.delete('search');
    }

    if (category) {
        params.set('category', category);
    } else {
        params.delete('category');
    }

    if (subcategory) {
        params.set('subcategory', subcategory);
    } else {
        params.delete('subcategory');
    }

    const query = params.toString();
    window.location.href = query ? `/?${query}` : '/';
};

window.SetCategoryFilter = function(categoryId) {
    const categoryInput = document.getElementById('IndexCategoryFilter');
    const subcategoryInput = document.getElementById('IndexSubcategoryFilter');

    if (categoryInput) {
        categoryInput.value = categoryId;
        syncSubcategoryOptions();
    }

    if (subcategoryInput) {
        subcategoryInput.value = '';
    }

    window.ApplyCatalogFilters();
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

window.ToggleMobileCatalog = function(show) {
    const drawer = document.getElementById('MobileCatalogDrawer');
    const overlay = document.getElementById('MobileCatalogOverlay');

    if (!drawer || !overlay) return;

    if (show) {
        drawer.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        return;
    }

    drawer.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

window.SetMainProductImage = function(url) {
    const image = document.getElementById('MainProductImage');
    if (image && url) {
        image.src = url;
    }
};

window.OpenImageZoom = function() {
    const modal = document.getElementById('ImageZoomModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
};

window.CloseImageZoom = function() {
    const modal = document.getElementById('ImageZoomModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
};

window.ChangeProductQty = function(delta) {
    const input = document.getElementById('ProductQty');
    if (!input) return;

    const current = parseInt(input.value || '1', 10);
    const min = parseInt(input.min || '1', 10);
    const max = parseInt(input.max || '999', 10);
    const next = Math.min(max, Math.max(min, current + delta));
    input.value = next;
};

window.AddCurrentProductToCart = function(product) {
    const qtyInput = document.getElementById('ProductQty');
    const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10));
    const cart = window.getCart();
    const existing = cart.find((item) => item.id === product.id);

    if (existing) {
        existing.qty = Math.min((existing.qty || 0) + qty, product.maxQty || 99);
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            qty: Math.min(qty, product.maxQty || 99),
        });
    }

    window.setCart(cart);
    window.ToggleCart(true);
};

function updateProductZoomOrigin(frame, clientX, clientY) {
    const bounds = frame.getBoundingClientRect();
    const x = ((clientX - bounds.left) / bounds.width) * 100;
    const y = ((clientY - bounds.top) / bounds.height) * 100;

    frame.style.setProperty('--zoom-x', `${Math.min(100, Math.max(0, x))}%`);
    frame.style.setProperty('--zoom-y', `${Math.min(100, Math.max(0, y))}%`);
}

function initProductImageZoom() {
    document.querySelectorAll('[data-product-zoom]').forEach((frame) => {
        let touchZoomActive = false;

        // este bloque activa el zoom en escritorio
        frame.addEventListener('mouseenter', () => {
            if (!window.matchMedia('(hover: hover)').matches) return;
            frame.classList.add('is-zoomed');
        });

        frame.addEventListener('mousemove', (event) => {
            if (window.matchMedia('(hover: hover)').matches) {
                frame.classList.add('is-zoomed');
            }
            updateProductZoomOrigin(frame, event.clientX, event.clientY);
        });

        frame.addEventListener('mouseleave', () => {
            frame.classList.remove('is-zoomed');
            frame.style.setProperty('--zoom-x', '50%');
            frame.style.setProperty('--zoom-y', '50%');
        });

        // este bloque activa el zoom en celular al tocar
        frame.addEventListener('click', (event) => {
            if (window.matchMedia('(hover: hover)').matches) return;

            updateProductZoomOrigin(frame, event.clientX, event.clientY);
            touchZoomActive = !touchZoomActive;
            frame.classList.toggle('is-zoomed', touchZoomActive);
        });

        frame.addEventListener('touchmove', (event) => {
            if (!event.touches.length) return;

            const touch = event.touches[0];
            updateProductZoomOrigin(frame, touch.clientX, touch.clientY);
            touchZoomActive = true;
            frame.classList.add('is-zoomed');
        }, { passive: true });

        frame.addEventListener('touchend', () => {
            if (!touchZoomActive) {
                frame.classList.remove('is-zoomed');
            }
        });
    });
}
window.ToggleCompareProduct = function(product) {
    const key = 'electroshop-compare';
    let compare = [];

    try {
        compare = JSON.parse(localStorage.getItem(key) || '[]');
    } catch {
        compare = [];
    }

    const exists = compare.find((item) => item.id === product.id);

    if (exists) {
        compare = compare.filter((item) => item.id !== product.id);
        localStorage.setItem(key, JSON.stringify(compare));
        window.alert('Producto retirado de comparar.');
        return;
    }

    if (compare.length >= 4) {
        window.alert('Solo puedes comparar hasta 4 productos.');
        return;
    }

    compare.push(product);
    localStorage.setItem(key, JSON.stringify(compare));
    window.alert('Producto agregado para comparar.');
};

function syncSubcategoryOptions() {
    const categoryInput = document.getElementById('IndexCategoryFilter');
    const subcategoryInput = document.getElementById('IndexSubcategoryFilter');

    if (!categoryInput || !subcategoryInput) return;

    const selectedCategory = categoryInput.value;

    Array.from(subcategoryInput.options).forEach((option, index) => {
        if (index === 0) {
            option.hidden = false;
            return;
        }

        const matches = !selectedCategory || option.dataset.parent === selectedCategory;
        option.hidden = !matches;

        if (!matches && option.selected) {
            option.selected = false;
        }
    });
}

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
    syncSubcategoryOptions();
    initProductImageZoom();

    // Enter en Buscador
    document.getElementById('q')?.addEventListener('keydown', (e) => e.key === 'Enter' && window.Search());
    document.getElementById('IndexCategoryFilter')?.addEventListener('change', () => {
        syncSubcategoryOptions();
        const subcategoryInput = document.getElementById('IndexSubcategoryFilter');
        if (subcategoryInput) subcategoryInput.value = '';
    });

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

import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()
