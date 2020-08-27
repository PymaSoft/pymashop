<?php

use App\Image;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\Return_;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* // Versión 7

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); */

// Para hacer las pruebas con las imágenes
Route::get('/prueba', function () {
    Gate::authorize('haveaccess','role.show');
    $user = User::find(1);
    
    return $user;
});

// Mostrar resultados
Route::get('/resultados', function () {
    $image = Image::orderBy('id','Desc')->get();
    return $image;
});

Route::get('/', function () {
    /* $prod = Product::findOrFail(1);
    $prod->slug = 'product-3';
    $prod->save();
    return $prod; */
    /* $prod = new Product();
    $prod->name = 'Producto 1';
    $prod->slug = 'Producto 1';
    $prod->category_id = 1;
    $prod->short_description = 'Producto';
    $prod->long_description = 'Producto';
    $prod->specs = 'Producto';
    $prod->data_of_interest = 'Producto';
    $prod->state = 'Nuevo';
    $prod->active = 'Si';
    $prod->slidermain = 'No';
    $prod->save();
    return $prod; */
    // return view('welcome');
    /* $cat = Category::find(1)->products;
    return $cat; */
    return view('tienda.index');
});

Route::resource('/role', 'RoleController')->names('role');

Route::resource('/user', 'UserController', ['except'=>[
    'create','store']])->names('user');
    
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', function () {
    return view('plantilla.admin');
})->name('admin');

Route::resource('admin/category', 'Admin\AdminCategoryController')->names('admin.category');

Route::resource('admin/product', 'Admin\AdminProductController')->names('admin.product');

Route::get('cancelar/{ruta}', function ($ruta) {
    return redirect()->route($ruta)->with('cancelar','Acción Cancelada!');
})->name('cancelar');
