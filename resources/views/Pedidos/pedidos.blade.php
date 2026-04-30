<!-- @extends('layouts.app')

@section('title', 'Pedidos | ElectroShop')

@section('styles')
    @vite('resources/css/pedidos.css')
@endsection

@section('content')
<div class="PedidosPage">
    {{-- bloque hero --}}
    <section class="PedidosHero">
        <div>
            <p class="PedidosEyebrow">pedidos</p>
            <h1 class="PedidosTitle">Historial de pedidos simulados</h1>
            <p class="PedidosLead">Aquí puedes revisar tus pedidos generados desde compra y cancelar los que sigan pendientes o pagados.</p>
        </div>

        <a href="{{ route('home') }}" class="PedidosBackLink">Volver al catálogo</a>
    </section>

    {{-- bloque panel --}}
    <section class="PedidosPanel">
        {{-- bloque cabecera --}}
        <div class="PedidosPanelHead">
            <div>
                <p class="PedidosEyebrow">resumen</p>
                <h2>Pedidos registrados</h2>
            </div>
            <span class="PedidosTag" id="PedidosCount">0 pedidos</span>
        </div>

        {{-- bloque vacio --}}
        <div id="PedidosEmpty" class="PedidosEmpty hidden">
            Aún no tienes pedidos simulados. Genera uno desde la vista de compra.
        </div>

        {{-- bloque lista --}}
        <div id="PedidosList" class="PedidosList"></div>
    </section>
</div>
@endsection

@section('scripts')
    @vite('resources/js/pedidos.js')
@endsection -->
