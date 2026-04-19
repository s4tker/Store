@extends('layouts.app')

@section('title', 'Formulario de Compra | ElectroShop')

@section('styles')
    @vite('resources/css/compra.css')
@endsection

@section('content')
@php
    // bloque bootstrap
    $CompraBootstrap = [
        'Usuario' => $UsuarioCompra ? [
            'Nombre' => $UsuarioCompra->Nombre,
            'Apellidos' => $UsuarioCompra->Apellidos,
            'Correo' => $UsuarioCompra->Correo,
            'Telefono' => $UsuarioCompra->Telefono,
            'Dni' => $UsuarioCompra->Dni,
            'Ruc' => $UsuarioCompra->Ruc,
            'RazonSocial' => $UsuarioCompra->RazonSocial,
        ] : null,
        'Direcciones' => $DireccionesCompra->map(fn ($Direccion) => [
            'Id' => $Direccion->Id,
            'Pais' => $Direccion->Pais,
            'Region' => $Direccion->Region,
            'Ciudad' => $Direccion->Ciudad,
            'Direccion' => $Direccion->Direccion,
            'Referencia' => $Direccion->Referencia,
        ])->values(),
    ];
@endphp

{{-- bloque datos --}}
<script id="CompraBootstrap" type="application/json">@json($CompraBootstrap)</script>

