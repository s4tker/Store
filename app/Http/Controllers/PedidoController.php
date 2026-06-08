<?php

// este controlador atiende pantallas y acciones del sistema
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// esta clase controla pedidos del usuario
class PedidoController extends Controller
{
    // recibe el servicio que crea y cancela pedidos

    public function __construct(private PedidoService $pedidoService)
    {
    }
    // muestra pedidos del usuario actual

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
    // guarda el pedido con direccion productos y cliente

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

            return redirect()
                ->route('pedidos.index')
                ->with('success', 'Pedido creado correctamente.')
                ->with('clear_cart', true);
        } catch (\Throwable $exception) {
            Log::error('Error al crear pedido', [
                'user_id' => $usuarioAutenticado?->Id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors(['pedido' => $exception->getMessage()])->withInput();
        }
    }
    // busca un cliente por dni

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

        if (Auth::check() && (int) $cliente->Id !== (int) Auth::id()) {
            $cliente->Correo = Auth::user()->Correo;
        }

        return response()->json([
            'found' => true,
            'cliente' => $cliente,
            'direcciones' => $cliente->direcciones()
                ->orderByDesc('Id')
                ->get(['Id', 'Pais', 'Region', 'Ciudad', 'Direccion', 'Referencia']),
        ]);
    }
    // muestra un pedido del usuario con productos y estado

    public function show(int $id)
    {
        $pedido = $this->findPedidoForUsuario($id);

        return view('Pedidos.show', [
            'pedido' => $pedido,
        ]);
    }
    // cancela un pedido del usuario

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
    // busca un pedido del usuario actual

    private function findPedidoForUsuario(int $id): Pedido
    {
        return Pedido::with(['detalles.variante.producto', 'direccion'])
            ->where('Id', $id)
            ->where('UsuarioId', Auth::id())
            ->firstOrFail();
    }
    // busca o crea cliente por dni

    private function resolverClientePorDni(StorePedidoRequest $request, User $usuarioAutenticado): User
    {
        $dni = $request->validated('Documento');
        $cliente = $usuarioAutenticado;
        $duenoDni = User::query()
            ->where('Dni', $dni)
            ->where('Id', '!=', $usuarioAutenticado->Id)
            ->first();

        $cliente->fill([
            'Alias' => $cliente->Alias ?: $this->crearAliasCliente($request->validated('Correo'), $dni),
            'Nombre' => $request->validated('Nombre'),
            'Apellidos' => $request->validated('Apellidos'),
            'Correo' => $cliente->Correo ?: $this->resolverCorreoCliente($request->validated('Correo'), $cliente),
            'Telefono' => $request->validated('Telefono'),
            'Dni' => $duenoDni ? $cliente->Dni : $dni,
        ]);

        $cliente->save();

        return $cliente;
    }
    // decide la direccion del pedido

    private function resolverDireccionPedido(StorePedidoRequest $request, User $cliente): Direccion
    {
        $direccionId = $request->validated('DireccionId');

        if ($direccionId) {
            $direccion = Direccion::query()->find($direccionId);

            if (! $direccion) {
                abort(422, 'La dirección seleccionada no existe.');
            }

            if ((int) $direccion->UsuarioId === (int) $cliente->Id) {
                return $direccion;
            }

            return Direccion::create([
                'UsuarioId' => $cliente->Id,
                'Pais' => $direccion->Pais ?: 'Peru',
                'Region' => $direccion->Region,
                'Ciudad' => $direccion->Ciudad,
                'Direccion' => $direccion->Direccion,
                'Referencia' => $direccion->Referencia,
            ]);
        }

        if ($cliente->direcciones()->exists()) {
            abort(422, 'Selecciona una dirección guardada para continuar.');
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
    // crea un alias para el cliente

    private function crearAliasCliente(string $correo, string $dni): string
    {
        $base = Str::of(Str::before($correo, '@'))
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->limit(40, '')
            ->value();

        return $base !== '' ? $base : 'cliente' . $dni;
    }
    // confirma el correo del cliente

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
