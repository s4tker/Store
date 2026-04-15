<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; // <--- ESTA LÍNEA FALTA Y POR ESO DA ERROR

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->search ?? '';

        // Ahora Producto será reconocido y buscará la relación 'marca'
        $Query = Producto::with('marca');

        if ($Search) {
            $Query->where('Nombre', 'like', "%{$Search}%");
        }

        $Products = $Query->orderBy('Id', 'desc')->get();

        return view('index', [
            'Products' => $Products,
            'Search' => $Search
        ]);
    }
}
