<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->search ?? '';
        $Products = collect();

        try {
            $Query = Producto::with(['marca', 'imagenes', 'variantes']);

            if ($Search) {
                $Query->where('Nombre', 'like', "%{$Search}%");
            }

            $Products = $Query->orderBy('Id', 'desc')->get();
        } catch (QueryException) {
            $Products = new Collection();
        }

        return view('index', [
            'Products' => $Products,
            'Search' => $Search,
        ]);
    }
}
