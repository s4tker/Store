<footer class="bg-slate-900 text-slate-400 mt-10 md:mt-16 py-6 md:py-10">
    <div class="max-w-7xl mx-auto px-4 md:px-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <div>
                <h3 class="text-white text-sm md:text-base font-black mb-3">Acerca de ElectroShop</h3>
                <p class="text-[13px] md:text-sm text-slate-500">Tu tienda de electrónica, audio y accesorios de confianza.</p>
            </div>
            <div>
                <h3 class="text-white text-sm md:text-base font-black mb-3">Navegación</h3>
                <ul class="space-y-2 text-[13px] md:text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition">Inicio</a></li>
                    @auth
                        <li><a href="{{ route('account') }}" class="hover:text-blue-400 transition">Mi Cuenta</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h3 class="text-white text-sm md:text-base font-black mb-3">Información</h3>
                <p class="text-[13px] md:text-sm text-slate-500">© {{ date('Y') }} ElectroShop. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>
