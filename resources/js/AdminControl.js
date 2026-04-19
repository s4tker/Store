// bloque base
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const adminProducts = JSON.parse(document.getElementById('AdminProductsData')?.textContent || '[]');
const adminProductsMap = new Map(adminProducts.map((product) => [String(product.id), product]));
const AdminSectionStorageKey = 'electroshop-admin-section';

let previewUrls = [];

// bloque arranque
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initSectionNavigation();
    initProductEditor();
    initCategoryHelpers();
    initCategoryEditor();
    initBrandEditor();
    initFormHandlers();
    initDeleteButtons();
});

// bloque sidebar
function initSidebar() {
    const sidebar = document.getElementById('AdminSidebar');
    const overlay = document.getElementById('AdminSidebarOverlay');
    const toggle = document.getElementById('AdminSidebarToggle');

    if (!sidebar || !overlay || !toggle) {
        return;
    }

    const closeSidebar = () => {
        sidebar.classList.remove('is-open');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    const openSidebar = () => {
        sidebar.classList.add('is-open');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    toggle.addEventListener('click', () => {
        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
            return;
        }

        openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.admin-nav-item').forEach((item) => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });
}

// bloque secciones
function initSectionNavigation() {
    const navItems = document.querySelectorAll('.admin-nav-item');
    const sections = document.querySelectorAll('.admin-section');
    const setActiveSection = (target) => {
        if (!target) {
            return;
        }

        navItems.forEach((item) => item.classList.toggle('active', item.dataset.section === target));
        sections.forEach((section) => section.classList.toggle('hidden', section.id !== `section-${target}`));
        window.sessionStorage.setItem(AdminSectionStorageKey, target);
    };

    navItems.forEach((button) => {
        button.addEventListener('click', () => {
            setActiveSection(button.dataset.section);
        });
    });

    setActiveSection(window.sessionStorage.getItem(AdminSectionStorageKey) || navItems[0]?.dataset.section);
}

// bloque producto
function initProductEditor() {
    const form = document.getElementById('FormAddProducto');
    const categorySelect = document.getElementById('SelectCat');
    const subcategorySelect = document.getElementById('SelectSub');
    const priceInput = document.getElementById('InpPrecio');
    const offerInput = document.getElementById('InpOferta');
    const discountLabel = document.getElementById('TxtDescuento');
    const fileInput = document.getElementById('ProductImages');
    const fileName = document.getElementById('FileName');
    const addAttributeButton = document.getElementById('BtnAddAttribute');
    const resetButton = document.getElementById('BtnResetProductForm');
    const clearButton = document.getElementById('BtnClearEditor');
    const searchInput = document.getElementById('ProductSearch');
    const brandFilter = document.getElementById('FilterBrand');
    const categoryFilter = document.getElementById('FilterCategory');

    if (!form || !categorySelect || !subcategorySelect) {
        return;
    }

    const syncCategoryState = (selectedSubcategory = '') => {
        const option = categorySelect.options[categorySelect.selectedIndex];
        const subcategories = JSON.parse(option?.dataset.subs || '[]');

        subcategorySelect.innerHTML = '';

        if (!subcategories.length) {
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Sin subcategorías</option>';
            return;
        }

        subcategorySelect.disabled = false;
        subcategorySelect.innerHTML = '<option value="">Seleccionar</option>';

        subcategories.forEach((subcategory) => {
            const optionElement = document.createElement('option');
            optionElement.value = subcategory.Id;
            optionElement.textContent = subcategory.Nombre;

            if (String(subcategory.Id) === String(selectedSubcategory)) {
                optionElement.selected = true;
            }

            subcategorySelect.appendChild(optionElement);
        });
    };

    const updateDiscount = () => {
        const price = Number.parseFloat(priceInput?.value || '0');
        const offer = Number.parseFloat(offerInput?.value || '0');

        if (price > 0 && offer > 0 && offer < price) {
            const percent = Math.round(((price - offer) / price) * 100);
            discountLabel.textContent = `${percent}% desc.`;
            discountLabel.classList.add('is-positive');
            return;
        }

        discountLabel.textContent = '0% desc.';
        discountLabel.classList.remove('is-positive');
    };

    categorySelect.addEventListener('change', () => syncCategoryState());
    priceInput?.addEventListener('input', updateDiscount);
    offerInput?.addEventListener('input', updateDiscount);

    fileInput?.addEventListener('change', () => {
        renderNewImagesPreview(fileInput.files || []);

        if (!fileInput.files?.length) {
            fileName.textContent = 'Puedes seleccionar varias imágenes a la vez.';
            return;
        }

        fileName.textContent = `${fileInput.files.length} imagen(es) lista(s) para subir.`;
    });

    addAttributeButton?.addEventListener('click', () => addAttributeRow());
    resetButton?.addEventListener('click', () => resetProductForm());
    clearButton?.addEventListener('click', () => resetProductForm());

    document.querySelectorAll('[data-load-product]').forEach((button) => {
        button.addEventListener('click', () => {
            const productId = button.dataset.loadProduct;
            const product = adminProductsMap.get(String(productId));

            if (!product) {
                return;
            }

            loadProductIntoForm(product);
        });
    });

    [searchInput, brandFilter, categoryFilter].forEach((element) => {
        element?.addEventListener('input', filterProducts);
        element?.addEventListener('change', filterProducts);
    });

    syncCategoryState();
    updateDiscount();
    filterProducts();
}

// bloque atributos
function addAttributeRow(name = '', value = '') {
    const container = document.getElementById('ContainerAtributos');

    if (!container) {
        return;
    }

    const row = document.createElement('div');
    row.className = 'attribute-row';
    row.innerHTML = `
        <input type="text" name="attr_nombre[]" class="input-admin" placeholder="Ej: RAM" value="${escapeHtml(name)}">
        <input type="text" name="attr_valor[]" class="input-admin" placeholder="Ej: 16 GB" value="${escapeHtml(value)}">
        <button type="button" class="attribute-remove" aria-label="Eliminar atributo">×</button>
    `;

    row.querySelector('.attribute-remove')?.addEventListener('click', () => row.remove());
    container.appendChild(row);
}

// bloque cargar
function loadProductIntoForm(product) {
    const form = document.getElementById('FormAddProducto');
    const formTitle = document.getElementById('ProductFormTitle');
    const formEyebrow = document.getElementById('ProductFormEyebrow');
    const submitButton = document.getElementById('BtnSubmitProduct');
    const methodInput = document.getElementById('ProductFormMethod');
    const editingInput = document.getElementById('EditingProductId');
    const categorySelect = document.getElementById('SelectCat');

    if (!form || !methodInput || !editingInput || !categorySelect) {
        return;
    }

    clearRemovedImages();
    clearNewImages();
    clearAttributeRows();

    form.action = `${form.dataset.updateBase}/${product.id}`;
    methodInput.value = 'PUT';
    editingInput.value = product.id;

    formTitle.textContent = `Editar ${product.nombre}`;
    formEyebrow.textContent = 'Producto seleccionado';
    submitButton.textContent = 'Guardar cambios';

    document.getElementById('ProductName').value = product.nombre || '';
    document.getElementById('ProductBrand').value = product.marca_id || '';
    document.getElementById('InpPrecio').value = product.precio || '';
    document.getElementById('InpOferta').value = product.precio_oferta || '';
    document.getElementById('ProductStock').value = product.stock ?? 0;
    document.getElementById('ProductStatus').value = product.estado || 'Activo';
    document.getElementById('ProductDescription').value = product.descripcion || '';

    categorySelect.value = product.categoria_id || '';
    categorySelect.dispatchEvent(new Event('change'));
    document.getElementById('SelectSub').value = product.subcategoria_id || '';

    (product.atributos || []).forEach((attribute) => addAttributeRow(attribute.nombre, attribute.valor));
    renderExistingImages(product.imagenes || []);
    highlightActiveProduct(product.id);

    document.getElementById('InpPrecio')?.dispatchEvent(new Event('input'));
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// bloque reset producto
function resetProductForm() {
    const form = document.getElementById('FormAddProducto');
    const methodInput = document.getElementById('ProductFormMethod');
    const editingInput = document.getElementById('EditingProductId');
    const formTitle = document.getElementById('ProductFormTitle');
    const formEyebrow = document.getElementById('ProductFormEyebrow');
    const submitButton = document.getElementById('BtnSubmitProduct');
    const categorySelect = document.getElementById('SelectCat');
    const subcategorySelect = document.getElementById('SelectSub');
    const fileName = document.getElementById('FileName');
    const statusSelect = document.getElementById('ProductStatus');

    if (!form || !methodInput || !editingInput || !categorySelect || !subcategorySelect || !statusSelect) {
        return;
    }

    form.reset();
    form.action = form.dataset.storeUrl;
    methodInput.value = 'POST';
    editingInput.value = '';

    formTitle.textContent = 'Registrar producto';
    formEyebrow.textContent = 'Nuevo producto';
    submitButton.textContent = 'Registrar producto';
    statusSelect.value = 'Activo';

    subcategorySelect.disabled = true;
    subcategorySelect.innerHTML = '<option value="">Sin subcategorías</option>';

    fileName.textContent = 'Puedes seleccionar varias imágenes a la vez.';

    clearRemovedImages();
    clearNewImages();
    clearAttributeRows();
    renderExistingImages([]);
    highlightActiveProduct(null);
    document.getElementById('TxtDescuento')?.classList.remove('is-positive');
    document.getElementById('TxtDescuento').textContent = '0% desc.';
}

// bloque imagenes guardadas
function renderExistingImages(images) {
    const grid = document.getElementById('ExistingImagesGrid');

    if (!grid) {
        return;
    }

    grid.innerHTML = '';

    if (!images.length) {
        grid.innerHTML = '<div class="empty-inline-state">Aún no hay imágenes guardadas.</div>';
        return;
    }

    images.forEach((image) => {
        const card = document.createElement('article');
        card.className = 'image-card';
        card.dataset.imageId = image.id;
        card.innerHTML = `
            <img src="${image.url}" alt="Imagen del producto" class="image-card-preview">
            <div class="image-card-footer">
                <span>Orden ${image.orden}</span>
                <button type="button" class="image-card-remove">Quitar</button>
            </div>
        `;

        card.querySelector('.image-card-remove')?.addEventListener('click', () => toggleImageRemoval(image.id, card));
        grid.appendChild(card);
    });
}

// bloque imagenes nuevas
function renderNewImagesPreview(files) {
    const grid = document.getElementById('NewImagesPreview');

    if (!grid) {
        return;
    }

    previewUrls.forEach((url) => URL.revokeObjectURL(url));
    previewUrls = [];
    grid.innerHTML = '';

    if (!files.length) {
        grid.innerHTML = '<div class="empty-inline-state">Las nuevas imágenes aparecerán aquí.</div>';
        return;
    }

    Array.from(files).forEach((file, index) => {
        const previewUrl = URL.createObjectURL(file);
        previewUrls.push(previewUrl);

        const card = document.createElement('article');
        card.className = 'image-card image-card-new';
        card.innerHTML = `
            <img src="${previewUrl}" alt="Vista previa ${index + 1}" class="image-card-preview">
            <div class="image-card-footer">
                <span>${file.name}</span>
                <span>${Math.round(file.size / 1024)} KB</span>
            </div>
        `;

        grid.appendChild(card);
    });
}

// bloque quitar imagen
function toggleImageRemoval(imageId, card) {
    const container = document.getElementById('RemovedImagesContainer');

    if (!container || !card) {
        return;
    }

    const existingInput = container.querySelector(`input[value="${imageId}"]`);

    if (existingInput) {
        existingInput.remove();
        card.classList.remove('is-marked-remove');
        return;
    }

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'RemoveImagenes[]';
    input.value = imageId;
    container.appendChild(input);
    card.classList.add('is-marked-remove');
}

// bloque limpiar imagenes
function clearRemovedImages() {
    const container = document.getElementById('RemovedImagesContainer');

    if (container) {
        container.innerHTML = '';
    }
}

// bloque limpiar carga
function clearNewImages() {
    const input = document.getElementById('ProductImages');
    const fileName = document.getElementById('FileName');

    if (input) {
        input.value = '';
    }

    if (fileName) {
        fileName.textContent = 'Puedes seleccionar varias imágenes a la vez.';
    }

    renderNewImagesPreview([]);
}

// bloque limpiar atributos
function clearAttributeRows() {
    const container = document.getElementById('ContainerAtributos');

    if (container) {
        container.innerHTML = '';
    }
}

// bloque activo
function highlightActiveProduct(productId) {
    document.querySelectorAll('.product-card-admin').forEach((card) => {
        card.classList.toggle('is-active', String(card.dataset.productId) === String(productId));
    });
}

// bloque filtros
function filterProducts() {
    const search = document.getElementById('ProductSearch')?.value.trim().toLowerCase() || '';
    const brand = document.getElementById('FilterBrand')?.value.trim().toLowerCase() || '';
    const category = document.getElementById('FilterCategory')?.value.trim().toLowerCase() || '';
    const cards = document.querySelectorAll('.product-card-admin');
    const emptyState = document.getElementById('NoProductResults');
    const countLabel = document.getElementById('ProductResultsCount');

    let visibleCount = 0;

    cards.forEach((card) => {
        const matchesSearch = !search || [
            card.dataset.productName,
            card.dataset.productBrand,
            card.dataset.productCategory,
            card.dataset.productSku,
        ].some((value) => value?.includes(search));

        const matchesBrand = !brand || card.dataset.productBrand === brand;
        const matchesCategory = !category || card.dataset.productCategory === category;
        const isVisible = matchesSearch && matchesBrand && matchesCategory;

        card.classList.toggle('hidden', !isVisible);

        if (isVisible) {
            visibleCount += 1;
        }
    });

    if (countLabel) {
        countLabel.textContent = `${visibleCount} resultado(s)`;
    }

    if (emptyState) {
        emptyState.classList.toggle('hidden', visibleCount > 0);
    }
}

// bloque categoria base
function initCategoryHelpers() {
    const typeSelect = document.getElementById('SelectCategoryType');
    const parentSelect = document.getElementById('CategoryParentSelect');

    if (!typeSelect || !parentSelect) {
        return;
    }

    const syncCategoryType = () => {
        const isSubcategory = typeSelect.value === 'subcategoria';
        parentSelect.disabled = !isSubcategory;

        if (!isSubcategory) {
            parentSelect.value = '';
        }
    };

    typeSelect.addEventListener('change', syncCategoryType);
    syncCategoryType();
}

// bloque editar categoria
function initCategoryEditor() {
    const form = document.getElementById('FormAddCategoria');
    const resetButton = document.getElementById('BtnResetCategoryForm');

    if (!form) {
        return;
    }

    resetButton?.addEventListener('click', resetCategoryForm);

    document.querySelectorAll('[data-edit-category]').forEach((button) => {
        button.addEventListener('click', () => {
            const methodInput = document.getElementById('CategoryFormMethod');
            const editingInput = document.getElementById('EditingCategoryId');
            const typeSelect = document.getElementById('SelectCategoryType');
            const parentSelect = document.getElementById('CategoryParentSelect');
            const title = document.getElementById('CategoryFormTitle');
            const eyebrow = document.getElementById('CategoryFormEyebrow');
            const submitButton = document.getElementById('BtnSubmitCategory');

            if (!methodInput || !editingInput || !typeSelect || !parentSelect) {
                return;
            }

            const id = button.dataset.editCategory;
            const type = button.dataset.categoryType || 'principal';
            const parentId = button.dataset.categoryParent || '';

            form.action = `${form.dataset.updateBase}/${id}`;
            methodInput.value = 'PUT';
            editingInput.value = id || '';
            document.getElementById('CategoryName').value = button.dataset.categoryName || '';
            typeSelect.value = type;
            typeSelect.dispatchEvent(new Event('change'));
            parentSelect.value = parentId;

            if (title) {
                title.textContent = `Editar ${button.dataset.categoryName || 'categoría'}`;
            }

            if (eyebrow) {
                eyebrow.textContent = 'Edición de categoría';
            }

            if (submitButton) {
                submitButton.textContent = 'Guardar cambios';
            }

            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
}

// bloque reset categoria
function resetCategoryForm() {
    const form = document.getElementById('FormAddCategoria');
    const methodInput = document.getElementById('CategoryFormMethod');
    const editingInput = document.getElementById('EditingCategoryId');
    const typeSelect = document.getElementById('SelectCategoryType');
    const parentSelect = document.getElementById('CategoryParentSelect');
    const title = document.getElementById('CategoryFormTitle');
    const eyebrow = document.getElementById('CategoryFormEyebrow');
    const submitButton = document.getElementById('BtnSubmitCategory');

    if (!form || !methodInput || !editingInput || !typeSelect || !parentSelect) {
        return;
    }

    form.reset();
    form.action = form.dataset.storeUrl;
    methodInput.value = 'POST';
    editingInput.value = '';
    typeSelect.value = 'principal';
    parentSelect.value = '';
    parentSelect.disabled = true;

    if (title) {
        title.textContent = 'Registrar categoría';
    }

    if (eyebrow) {
        eyebrow.textContent = 'Jerarquía del catálogo';
    }

    if (submitButton) {
        submitButton.textContent = 'Guardar categoría';
    }
}

// bloque editar marca
function initBrandEditor() {
    const form = document.getElementById('FormAddMarca');
    const resetButton = document.getElementById('BtnResetBrandForm');

    if (!form) {
        return;
    }

    resetButton?.addEventListener('click', resetBrandForm);

    document.querySelectorAll('[data-edit-brand]').forEach((button) => {
        button.addEventListener('click', () => {
            const methodInput = document.getElementById('BrandFormMethod');
            const editingInput = document.getElementById('EditingBrandId');
            const title = document.getElementById('BrandFormTitle');
            const eyebrow = document.getElementById('BrandFormEyebrow');
            const submitButton = document.getElementById('BtnSubmitBrand');

            if (!methodInput || !editingInput) {
                return;
            }

            const id = button.dataset.editBrand;
            form.action = `${form.dataset.updateBase}/${id}`;
            methodInput.value = 'PUT';
            editingInput.value = id || '';
            document.getElementById('BrandName').value = button.dataset.brandName || '';

            if (title) {
                title.textContent = `Editar ${button.dataset.brandName || 'marca'}`;
            }

            if (eyebrow) {
                eyebrow.textContent = 'Edición de marca';
            }

            if (submitButton) {
                submitButton.textContent = 'Guardar cambios';
            }

            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
}

// bloque reset marca
function resetBrandForm() {
    const form = document.getElementById('FormAddMarca');
    const methodInput = document.getElementById('BrandFormMethod');
    const editingInput = document.getElementById('EditingBrandId');
    const title = document.getElementById('BrandFormTitle');
    const eyebrow = document.getElementById('BrandFormEyebrow');
    const submitButton = document.getElementById('BtnSubmitBrand');

    if (!form || !methodInput || !editingInput) {
        return;
    }

    form.reset();
    form.action = form.dataset.storeUrl;
    methodInput.value = 'POST';
    editingInput.value = '';

    if (title) {
        title.textContent = 'Nueva marca';
    }

    if (eyebrow) {
        eyebrow.textContent = 'Fabricantes';
    }

    if (submitButton) {
        submitButton.textContent = 'Guardar marca';
    }
}

// bloque guardado
function initFormHandlers() {
    ['FormAddProducto', 'FormAddCategoria', 'FormAddMarca'].forEach((formId) => {
        const form = document.getElementById(formId);

        if (!form) {
            return;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const originalLabel = submitButton?.textContent || 'Guardar';
            const body = new FormData(form);

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Guardando...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'No se pudo guardar.');
                }

                showToast(payload.message || 'Guardado correctamente.');

                if (formId === 'FormAddProducto') {
                    window.sessionStorage.setItem(AdminSectionStorageKey, 'productos');
                    window.setTimeout(() => window.location.reload(), 700);
                    return;
                }

                form.reset();
                resetCategoryFormState(formId);
                if (formId === 'FormAddCategoria') {
                    window.sessionStorage.setItem(AdminSectionStorageKey, 'categorias');
                    resetCategoryForm();
                }

                if (formId === 'FormAddMarca') {
                    window.sessionStorage.setItem(AdminSectionStorageKey, 'marcas');
                    resetBrandForm();
                }

                window.setTimeout(() => window.location.reload(), 700);
            } catch (error) {
                showToast(error.message || 'Error inesperado.', true);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalLabel;
                }
            }
        });
    });
}

// bloque apoyo categoria
function resetCategoryFormState(formId) {
    if (formId === 'FormAddCategoria') {
        resetCategoryForm();
    }
}

// bloque eliminar
function initDeleteButtons() {
    document.querySelectorAll('[data-delete-url]').forEach((button) => {
        button.addEventListener('click', async () => {
            const url = button.dataset.deleteUrl;
            const label = button.dataset.deleteLabel || 'registro';

            if (!window.confirm(`¿Eliminar ${label}?`)) {
                return;
            }

            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = '...';

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'No se pudo eliminar.');
                }

                showToast(payload.message || 'Eliminado correctamente.');
                window.setTimeout(() => window.location.reload(), 700);
            } catch (error) {
                button.disabled = false;
                button.textContent = originalText;
                showToast(error.message || 'Error inesperado.', true);
            }
        });
    });
}

// bloque toast
function showToast(message, isError = false) {
    const toast = document.getElementById('Toast');

    if (!toast) {
        return;
    }

    toast.textContent = message;
    toast.classList.remove('is-error', 'is-visible');

    if (isError) {
        toast.classList.add('is-error');
    }

    requestAnimationFrame(() => toast.classList.add('is-visible'));

    window.clearTimeout(showToast.timeoutId);
    showToast.timeoutId = window.setTimeout(() => {
        toast.classList.remove('is-visible');
    }, 3500);
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('"', '&quot;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;');
}