<div class="CompraPage">
    {{-- bloque hero --}}
    <section class="CompraHero">
        <div>
            <p class="CompraEyebrow">simulación de compra</p>
            <h1 class="CompraTitle">Finaliza tu pedido con el estilo de ElectroShop</h1>
            <p class="CompraLead">Este formulario usa la estructura de tu base de datos para cliente, dirección, pedido y pago, pero solo simula la operación.</p>
        </div>

        <a href="{{ route('home') }}" class="CompraBackLink">Seguir comprando</a>
    </section>

    <div class="CompraGrid">
        {{-- bloque formulario --}}
        <section class="CompraPanel">
            <div class="CompraPanelHead">
                <div>
                    <p class="CompraEyebrow">cliente</p>
                    <h2>Datos del usuario</h2>
                </div>
                @auth
                    <span class="CompraTag">prefill activo</span>
                @endauth
            </div>

            <form id="CompraForm" class="CompraForm" autocomplete="on">
                <div class="CompraFieldGroup CompraFieldGroupWide">
                    <label for="CompraTipoCliente">Tipo de compra</label>
                    <select id="CompraTipoCliente" name="TipoCliente" class="CompraInput">
                        <option value="persona">Persona natural</option>
                        <option value="empresa">Empresa</option>
                    </select>
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraNombre">Nombre</label>
                    <input id="CompraNombre" name="Nombre" type="text" class="CompraInput" placeholder="Nombre">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraApellidos">Apellidos</label>
                    <input id="CompraApellidos" name="Apellidos" type="text" class="CompraInput" placeholder="Apellidos">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraCorreo">Correo</label>
                    <input id="CompraCorreo" name="Correo" type="email" class="CompraInput" placeholder="correo@dominio.com">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraTelefono">Teléfono</label>
                    <input id="CompraTelefono" name="Telefono" type="text" maxlength="9" class="CompraInput" placeholder="999999999">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraDni">DNI</label>
                    <input id="CompraDni" name="Dni" type="text" maxlength="8" class="CompraInput" placeholder="12345678">
                </div>

                <div class="CompraFieldGroup CompraFieldEmpresa" data-empresa-field>
                    <label for="CompraRuc">RUC</label>
                    <input id="CompraRuc" name="Ruc" type="text" maxlength="11" class="CompraInput" placeholder="20123456789">
                </div>

                <div class="CompraFieldGroup CompraFieldEmpresa" data-empresa-field>
                    <label for="CompraRazonSocial">Razón social</label>
                    <input id="CompraRazonSocial" name="RazonSocial" type="text" class="CompraInput" placeholder="Empresa SAC">
                </div>

                <div class="CompraPanelDivider"></div>

                <div class="CompraPanelHead CompraPanelHeadCompact">
                    <div>
                        <p class="CompraEyebrow">dirección</p>
                        <h2>Entrega</h2>
                    </div>
                </div>

                @if($DireccionesCompra->isNotEmpty())
                    <div class="CompraSavedAddresses" id="CompraSavedAddresses"></div>
                @endif

                <div class="CompraFieldGroup">
                    <label for="CompraPais">País</label>
                    <input id="CompraPais" name="Pais" type="text" class="CompraInput" placeholder="Perú">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraRegion">Región</label>
                    <input id="CompraRegion" name="Region" type="text" class="CompraInput" placeholder="Lima">
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraCiudad">Ciudad</label>
                    <input id="CompraCiudad" name="Ciudad" type="text" class="CompraInput" placeholder="Lima">
                </div>

                <div class="CompraFieldGroup CompraFieldGroupWide">
                    <label for="CompraDireccion">Dirección</label>
                    <input id="CompraDireccion" name="Direccion" type="text" class="CompraInput" placeholder="Av. o calle, número, interior">
                </div>

                <div class="CompraFieldGroup CompraFieldGroupWide">
                    <label for="CompraReferencia">Referencia</label>
                    <textarea id="CompraReferencia" name="Referencia" class="CompraInput CompraTextarea" rows="3" placeholder="Punto de referencia para la entrega"></textarea>
                </div>

                <div class="CompraPanelDivider"></div>

                <div class="CompraPanelHead CompraPanelHeadCompact">
                    <div>
                        <p class="CompraEyebrow">pago</p>
                        <h2>Pago simulado</h2>
                    </div>
                </div>

                <div class="CompraFieldGroup CompraFieldGroupWide">
                    <label for="CompraMetodoPago">Método</label>
                    <select id="CompraMetodoPago" name="MetodoPago" class="CompraInput">
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Yape">Yape</option>
                        <option value="Plin">Plin</option>
                    </select>
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraEstadoPedido">Estado de pedido</label>
                    <select id="CompraEstadoPedido" name="EstadoPedido" class="CompraInput">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Pagado">Pagado</option>
                    </select>
                </div>

                <div class="CompraFieldGroup">
                    <label for="CompraEstadoPago">Estado de pago</label>
                    <select id="CompraEstadoPago" name="EstadoPago" class="CompraInput">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                <div class="CompraActions">
                    <button type="submit" class="CompraPrimaryButton">Simular compra</button>
                    <button type="button" class="CompraGhostButton" onclick="ClearCompraSimulation()">Limpiar</button>
                </div>
            </form>
        </section>

        {{-- bloque resumen --}}
        <aside class="CompraSidebar">
            <section class="CompraPanel CompraPanelSticky">
                <div class="CompraPanelHead">
                    <div>
                        <p class="CompraEyebrow">pedido</p>
                        <h2>Resumen del carrito</h2>
                    </div>
                    <span class="CompraTag" id="CompraItemCount">0 items</span>
                </div>

                <div id="CompraEmptyState" class="CompraEmptyState hidden">
                    Tu carrito está vacío. Vuelve al catálogo y agrega productos para probar la simulación.
                </div>

                <div id="CompraItems" class="CompraItems"></div>

                <div class="CompraTotals">
                    <div>
                        <span>Subtotal</span>
                        <strong id="CompraSubtotal">S/.0.00</strong>
                    </div>
                    <div>
                        <span>Envío simulado</span>
                        <strong id="CompraEnvio">S/.0.00</strong>
                    </div>
                    <div class="CompraTotalRow">
                        <span>Total pedido</span>
                        <strong id="CompraTotal">S/.0.00</strong>
                    </div>
                </div>

                <div id="CompraResult" class="CompraResult hidden"></div>
            </section>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
    @vite('resources/js/compra.js')
@endsection
