import Alpine from 'alpinejs'

// bloque base
const CartStorageKey = 'electroshop-cart';

// bloque busqueda
window.Search = function() {
    const QueryText = document.getElementById('q')?.value.trim() || '';
    const Params = new URLSearchParams(window.location.search);

    if (QueryText) {
        Params.set('search', QueryText);
    } else {
        Params.delete('search');
    }

    const Category = document.getElementById('IndexCategoryFilter')?.value || '';
    const Subcategory = document.getElementById('IndexSubcategoryFilter')?.value || '';

    if (Category) {
        Params.set('category', Category);
    }

    if (Subcategory) {
        Params.set('subcategory', Subcategory);
    }

    const Query = Params.toString();
    window.location.href = Query ? `/?${Query}` : '/';
};

window.ApplyCatalogFilters = function() {
    const Params = new URLSearchParams(window.location.search);
    const Category = document.getElementById('IndexCategoryFilter')?.value || '';
    const Subcategory = document.getElementById('IndexSubcategoryFilter')?.value || '';
    const SearchText = document.getElementById('q')?.value.trim() || '';

    if (SearchText) {
        Params.set('search', SearchText);
    } else {
        Params.delete('search');
    }

    if (Category) {
        Params.set('category', Category);
    } else {
        Params.delete('category');
    }

    if (Subcategory) {
        Params.set('subcategory', Subcategory);
    } else {
        Params.delete('subcategory');
    }

    const Query = Params.toString();
    window.location.href = Query ? `/?${Query}` : '/';
};

window.SetCategoryFilter = function(CategoryId) {
    const CategoryInput = document.getElementById('IndexCategoryFilter');
    const SubcategoryInput = document.getElementById('IndexSubcategoryFilter');

    if (CategoryInput) {
        CategoryInput.value = CategoryId;
        SyncSubcategoryOptions();
    }

    if (SubcategoryInput) {
        SubcategoryInput.value = '';
    }

    window.ApplyCatalogFilters();
};

// bloque carrito
window.getCart = () => {
    try {
        const RawCart = JSON.parse(localStorage.getItem(CartStorageKey) || '[]');
        return Array.isArray(RawCart) ? RawCart.map(NormalizeCartItem).filter(Boolean) : [];
    } catch {
        return [];
    }
};

window.setCart = (Cart) => {
    localStorage.setItem(CartStorageKey, JSON.stringify(Cart.map(NormalizeCartItem).filter(Boolean)));
    window.updateCartUI();
};

window.updateCartUI = function() {
    const Cart = window.getCart();
    const Count = Cart.reduce((Total, Item) => Total + Item.qty, 0);
    const Total = Cart.reduce((Sum, Item) => Sum + (Item.price * Item.qty), 0);

    const CountBadge = document.getElementById('CartCount');
    if (CountBadge) {
        CountBadge.innerText = Count;
        CountBadge.classList.toggle('hidden', Count === 0);
        CountBadge.classList.toggle('inline-flex', Count > 0);
    }

    const TotalElement = document.getElementById('CartTotal');
    if (TotalElement) {
        TotalElement.innerText = `S/.${Total.toFixed(2)}`;
    }

    const CheckoutButton = document.getElementById('BtnCheckout');
    if (CheckoutButton) {
        const HasItems = Cart.length > 0;
        CheckoutButton.disabled = !HasItems;
        CheckoutButton.classList.toggle('opacity-60', !HasItems);
        CheckoutButton.classList.toggle('cursor-not-allowed', !HasItems);
    }

    const ItemsContainer = document.getElementById('CartItems');
    if (!ItemsContainer) {
        return;
    }

    if (Cart.length === 0) {
        ItemsContainer.innerHTML = `<div class="py-20 text-center opacity-50"><p class="font-black uppercase text-xs">Vacío</p></div>`;
        return;
    }

    ItemsContainer.innerHTML = Cart.map((Item) => `
        <article class="flex gap-4 p-4 bg-white border border-slate-100 rounded-3xl animate-fade-in">
            <img src="${EscapeHtml(Item.image)}" alt="${EscapeHtml(Item.name)}" class="w-16 h-16 object-contain">
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold truncate text-slate-800">${EscapeHtml(Item.name)}</h4>
                <p class="font-black text-sm italic text-blue-600">S/.${Item.price.toFixed(2)}</p>
                <div class="flex justify-between items-center mt-2 gap-3">
                    <div class="flex gap-4 bg-slate-100 px-3 py-1 rounded-full text-xs font-bold text-slate-600">
                        <button type="button" onclick="changeQty('${Item.key}', -1)">−</button>
                        <span>${Item.qty}</span>
                        <button type="button" onclick="changeQty('${Item.key}', 1)">+</button>
                    </div>
                    <button type="button" onclick="removeFromCart('${Item.key}')" class="text-red-500 text-[9px] font-black uppercase underline">Borrar</button>
                </div>
            </div>
        </article>
    `).join('');
};

