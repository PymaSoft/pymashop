<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function show($slug)
    {
        if (Product::where('slug',$slug)->first()) {
            return 'Slug existe';
        }
        else {
            return 'Disponible';
        }
    }

    public function eliminarimagen($id)
    {
        //return "se va a eliminar el registro ".$id;
        $image = Image::find($id);

        $archivo = substr($image->url,1);
        // return $archivo;

        $eliminar = File::delete($archivo);

        $image->delete();

        return "eliminado id:".$id. ' '.$eliminar;
    }
}
