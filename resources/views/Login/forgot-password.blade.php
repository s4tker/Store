{{-- vista para pedir recuperacion de contraseña --}}

@extends('layouts.app')

@section('title', 'Recuperar contraseña | ElectroShop')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-12rem)] max-w-5xl items-center justify-center">
<section class="grid w-full overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-2xl md:grid-cols-[0.95fr_1.05fr]">
<div class="hidden items-center justify-center border-r bg-slate-50 p-10 md:flex">
<div class="text-center">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img mx-auto h-40 w-40 rounded-full shadow-2xl" alt="ElectroShop">
                <p class="mt-8 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">seguridad</p>
                <h1 class="mt-3 text-3xl font-black italic uppercase text-slate-900">Electro<span class="text-blue-600">Shop</span></h1>
            </div>
        </div>
<div class="p-7 sm:p-10 md:p-14">
<div class="mb-8">
                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">recuperar acceso</p>
                <h2 class="mt-3 text-2xl font-black italic uppercase text-slate-900">Restablecer contraseña</h2>
                <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ingresa tu correo y enviaremos un codigo</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect" value="{{ $RedirectTo }}">
                <input id="PasswordResetEmail" type="email" name="email" value="{{ old('email', $Email) }}" placeholder="Correo electrónico" required class="auth-input w-full rounded-2xl border-none bg-slate-100 p-4 font-bold">

                <button type="submit" class="btn-primary-es w-full rounded-2xl bg-slate-900 py-4 text-[11px] font-black uppercase tracking-widest text-white transition-all hover:bg-blue-600">Enviar codigo</button>

                @if($errors->any())
                    <div class="rounded-xl bg-red-50 p-3 text-center text-[10px] font-bold uppercase text-red-600">{{ $errors->first() }}</div>
                @endif

                @if(session('status'))
                    <div class="rounded-xl bg-blue-50 p-3 text-center text-[10px] font-bold uppercase text-blue-600">{{ session('status') }}</div>
                @endif

                <a id="AlreadyHaveCodeLink" href="{{ route('password.reset.form', ['email' => old('email', $Email)]) }}" class="block text-center text-[10px] font-black uppercase tracking-widest text-slate-400 transition-colors hover:text-blue-600">Ya tengo codigo</a>
                <a href="{{ route('login', ['redirect' => $RedirectTo]) }}" class="block text-center text-[10px] font-black uppercase tracking-widest text-slate-400 transition-colors hover:text-blue-600">Volver al login</a>
            </form>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('PasswordResetEmail');
    const codeLink = document.getElementById('AlreadyHaveCodeLink');

    if (!emailInput || !codeLink) {
        return;
    }

    const syncCodeLink = () => {
        const url = new URL(codeLink.href, window.location.origin);
        url.searchParams.set('email', emailInput.value.trim());
        codeLink.href = url.toString();
    };

    emailInput.addEventListener('input', syncCodeLink);
    syncCodeLink();
});
</script>
@endsection