window.changeQty = (Key, Delta) => {
    const Cart = window.getCart()
        .map((Item) => {
            if (Item.key !== String(Key)) {
                return Item;
            }

            return {
                ...Item,
                qty: Math.min(Item.maxQty, Item.qty + Delta),
            };
        })
        .filter((Item) => Item.qty > 0);

    window.setCart(Cart);
};

// bloque quitar
window.removeFromCart = (Key) => {
    window.setCart(window.getCart().filter((Item) => Item.key !== String(Key)));
};

// bloque compra
window.GoToCompraForm = function() {
    const Cart = window.getCart();
    const CheckoutButton = document.getElementById('BtnCheckout');

    if (!Cart.length || !CheckoutButton?.dataset.checkoutUrl) {
        return;
    }

    window.location.href = CheckoutButton.dataset.checkoutUrl;
};

// bloque drawer
window.ToggleCart = function(Show) {
    const Drawer = document.getElementById('CartDrawer');
    const Overlay = document.getElementById('CartOverlay');

    if (!Drawer || !Overlay) {
        return;
    }

    if (Show) {
        Drawer.classList.remove('translate-x-full');
        Overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        return;
    }

    Drawer.classList.add('translate-x-full');
    Overlay.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

// bloque menu movil
window.ToggleMobileCatalog = function(Show) {
    const Drawer = document.getElementById('MobileCatalogDrawer');
    const Overlay = document.getElementById('MobileCatalogOverlay');

    if (!Drawer || !Overlay) {
        return;
    }

    if (Show) {
        Drawer.classList.remove('-translate-x-full');
        Overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        return;
    }

    Drawer.classList.add('-translate-x-full');
    Overlay.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

// bloque producto
window.SetMainProductImage = function(Url) {
    const Image = document.getElementById('MainProductImage');
    if (Image && Url) {
        Image.src = Url;
    }
};

window.OpenImageZoom = function() {
    const Modal = document.getElementById('ImageZoomModal');
    if (!Modal) {
        return;
    }

    Modal.classList.remove('hidden');
    Modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
};

window.CloseImageZoom = function() {
    const Modal = document.getElementById('ImageZoomModal');
    if (!Modal) {
        return;
    }

    Modal.classList.add('hidden');
    Modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
};

window.ChangeProductQty = function(Delta) {
    const Input = document.getElementById('ProductQty');
    if (!Input) {
        return;
    }

    const Current = parseInt(Input.value || '1', 10);
    const Min = parseInt(Input.min || '1', 10);
    const Max = parseInt(Input.max || '999', 10);
    const Next = Math.min(Max, Math.max(Min, Current + Delta));
    Input.value = Next;
};

// bloque agregar
window.AddCurrentProductToCart = function(Product) {
    const QtyInput = document.getElementById('ProductQty');
    const Qty = Math.max(1, parseInt(QtyInput?.value || '1', 10));
    const Cart = window.getCart();
    const NewItem = NormalizeCartItem({
        ...Product,
        qty: Qty,
    });

    if (!NewItem) {
        return;
    }

    const Existing = Cart.find((Item) => Item.key === NewItem.key);

    if (Existing) {
        Existing.qty = Math.min(Existing.maxQty, Existing.qty + Qty);
    } else {
        Cart.push(NewItem);
    }

    window.setCart(Cart);
    window.ToggleCart(true);
};

// bloque zoom
function UpdateProductZoomOrigin(Frame, ClientX, ClientY) {
    const Bounds = Frame.getBoundingClientRect();
    const X = ((ClientX - Bounds.left) / Bounds.width) * 100;
    const Y = ((ClientY - Bounds.top) / Bounds.height) * 100;

    Frame.style.setProperty('--zoom-x', `${Math.min(100, Math.max(0, X))}%`);
    Frame.style.setProperty('--zoom-y', `${Math.min(100, Math.max(0, Y))}%`);
}

function InitProductImageZoom() {
    document.querySelectorAll('[data-product-zoom]').forEach((Frame) => {
        let TouchZoomActive = false;

        // zoom pc
        Frame.addEventListener('mouseenter', () => {
            if (!window.matchMedia('(hover: hover)').matches) {
                return;
            }

            Frame.classList.add('is-zoomed');
        });

        Frame.addEventListener('mousemove', (Event) => {
            if (window.matchMedia('(hover: hover)').matches) {
                Frame.classList.add('is-zoomed');
            }

            UpdateProductZoomOrigin(Frame, Event.clientX, Event.clientY);
        });

        Frame.addEventListener('mouseleave', () => {
            Frame.classList.remove('is-zoomed');
            Frame.style.setProperty('--zoom-x', '50%');
            Frame.style.setProperty('--zoom-y', '50%');
        });

        // zoom movil
        Frame.addEventListener('click', (Event) => {
            if (window.matchMedia('(hover: hover)').matches) {
                return;
            }

            UpdateProductZoomOrigin(Frame, Event.clientX, Event.clientY);
            TouchZoomActive = !TouchZoomActive;
            Frame.classList.toggle('is-zoomed', TouchZoomActive);
        });

        Frame.addEventListener('touchmove', (Event) => {
            if (!Event.touches.length) {
                return;
            }

            const Touch = Event.touches[0];
            UpdateProductZoomOrigin(Frame, Touch.clientX, Touch.clientY);
            TouchZoomActive = true;
            Frame.classList.add('is-zoomed');
        }, { passive: true });

        Frame.addEventListener('touchend', () => {
            if (!TouchZoomActive) {
                Frame.classList.remove('is-zoomed');
            }
        });
    });
}

// bloque comparar
window.ToggleCompareProduct = function(Product) {
    const CompareKey = 'electroshop-compare';
    let Compare = [];

    try {
        Compare = JSON.parse(localStorage.getItem(CompareKey) || '[]');
    } catch {
        Compare = [];
    }

    const Exists = Compare.find((Item) => ItemMatchesId(Item, Product.id));

    if (Exists) {
        Compare = Compare.filter((Item) => !ItemMatchesId(Item, Product.id));
        localStorage.setItem(CompareKey, JSON.stringify(Compare));
        window.alert('Producto retirado de comparar.');
        return;
    }

    if (Compare.length >= 4) {
        window.alert('Solo puedes comparar hasta 4 productos.');
        return;
    }

    Compare.push(Product);
    localStorage.setItem(CompareKey, JSON.stringify(Compare));
    window.alert('Producto agregado para comparar.');
};

// bloque filtros
function SyncSubcategoryOptions() {
    const CategoryInput = document.getElementById('IndexCategoryFilter');
    const SubcategoryInput = document.getElementById('IndexSubcategoryFilter');

    if (!CategoryInput || !SubcategoryInput) {
        return;
    }

    const SelectedCategory = CategoryInput.value;

    Array.from(SubcategoryInput.options).forEach((Option, Index) => {
        if (Index === 0) {
            Option.hidden = false;
            return;
        }

        const Matches = !SelectedCategory || Option.dataset.parent === SelectedCategory;
        Option.hidden = !Matches;

        if (!Matches && Option.selected) {
            Option.selected = false;
        }
    });
}

// bloque arranque
document.addEventListener('DOMContentLoaded', () => {
    window.updateCartUI();
    SyncSubcategoryOptions();
    InitProductImageZoom();

    document.getElementById('q')?.addEventListener('keydown', (Event) => Event.key === 'Enter' && window.Search());

    document.getElementById('IndexCategoryFilter')?.addEventListener('change', () => {
        SyncSubcategoryOptions();
        const SubcategoryInput = document.getElementById('IndexSubcategoryFilter');
        if (SubcategoryInput) {
            SubcategoryInput.value = '';
        }
    });
});

// bloque sync
window.addEventListener('storage', (Event) => {
    if (Event.key === CartStorageKey || Event.key === null) {
        window.updateCartUI();
    }
});

// bloque ayuda
function NormalizeCartItem(Item) {
    if (!Item) {
        return null;
    }

    const ProductId = Number(Item.productId || Item.id || 0);
    const VariantId = Number(Item.variantId || 0) || null;
    const MaxQty = Math.max(1, Number(Item.maxQty || 99) || 99);
    const Qty = Math.min(MaxQty, Math.max(1, Number(Item.qty || 1) || 1));
    const Key = VariantId ? `v-${VariantId}` : `p-${ProductId}`;

    if (!ProductId) {
        return null;
    }

    return {
        key: Item.key || Key,
        id: ProductId,
        productId: ProductId,
        variantId: VariantId,
        sku: Item.sku || '',
        slug: Item.slug || '',
        name: String(Item.name || 'Producto'),
        price: Number(Item.price || 0),
        image: String(Item.image || ''),
        qty: Qty,
        maxQty: MaxQty,
    };
}

function ItemMatchesId(Item, ProductId) {
    return Number(Item?.id || Item?.productId || 0) === Number(ProductId || 0);
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

window.Alpine = Alpine
Alpine.start()
