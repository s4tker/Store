// bloque base
const Token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const JsonHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': Token,
    'X-Requested-With': 'XMLHttpRequest',
};

let authMode = 'login';

// bloque login
function ResetAuthForm() {
    const EmailInput = document.getElementById('AuthEmail');
    const PassInput = document.getElementById('AuthPass');
    const PassWrapper = document.getElementById('PassWrapper');
    const Subtitle = document.getElementById('AuthSubtitle');
    const Button = document.getElementById('AuthBtn');
    const AlertBox = document.getElementById('AuthAlert');

    if (!EmailInput || !PassInput || !PassWrapper || !Subtitle || !Button || !AlertBox) {
        return;
    }

    EmailInput.readOnly = false;
    EmailInput.value = '';
    EmailInput.classList.remove('opacity-50');
    PassInput.value = '';
    PassWrapper.classList.add('hidden');
    Subtitle.innerText = 'Ingresa tu correo para continuar';
    Button.innerText = 'Continuar';
    AlertBox.classList.add('hidden');
}

// bloque ojo
window.togglePassword = function() {
    const PassInput = document.getElementById('AuthPass');
    const EyeIcon = document.getElementById('eyeIcon');

    if (!PassInput || !EyeIcon) {
        return;
    }

    if (PassInput.type === 'password') {
        PassInput.type = 'text';
        EyeIcon.classList.add('text-blue-600');
        return;
    }

    PassInput.type = 'password';
    EyeIcon.classList.remove('text-blue-600');
};

// bloque validacion
function ValidateEmail(Email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(Email);
}

window.handleAuthStep = async function() {
    const EmailInput = document.getElementById('AuthEmail');
    const PassInput = document.getElementById('AuthPass');
    const PassWrapper = document.getElementById('PassWrapper');
    const AlertBox = document.getElementById('AuthAlert');
    const Button = document.getElementById('AuthBtn');

    if (!EmailInput || !PassInput || !PassWrapper || !AlertBox || !Button) {
        return;
    }

    const Email = EmailInput.value.trim();
    const Password = PassInput.value.trim();
    const RedirectInput = document.getElementById('AuthRedirect');
    const RedirectTo = RedirectInput?.value || '';

    AlertBox.classList.add('hidden');

    // bloque correo
    if (!ValidateEmail(Email)) {
        AlertBox.innerText = 'Ingresa un correo válido';
        AlertBox.classList.remove('hidden');
        return;
    }

    // bloque verificacion
    if (PassWrapper.classList.contains('hidden')) {
        Button.innerText = 'Verificando...';
        Button.disabled = true;

        try {
            const Response = await fetch('/auth/check', {
                method: 'POST',
                headers: JsonHeaders,
                body: JSON.stringify({ email: Email }),
            });
            const Data = await Response.json();

            PassWrapper.classList.remove('hidden');
            EmailInput.readOnly = true;
            EmailInput.classList.add('opacity-50');
            Button.disabled = false;

            if (Data.exists) {
                authMode = 'login';
                document.getElementById('AuthSubtitle').innerText = 'Bienvenido, ingresa tu clave';
                Button.innerText = 'Iniciar Sesión';
            } else {
                authMode = 'register';
                document.getElementById('AuthSubtitle').innerText = 'Correo nuevo: Crea tu clave';
                Button.innerText = 'Crear Cuenta';
            }

            setTimeout(() => PassInput.focus(), 100);
        } catch (Error) {
            Button.disabled = false;
            Button.innerText = 'Continuar';
            console.error(Error);
        }
        return;
    }

    // bloque acceso
    if (!Password) {
        AlertBox.innerText = 'Ingresa tu contraseña';
        AlertBox.classList.remove('hidden');
        return;
    }

    Button.innerText = 'Procesando...';
    Button.disabled = true;

    try {
        const Response = await fetch('/auth/process', {
            method: 'POST',
            headers: JsonHeaders,
            body: JSON.stringify({ email: Email, password: Password, mode: authMode, redirect: RedirectTo }),
        });
        const Result = await Response.json();

        if (Result.success) {
            window.location.href = Result.redirect || '/';
            return;
        }

        AlertBox.innerText = Result.message || 'Error';
        AlertBox.classList.remove('hidden');
        Button.disabled = false;
        Button.innerText = authMode === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta';
    } catch (Error) {
        Button.disabled = false;
        Button.innerText = authMode === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta';
        console.error(Error);
    }
};

// bloque arranque
document.addEventListener('DOMContentLoaded', () => {
    ResetAuthForm();

    ['AuthEmail', 'AuthPass'].forEach((Id) => {
        document.getElementById(Id)?.addEventListener('keydown', (Event) => {
            if (Event.key !== 'Enter') {
                return;
            }

            Event.preventDefault();
            window.handleAuthStep();
        });
    });
});
