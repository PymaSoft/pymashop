<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
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
        $this->authorize('haveaccess','category.index');

        $name = $request->get('name');
        // dd($name);
        $categorias = Category::where('name','like',"%$name%")->orderBy('name')->paginate(2);

        return view('admin.category.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('haveaccess','category.create');

        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* $cat = new Category();
        $cat->name = $request->name;
        $cat->slug = $request->slug;
        $cat->description = $request->description;
        $cat->save();
        
        return $cat; */  // Método largo
        
        // Método corto (en el Modelo Category)
        // return Category::create($request->all());
        
        $this->authorize('haveaccess','category.create');

        $request->validate([
            'name'=>'required|max:50|unique:categories,name',
            'slug'=>'required|max:50|unique:categories,slug',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.category.index')->with('datos','Registro creado correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $this->authorize('haveaccess','category.show');

        $cat = Category::where('slug', $slug)->firstOrFail();
        $editar = 'Si';
        return view('admin.category.show', compact('cat', 'editar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $this->authorize('haveaccess','category.edit');

        $cat = Category::where('slug', $slug)->firstOrFail();
        $editar = 'Si';
        return view('admin.category.edit', compact('cat', 'editar'));
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
        /* $cat = Category::findOrFail($id);
        $cat->name = $request->name;
        $cat->slug = $request->slug;
        $cat->description = $request->description;
        $cat->save();

        return $cat; */ // Método largo
        
        // Método corto (en el Modelo Category)
        $this->authorize('haveaccess','category.edit');

        $cat = Category::findOrFail($id);

        $request->validate([
            'name'=>'required|max:50|unique:categories,name,'.$cat->id,
            'slug'=>'required|max:50|unique:categories,slug,'.$cat->id,
        ]);

        $cat->fill($request->all())->save();

        // return $cat;
        return redirect()->route('admin.category.index')->with('datos','Registro actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('haveaccess','category.destroy');

        $cat = Category::findOrFail($id);
        $cat->delete();

        return redirect()->route('admin.category.index')->with('datos','Registro eliminado correctamente!');
    }
}
