@extends('layouts.app')

@section('title', 'Verificacion | ElectroShop')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-12rem)] max-w-5xl items-center justify-center">
    <section class="grid w-full overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-2xl md:grid-cols-[0.95fr_1.05fr]">
        <div class="hidden items-center justify-center border-r bg-slate-50 p-10 md:flex">
            <div class="text-center">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img mx-auto h-40 w-40 rounded-full shadow-2xl" alt="ElectroShop">
            </div>
        </div>

        <div class="p-7 sm:p-10 md:p-14">
            <div class="mb-8">
                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">codigo otp</p>
                <h2 class="mt-3 text-2xl font-black italic uppercase text-slate-900">Revisa tu correo</h2>
                <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ingresa el codigo que se ha enviado a tu mail</p>
            </div>

            <input type="hidden" id="OtpEmail" value="{{ $Email }}">
            <input type="hidden" id="OtpRedirect" value="{{ $RedirectTo }}">

            <div class="space-y-4">
                <input id="OtpCode" type="text" inputmode="numeric" maxlength="6" placeholder="Codigo de verificacion" class="auth-input w-full rounded-2xl border-none bg-slate-100 p-4 text-center text-sm font-black tracking-widest placeholder:text-xs placeholder:tracking-wide">

                <button id="OtpBtn" type="button" class="btn-primary-es w-full rounded-2xl bg-slate-900 py-4 text-[11px] font-black uppercase tracking-widest text-white transition-all hover:bg-blue-600">Verificar codigo</button>

                <button id="ResendOtpBtn" type="button" class="w-full rounded-2xl bg-slate-100 py-4 text-[11px] font-black uppercase tracking-widest text-slate-600 transition-all hover:bg-blue-50 hover:text-blue-600">Reenviar codigo</button>

                <div id="OtpAlert" class="hidden rounded-xl bg-red-50 p-3 text-center text-[10px] font-bold uppercase text-red-600"></div>

                <a href="{{ route('login', ['redirect' => $RedirectTo]) }}" class="block text-center text-[10px] font-black uppercase tracking-widest text-slate-400 transition-colors hover:text-blue-600">Cambiar correo</a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
const OtpToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

async function verifyOtp() {
    const codeInput = document.getElementById('OtpCode');
    const button = document.getElementById('OtpBtn');
    const alertBox = document.getElementById('OtpAlert');
    const email = document.getElementById('OtpEmail')?.value || '';
    const redirect = document.getElementById('OtpRedirect')?.value || '';
    const code = (codeInput?.value || '').replace(/\D/g, '').slice(0, 6);

    alertBox.classList.add('hidden');

    if (code.length !== 6) {
        alertBox.innerText = 'Ingresa el codigo de 6 digitos';
        alertBox.classList.remove('hidden');
        return;
    }

    button.disabled = true;
    button.innerText = 'Verificando...';

    try {
        const response = await fetch('{{ route('auth.otp.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': OtpToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ email, otp_code: code, redirect }),
        });
        const result = await response.json();

        if (result.success) {
            window.location.href = result.redirect || '/';
            return;
        }

        alertBox.innerText = result.message || 'Codigo invalido';
        alertBox.classList.remove('hidden');
    } catch (error) {
        console.error(error);
        alertBox.innerText = 'No se pudo verificar el codigo';
        alertBox.classList.remove('hidden');
    }

    button.disabled = false;
    button.innerText = 'Verificar codigo';
}

async function resendOtp() {
    const button = document.getElementById('ResendOtpBtn');
    const alertBox = document.getElementById('OtpAlert');
    const email = document.getElementById('OtpEmail')?.value || '';

    alertBox.classList.add('hidden');
    button.disabled = true;
    button.innerText = 'Reenviando...';

    try {
        const response = await fetch('{{ route('auth.otp.resend') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': OtpToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ email }),
        });
        const result = await response.json();

        alertBox.innerText = result.message || (result.success ? 'Codigo reenviado' : 'No se pudo reenviar');
        alertBox.classList.remove('hidden');
        alertBox.classList.toggle('bg-red-50', !result.success);
        alertBox.classList.toggle('text-red-600', !result.success);
        alertBox.classList.toggle('bg-blue-50', result.success);
        alertBox.classList.toggle('text-blue-600', result.success);
    } catch (error) {
        console.error(error);
        alertBox.innerText = 'No se pudo reenviar el codigo';
        alertBox.classList.remove('hidden');
    }

    button.disabled = false;
    button.innerText = 'Reenviar codigo';
}

document.addEventListener('DOMContentLoaded', () => {
    const codeInput = document.getElementById('OtpCode');
    const button = document.getElementById('OtpBtn');
    const resendButton = document.getElementById('ResendOtpBtn');

    codeInput?.focus();
    codeInput?.addEventListener('input', () => {
        codeInput.value = codeInput.value.replace(/\D/g, '').slice(0, 6);
    });
    codeInput?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            verifyOtp();
        }
    });
    button?.addEventListener('click', verifyOtp);
    resendButton?.addEventListener('click', resendOtp);
});
</script>
@endsection
