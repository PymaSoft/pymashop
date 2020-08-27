<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('haveaccess','product.index');

        $name = $request->get('name');
        // dd($name);
        
        $products = Product::with('images', 'category')->where('name','like',"%$name%")->orderBy('name')->paginate(2);

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('haveaccess','product.create');

        $estados_productos = $this->estados_productos();
        $categorias = Category::orderBy('name')->get();

        return view('admin.product.create',compact('categorias','estados_productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('haveaccess','product.create');

        $request->validate([
            'name' => 'required|unique:products,name',
            'slug' => 'required|unique:products,slug',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $urlimagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');
            // return $imagenes;
            // dd ($imagenes);

            foreach ($imagenes as $imagen) {
                $nombre = time().'_'.$imagen->getClientOriginalName();

                $ruta = public_path().'/imagenes';
                
                $imagen->move($ruta, $nombre);

                $urlimagenes[]['url'] = '/imagenes/'.$nombre;
            }
            //return $urlimagenes;
        }

        $prod = new Product;

        $prod->name	= $request->name;
        $prod->slug	= $request->slug;
        $prod->category_id = $request->category_id;
        $prod->quantity	= $request->quantity;
        $prod->price_previous = $request->price_previous;
        $prod->price_current = $request->price_current;
        $prod->discount_percentage = $request->discount_percentage;
        $prod->short_description = $request->short_description;
        $prod->long_description	= $request->long_description;
        $prod->specs = $request->specs;
        $prod->data_of_interest	= $request->data_of_interest;
        $prod->state = $request->state;
        
        if ($request->active) {
            $prod->active= 'Si';    
        }
        else {
            $prod->active= 'No';    
        }

        if ($request->slidermain) {
            $prod->slidermain= 'Si';    
        }
        else {
            $prod->slidermain= 'No';    
        }
        
        $prod->save();

        $prod->images()->createMany($urlimagenes);

        //return $prod->images;
        
        // return $request->all();
        return redirect()->route('admin.product.index')->with('datos','Registro creado correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $this->authorize('haveaccess','product.show');

        $producto = Product::with('images', 'category')->where('slug',$slug)->firstOrFail();

        $categorias = Category::orderBy('name')->get();

        $estados_productos = $this->estados_productos();
        // dd($estados_productos);

        return view('admin.product.show',compact('producto','categorias','estados_productos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $this->authorize('haveaccess','product.edit');

        $producto = Product::with('images', 'category')->where('slug',$slug)->firstOrFail();

        $categorias = Category::orderBy('name')->get();

        $estados_productos = $this->estados_productos();
        // dd($estados_productos);

        return view('admin.product.edit',compact('producto','categorias','estados_productos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('haveaccess','product.edit');

        $request->validate([
            'name' => 'required|unique:products,name,'.$id,
            'slug' => 'required|unique:products,slug,'.$id,
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $urlimagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');
            // return $imagenes;
            // dd ($imagenes);

            foreach ($imagenes as $imagen) {
                $nombre = time().'_'.$imagen->getClientOriginalName();

                $ruta = public_path().'/imagenes';
                
                $imagen->move($ruta, $nombre);

                $urlimagenes[]['url'] = '/imagenes/'.$nombre;
            }
            //return $urlimagenes;
        }

        $prod = Product::findOrFail($id);

        $prod->name	= $request->name;
        $prod->slug	= $request->slug;
        $prod->category_id = $request->category_id;
        $prod->quantity	= $request->quantity;
        $prod->price_previous = $request->price_previous;
        $prod->price_current = $request->price_current;
        $prod->discount_percentage = $request->discount_percentage;
        $prod->short_description = $request->short_description;
        $prod->long_description	= $request->long_description;
        $prod->specs = $request->specs;
        $prod->data_of_interest	= $request->data_of_interest;
        $prod->state = $request->state;
        
        if ($request->active) {
            $prod->active= 'Si';    
        }
        else {
            $prod->active= 'No';    
        }

        if ($request->slidermain) {
            $prod->slidermain= 'Si';    
        }
        else {
            $prod->slidermain= 'No';    
        }
        
        $prod->save();

        $prod->images()->createMany($urlimagenes);

        //return $prod->images;
        
        // return $request->all();
        return redirect()->route('admin.product.edit', $prod->slug)->with('datos','Registro actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('haveaccess','product.destroy');

        $prod = Product::with('images')->findOrFail($id);
        foreach ($prod->images as $image) {
            $archivo = substr($image->url,1);

            File::delete($archivo);

            $image->delete();
        }
        // return $prod;

        $prod->delete();

        return redirect()->route('admin.product.index')->with('datos','Registro eliminado correctamente!');
    }

    public function estados_productos(){
        return [
            '',
            'Nuevo',
            'En Oferta',
            'Popular'
        ];
    }
}
