<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->search ?? '';

        $Query = Productos::query();

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
