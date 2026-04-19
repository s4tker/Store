@extends('layouts.app')

@section('title', 'Login | ElectroShop')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-12rem)] max-w-5xl items-center justify-center">
    {{-- bloque login --}}
    <section class="grid w-full overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-2xl md:grid-cols-[0.95fr_1.05fr]">
        {{-- bloque visual --}}
        <div class="hidden items-center justify-center border-r bg-slate-50 p-10 md:flex">
            <div class="text-center">
                <img src="{{ asset('img/logo/logo.png') }}" class="logo-img mx-auto h-40 w-40 rounded-full shadow-2xl" alt="ElectroShop">
                <p class="mt-8 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">login y registro</p>
                <h1 class="mt-3 text-3xl font-black italic uppercase text-slate-900">Electro<span class="text-blue-600">Shop</span></h1>
            </div>
        </div>

        {{-- bloque formulario --}}
        <div class="p-7 sm:p-10 md:p-14">
            <div class="mb-8">
                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">acceso</p>
                <h2 class="mt-3 text-2xl font-black italic uppercase text-slate-900">Electro<span class="text-blue-600">Shop</span></h2>
                <p id="AuthSubtitle" class="mt-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ingresa tu correo para continuar</p>
            </div>

            <input type="hidden" id="AuthRedirect" value="{{ $RedirectTo }}">

            <div class="space-y-4">
                <input id="AuthEmail" type="email" placeholder="Correo electrónico" class="auth-input w-full rounded-2xl border-none bg-slate-100 p-4 font-bold">

                <div id="PassWrapper" class="hidden relative">
                    <input id="AuthPass" type="password" placeholder="Escribe tu contraseña" class="auth-input w-full rounded-2xl border-none bg-slate-100 p-4 pr-12 font-bold">

                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-blue-600">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="eyePath" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <button id="AuthBtn" onclick="handleAuthStep()" class="btn-primary-es w-full rounded-2xl bg-slate-900 py-4 text-[11px] font-black uppercase tracking-widest text-white transition-all hover:bg-blue-600">Continuar</button>

                <div id="AuthAlert" class="hidden rounded-xl bg-red-50 p-3 text-center text-[10px] font-bold uppercase text-red-600"></div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    @vite('resources/js/login.js')
@endsection
