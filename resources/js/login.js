// guarda referencias del formulario de acceso
const Token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const JsonHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': Token,
    'X-Requested-With': 'XMLHttpRequest',
};

let authMode = 'login';

// envia correo y contraseña para iniciar sesion
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

    UpdateForgotPasswordLink(EmailInput.value.trim(), document.getElementById('AuthRedirect')?.value || '');
}

// cambia visibilidad de la contraseña
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

// revisa correo y contraseña antes de enviar
function ValidateEmail(Email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(Email);
}

// prepara enlace de recuperacion con el correo actual
function UpdateForgotPasswordLink(Email, RedirectTo) {
    const ForgotPasswordLink = document.getElementById('ForgotPasswordLink');

    if (!ForgotPasswordLink) {
        return;
    }

    const Url = new URL(ForgotPasswordLink.href, window.location.origin);
    Url.searchParams.set('email', Email);

    if (RedirectTo) {
        Url.searchParams.set('redirect', RedirectTo);
    } else {
        Url.searchParams.delete('redirect');
    }

    ForgotPasswordLink.href = Url.toString();
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

    // valida que el correo tenga formato correcto
    if (!ValidateEmail(Email)) {
        AlertBox.innerText = 'Ingresa un correo válido';
        AlertBox.classList.remove('hidden');
        return;
    }

    // muestra mensaje cuando falta validar la cuenta
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
                UpdateForgotPasswordLink(Email, RedirectTo);
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

    // termina el ingreso si los datos son correctos
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

        if (Result.success && Result.requires_otp) {
            window.location.href = Result.redirect || '/auth/otp';
            return;
        }

        if (Result.success) {
            window.location.href = Result.redirect || '/';
            return;
        }

        AlertBox.innerText = Result.message || 'Error';

        if (authMode === 'login') {
            UpdateForgotPasswordLink(Email, RedirectTo);
        }

        AlertBox.classList.remove('hidden');
        Button.disabled = false;
        Button.innerText = authMode === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta';
    } catch (Error) {
        Button.disabled = false;
        Button.innerText = authMode === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta';
        console.error(Error);
    }
};

// prepara eventos del formulario de login
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

    document.getElementById('AuthEmail')?.addEventListener('input', (Event) => {
        UpdateForgotPasswordLink(Event.target.value.trim(), document.getElementById('AuthRedirect')?.value || '');
    });
});
