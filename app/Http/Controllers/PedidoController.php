<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelPedidoRequest;
use App\Http\Requests\StorePedidoRequest;
use App\Models\Direccion;
use App\Models\Pedido;
use App\Models\User;
use App\Services\PedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        return view('Pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function store(StorePedidoRequest $request): RedirectResponse
    {
        $usuarioAutenticado = Auth::user();
        $carritoItems = json_decode($request->validated('carrito'), true) ?: [];

        try {
            $pedido = DB::transaction(function () use ($usuarioAutenticado, $request, $carritoItems) {
                $cliente = $this->resolverClientePorDni($request, $usuarioAutenticado);
                $direccion = $this->resolverDireccionPedido($request, $cliente);

                return $this->pedidoService->crearPedidoDesdeCarrito(
                    $cliente->Id,
                    $direccion->Id,
                    $carritoItems
                );
            });

            if ($pedido->UsuarioId !== Auth::id()) {
                Auth::loginUsingId($pedido->UsuarioId);
            }

            return redirect()
                ->route('pedidos.show', $pedido->Id)
                ->with('success', 'Pedido creado correctamente.');
        } catch (\Throwable $exception) {
            Log::error('Error al crear pedido', [
                'user_id' => $usuarioAutenticado?->Id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors(['pedido' => $exception->getMessage()])->withInput();
        }
    }

    public function buscarClientePorDni(string $dni): JsonResponse
    {
        $dni = preg_replace('/\D+/', '', $dni);

        if (! preg_match('/^\d{8}$/', $dni)) {
            return response()->json(['message' => 'DNI invalido.'], 422);
        }

        $cliente = User::query()
            ->where('Dni', $dni)
            ->first(['Id', 'Nombre', 'Apellidos', 'Correo', 'Telefono', 'Dni']);

        if (! $cliente) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'cliente' => $cliente,
            'direcciones' => $cliente->direcciones()
                ->orderByDesc('Id')
                ->get(['Id', 'Pais', 'Region', 'Ciudad', 'Direccion', 'Referencia']),
        ]);
    }

    public function show(int $id)
    {
        $pedido = $this->findPedidoForUsuario($id);

        return view('Pedidos.show', [
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

    private function resolverClientePorDni(StorePedidoRequest $request, User $usuarioAutenticado): User
    {
        $dni = $request->validated('Documento');
        $cliente = User::query()->where('Dni', $dni)->first();

        if (! $cliente) {
            $cliente = $usuarioAutenticado->Dni && $usuarioAutenticado->Dni !== $dni
                ? new User()
                : $usuarioAutenticado;
        }

        $cliente->fill([
            'Alias' => $cliente->Alias ?: $this->crearAliasCliente($request->validated('Correo'), $dni),
            'Nombre' => $request->validated('Nombre'),
            'Apellidos' => $request->validated('Apellidos'),
            'Correo' => $this->resolverCorreoCliente($request->validated('Correo'), $cliente),
            'Telefono' => $request->validated('Telefono'),
            'Dni' => $dni,
        ]);

        if (! $cliente->exists) {
            $cliente->Password = Hash::make(Str::random(32));
        }

        $cliente->save();

        return $cliente;
    }

    private function resolverDireccionPedido(StorePedidoRequest $request, User $cliente): Direccion
    {
        $direccionId = $request->validated('DireccionId');

        if ($direccionId) {
            return Direccion::query()
                ->where('Id', $direccionId)
                ->where('UsuarioId', $cliente->Id)
                ->firstOrFail();
        }

        if ($cliente->direcciones()->exists()) {
            abort(422, 'Selecciona una direccion guardada para continuar.');
        }

        return Direccion::create([
            'UsuarioId' => $cliente->Id,
            'Pais' => 'Peru',
            'Region' => $request->validated('Region'),
            'Ciudad' => $request->validated('Ciudad'),
            'Direccion' => $request->validated('Direccion'),
            'Referencia' => $request->validated('Referencia'),
        ]);
    }

    private function crearAliasCliente(string $correo, string $dni): string
    {
        $base = Str::of(Str::before($correo, '@'))
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->limit(40, '')
            ->value();

        return $base !== '' ? $base : 'cliente' . $dni;
    }

    private function resolverCorreoCliente(string $correo, User $cliente): string
    {
        $duenoCorreo = User::query()
            ->where('Correo', $correo)
            ->when($cliente->exists, fn ($query) => $query->where('Id', '!=', $cliente->Id))
            ->first();

        if (! $duenoCorreo) {
            return $correo;
        }

        if ($cliente->Correo) {
            return $cliente->Correo;
        }

        return 'cliente' . $cliente->Dni . '@checkout.local';
    }
}
