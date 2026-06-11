{{-- alertas reales de inventario para administradores --}}

<section class="admin-panel stats-admin-stock-panel p-4 md:p-5">
    @if(session('stock_status'))
        <div class="stats-admin-stock-flash mb-4">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
            </svg>
            <span>{{ session('stock_status') }}</span>
        </div>
    @endif

    <div class="stats-admin-panel-head flex flex-col gap-4 border-b border-slate-100 pb-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="admin-card-kicker">Inventario</p>
            <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-950">Notificaciones de stock</h2>
        </div>
        <div class="stats-admin-stock-legend">
            <span class="is-low">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.17V11a6 6 0 1 0-12 0v3.17a2 2 0 0 1-.6 1.43L4 17h5m6 0a3 3 0 0 1-6 0"/></svg>
                Stock bajo: 20
            </span>
            <span class="is-critical">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                Stock crítico: 12
            </span>
            <span class="is-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M10.3 4.3 2.8 17.2A2 2 0 0 0 4.5 20h15a2 2 0 0 0 1.7-2.8L13.7 4.3a2 2 0 0 0-3.4 0zM12 9v4m0 3h.01"/></svg>
                En peligro: 5
            </span>
        </div>
    </div>

    @if(count($StockAlerts))
        <div class="mt-5 grid gap-3.5" id="StockAlertsPanel">
            @foreach($StockAlerts as $alerta)
                <article class="stats-admin-stock-alert is-{{ $alerta['tono'] }}">
                    <div class="stats-admin-stock-alert-media">
                        <img src="{{ $alerta['imagen'] }}" alt="{{ $alerta['producto'] }}">
                        <span>
                            @if($alerta['tono'] === 'danger')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M10.3 4.3 2.8 17.2A2 2 0 0 0 4.5 20h15a2 2 0 0 0 1.7-2.8L13.7 4.3a2 2 0 0 0-3.4 0zM12 9v4m0 3h.01"/></svg>
                            @elseif($alerta['tono'] === 'critical')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.17V11a6 6 0 1 0-12 0v3.17a2 2 0 0 1-.6 1.43L4 17h5m6 0a3 3 0 0 1-6 0"/></svg>
                            @endif
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="stats-admin-stock-level">{{ $alerta['nivel'] }}</p>
                                <h3 class="truncate text-base font-semibold text-slate-950">{{ $alerta['producto'] }}</h3>
                            </div>
                            <strong class="stats-admin-stock-count">{{ $alerta['stock'] }} und.</strong>
                        </div>
                        <div class="mt-3 flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                            <form action="{{ $alerta['stock_update_url'] }}" method="POST" class="stats-admin-stock-form">
                                @csrf
                                @method('PATCH')
                                <label for="StockAlert{{ $alerta['variante_id'] }}">Stock</label>
                                <input id="StockAlert{{ $alerta['variante_id'] }}" type="text" inputmode="numeric" pattern="[0-9]*" name="Stock" value="{{ $alerta['stock'] }}">
                                <button type="submit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5a2 2 0 0 1 2-2h9l3 3v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2zM8 3v6h8M9 17h6"/></svg>
                                    Actualizar
                                </button>
                            </form>
                            <a href="{{ $alerta['advanced_url'] }}" class="stats-admin-stock-advanced">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m14.7 6.3 3 3M4 20l5.5-1.5L19 9a3 3 0 0 0-4-4L5.5 14.5z"/></svg>
                                Edición avanzada
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="admin-empty mt-5">
            <svg class="mx-auto mb-2 h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
            </svg>
            No hay productos con stock bajo.
        </div>
    @endif
</section>
