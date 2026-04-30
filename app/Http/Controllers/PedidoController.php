<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelPedidoRequest;
use App\Http\Requests\StorePedidoRequest;
use App\Models\Direccion;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function __construct(private PedidoService $pedidoService)
    {
    }

    public function index()
    {
        $pedidos = Auth::user()
            ->pedidos()
            ->with('direccion')
            ->orderByDesc('CreatedAt')
            ->get();

        return view('pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function store(StorePedidoRequest $request): RedirectResponse
    {
        $usuario = Auth::user();

        $carritoItems = json_decode($request->carrito, true);

        try {
            $pedido = DB::transaction(function () use ($usuario, $request, $carritoItems) {
                // Crear la dirección
                $direccion = Direccion::create([
                    'UsuarioId' => $usuario->Id,
                    'Pais' => $request->string('Pais'),
                    'Region' => $request->string('Region'),
                    'Ciudad' => $request->string('Ciudad'),
                    'Direccion' => $request->string('Direccion'),
                    'Referencia' => $request->string('Referencia'),
                ]);

                return $this->pedidoService->crearPedidoDesdeCarrito(
                    $usuario->Id,
                    $direccion->Id,
                    $carritoItems
                );
            });

            return redirect()
                ->route('pedidos.show', $pedido->Id)
                ->with('success', 'Pedido creado correctamente.');
        } catch (\Throwable $exception) {
            Log::error('Error al crear pedido', [
                'user_id' => $usuario->Id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors(['pedido' => $exception->getMessage()])->withInput();
        }
    }

    public function show(int $id)
    {
        $pedido = $this->findPedidoForUsuario($id);

        return view('pedidos.show', [
            'pedido' => $pedido,
        ]);
    }

    public function cancelar(int $id, CancelPedidoRequest $request): RedirectResponse
    {
        $pedido = $this->findPedidoForUsuario($id);

        try {
            $this->pedidoService->cancelarPedido($pedido->Id);

            return redirect()
                ->route('pedidos.index')
                ->with('success', 'Pedido cancelado correctamente.');
        } catch (\Throwable $exception) {
            Log::error('Error al cancelar pedido', [
                'pedido_id' => $pedido->Id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors(['pedido' => $exception->getMessage()]);
        }
    }

    private function findPedidoForUsuario(int $id): Pedido
    {
        return Pedido::with(['detalles.variante.producto', 'direccion'])
            ->where('Id', $id)
            ->where('UsuarioId', Auth::id())
            ->firstOrFail();
    }
}
