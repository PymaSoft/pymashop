<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function autocomplete(Request $request){
        $palabraabuscar = $request->get('palabraabuscar');

        $Productos = Product::where('name','like','%'.$palabraabuscar.'%')->orderBy('name')->get();

        $resultados = [];

        foreach ($Productos as $prod) {
            $encontrartexto = stristr($prod->name, $palabraabuscar);
            $prod->encontrar = $encontrartexto;
            $recortar_palabra = substr($encontrartexto, 0, strlen($palabraabuscar));
            $prod->substr = $recortar_palabra;
            $prod->name_negrita = str_ireplace($palabraabuscar, "<b>$recortar_palabra</b>", $prod->name);

            $resultados[] = $prod;
        }
        return $resultados;
    }
}
