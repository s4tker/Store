<?php

namespace App\Services;

use App\Models\Carrito;
use App\Models\Inventario;
use App\Models\MovimientoStock;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\ProductoVariantes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PedidoService
{
    public function crearPedidoDesdeCarrito(int $usuarioId, int $direccionId, array $carritoItems): Pedido
    {
        if (empty($carritoItems)) {
            throw ValidationException::withMessages([
                'carrito' => 'El carrito está vacío, no se puede generar el pedido.',
            ]);
        }

        $total = 0;
        $detalles = [];

        foreach ($carritoItems as $item) {
            $varianteId = $item['variantId'] ?? null;
            
            if (! $varianteId) {
                throw ValidationException::withMessages([
                    'carrito' => 'El carrito contiene items sin variante.',
                ]);
            }

            $variante = ProductoVariantes::find($varianteId);
            if (! $variante) {
                throw ValidationException::withMessages([
                    'carrito' => "La variante con ID {$varianteId} no existe.",
                ]);
            }

            $stock = Inventario::where('VarianteId', $varianteId)->first();
            $stockDisponible = $stock ? (int) $stock->Stock : 0;
            $cantidad = $item['qty'] ?? 1;

            if ($stockDisponible < $cantidad) {
                throw ValidationException::withMessages([
                    'carrito' => "No hay stock suficiente para {$variante->Sku}.",
                ]);
            }

            $precio = $item['price'] ?? (float) $variante->Precio;
            $subtotal = $precio * $cantidad;
            $total += $subtotal;

            $detalles[] = [
                'VarianteId' => $varianteId,
                'Cantidad' => $cantidad,
                'Precio' => $precio,
            ];
        }

        return DB::transaction(function () use ($usuarioId, $direccionId, $total, $detalles) {
            $pedido = Pedido::create([
                'UsuarioId' => $usuarioId,
                'DireccionId' => $direccionId,
                'Total' => $total,
                'Estado' => 'pendiente',
                'CreatedAt' => Carbon::now(),
            ]);

            foreach ($detalles as $detalle) {
                PedidoDetalle::create([
                    'PedidoId' => $pedido->Id,
                    'VarianteId' => $detalle['VarianteId'],
                    'Cantidad' => $detalle['Cantidad'],
                    'Precio' => $detalle['Precio'],
                ]);

                $inventario = Inventario::firstOrNew(['VarianteId' => $detalle['VarianteId']]);
                $inventario->Stock = ($inventario->Stock ?? 0) - $detalle['Cantidad'];
                $inventario->save();

                MovimientoStock::create([
                    'VarianteId' => $detalle['VarianteId'],
                    'Tipo' => 'Salida',
                    'Cantidad' => $detalle['Cantidad'],
                    'Motivo' => 'Venta',
                ]);
            }

            session()->forget('cart');

            return $pedido;
        });
    }

    public function cancelarPedido(int $pedidoId): Pedido
    {
        $pedido = Pedido::with('detalles')->find($pedidoId);

        if (! $pedido) {
            throw new ModelNotFoundException("Pedido con ID {$pedidoId} no encontrado.");
        }

        if (mb_strtolower((string) $pedido->Estado) !== 'pendiente') {
            throw ValidationException::withMessages([
                'pedido' => 'Solo se pueden cancelar pedidos en estado pendiente.',
            ]);
        }

        return DB::transaction(function () use ($pedido) {
            $pedido->Estado = 'cancelado';
            $pedido->save();

            foreach ($pedido->detalles as $detalle) {
                $inventario = Inventario::firstOrNew(['VarianteId' => $detalle->VarianteId]);
                $inventario->Stock = ($inventario->Stock ?? 0) + $detalle->Cantidad;
                $inventario->save();

                MovimientoStock::create([
                    'VarianteId' => $detalle->VarianteId,
                    'Tipo' => 'Entrada',
                    'Cantidad' => $detalle->Cantidad,
                    'Motivo' => 'Cancelación de pedido',
                ]);
            }

            return $pedido;
        });
    }

    private function obtenerDireccionPrincipal(int $usuarioId): ?int
    {
        return DB::table('Direcciones')
            ->where('UsuarioId', $usuarioId)
            ->value('Id');
    }
}
