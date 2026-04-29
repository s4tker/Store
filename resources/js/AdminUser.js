const adminUsers = JSON.parse(document.getElementById('AdminUsersData')?.textContent || '[]');
const adminUsersMap = new Map(adminUsers.map((user) => [String(user.id), user]));
const adminUsersEmailMap = new Map(adminUsers.map((user) => [String(user.correo).toLowerCase(), user]));

document.addEventListener('DOMContentLoaded', () => {
    initAdminUserEditor();
    initAdminUserDelete();
    initAdminUserFilters();
});

// bloque usuarios
function initAdminUserEditor() {
    const form = document.getElementById('FormAdminUsuario');
    const searchInput = document.getElementById('UserEmailSearch');

    if (!form) {
        return;
    }

    searchInput?.addEventListener('input', loadUserFromSearch);
    searchInput?.addEventListener('change', loadUserFromSearch);
    document.getElementById('BtnResetUserForm')?.addEventListener('click', resetAdminUserForm);
    document.getElementById('BtnClearUserEditor')?.addEventListener('click', resetAdminUserForm);

    document.querySelectorAll('[data-load-user]').forEach((button) => {
        button.addEventListener('click', () => {
            const user = adminUsersMap.get(String(button.dataset.loadUser));

            if (user) {
                loadUserIntoForm(user);
            }
        });
    });

    form.addEventListener('submit', submitAdminUserForm);
}

// bloque cargar desde busqueda
function loadUserFromSearch() {
    const email = document.getElementById('UserEmailSearch')?.value.trim().toLowerCase() || '';

    if (!email) {
        return;
    }

    const user = adminUsersEmailMap.get(email);

    if (user) {
        loadUserIntoForm(user);
    }
}

// bloque cargar formulario
function loadUserIntoForm(user) {
    const form = document.getElementById('FormAdminUsuario');
    const methodInput = document.getElementById('UserFormMethod');
    const passwordInput = document.getElementById('UserPassword');

    if (!form || !methodInput || !passwordInput) {
        return;
    }

    form.action = `${form.dataset.updateBase}/${user.id}`;
    methodInput.value = 'PUT';
    document.getElementById('EditingUserId').value = user.id;
    document.getElementById('UserEmail').value = user.correo || '';
    document.getElementById('UserRole').value = user.rol_id || '';
    document.getElementById('UserEmailSearch').value = user.correo || '';

    passwordInput.value = '';
    passwordInput.required = false;
    passwordInput.placeholder = 'escribe una nueva contraseña si deseas cambiarla';

    document.getElementById('UserPasswordHelp').textContent = 'La contraseña actual no se muestra. Solo escribe una nueva si deseas reemplazarla.';
    document.getElementById('UserFormEyebrow').textContent = 'usuario seleccionado';
    document.getElementById('UserFormTitle').textContent = `Editar ${user.correo}`;
    document.getElementById('BtnSubmitUser').textContent = 'Guardar cambios';

    highlightActiveUser(user.id);
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// bloque reset formulario
function resetAdminUserForm() {
    const form = document.getElementById('FormAdminUsuario');
    const passwordInput = document.getElementById('UserPassword');

    if (!form || !passwordInput) {
        return;
    }

    form.reset();
    form.action = form.dataset.storeUrl;
    document.getElementById('UserFormMethod').value = 'POST';
    document.getElementById('EditingUserId').value = '';
    document.getElementById('UserEmailSearch').value = '';
    document.getElementById('UserFormEyebrow').textContent = 'panel de acceso';
    document.getElementById('UserFormTitle').textContent = 'Registrar usuario';
    document.getElementById('BtnSubmitUser').textContent = 'Registrar usuario';
    document.getElementById('UserPasswordHelp').textContent = 'Es obligatoria al crear una cuenta nueva.';

    passwordInput.required = true;
    passwordInput.placeholder = 'mínimo 6 caracteres';
    highlightActiveUser(null);
}

// bloque envio
async function submitAdminUserForm(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const submitButton = document.getElementById('BtnSubmitUser');
    const originalLabel = submitButton?.textContent || 'Guardar';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Guardando...';
    }

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });

        const payload = await response.json();

        if (!response.ok || !payload.success) {
            throw new Error(payload.message || 'No se pudo guardar el usuario.');
        }

        showAdminUserToast(payload.message || 'Usuario guardado correctamente.');
        window.setTimeout(() => window.location.reload(), 650);
    } catch (error) {
        showAdminUserToast(error.message || 'Ocurrió un error inesperado.', true);
    } finally {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = originalLabel;
        }
    }
}

// bloque eliminar
function initAdminUserDelete() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('[data-delete-url]').forEach((button) => {
        button.addEventListener('click', async () => {
            const url = button.dataset.deleteUrl;
            const label = button.dataset.deleteLabel || 'usuario';
            const originalText = button.textContent;

            if (!window.confirm(`¿Eliminar ${label}?`)) {
                return;
            }

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
                    throw new Error(payload.message || 'No se pudo eliminar el usuario.');
                }

                showAdminUserToast(payload.message || 'Usuario eliminado correctamente.');
                window.setTimeout(() => window.location.reload(), 650);
            } catch (error) {
                button.disabled = false;
                button.textContent = originalText;
                showAdminUserToast(error.message || 'Ocurrió un error inesperado.', true);
            }
        });
    });
}

// bloque filtros
function initAdminUserFilters() {
    document.getElementById('UserListSearch')?.addEventListener('input', filterAdminUsers);
    filterAdminUsers();
}

// bloque filtrar lista
function filterAdminUsers() {
    const search = document.getElementById('UserListSearch')?.value.trim().toLowerCase() || '';
    const cards = document.querySelectorAll('.user-admin-item');
    const emptyState = document.getElementById('NoUserResults');
    const countLabel = document.getElementById('UserResultsCount');
    let visibleCount = 0;

    cards.forEach((card) => {
        const isVisible = !search || [
            card.dataset.userEmail,
            card.dataset.userRole,
        ].some((value) => value?.includes(search));

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

// bloque activo
function highlightActiveUser(userId) {
    document.querySelectorAll('.user-admin-item').forEach((card) => {
        card.classList.toggle('is-active', String(card.dataset.loadUser) === String(userId));
    });
}

// bloque toast
function showAdminUserToast(message, isError = false) {
    let toast = document.getElementById('AdminUserToast');

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'AdminUserToast';
        toast.className = 'user-admin-toast';
        document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.classList.remove('is-error', 'is-visible');

    if (isError) {
        toast.classList.add('is-error');
    }

    requestAnimationFrame(() => toast.classList.add('is-visible'));

    window.clearTimeout(showAdminUserToast.timeoutId);
    showAdminUserToast.timeoutId = window.setTimeout(() => {
        toast.classList.remove('is-visible');
    }, 3200);
}
