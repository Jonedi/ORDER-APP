<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use \App\Http\Middleware\ApiAuthMiddleware;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Test Orm
Route::get('/test-orm', 'PruebasController@testOrm');

// Rutas Usuario
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');

// Rutas Productos
Route::resource('api/product', 'productController');

// Rutas Ordenes
Route::resource('api/order', 'orderController');

