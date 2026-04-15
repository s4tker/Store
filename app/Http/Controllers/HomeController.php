<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->search ?? '';

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
