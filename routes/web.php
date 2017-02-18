<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'WelcomeController@index']);

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/passport-tokens', function (){
    return view('auth.passport-authentication');
});
Route::post('/python-data/{shop_name}/{category}/{token}', 'PythonController@save');

/**
 * Shop routes
 */
Route::post('register-shop', 'ShopController@create');
Route::resource('products/mobile', 'MobileController');
Route::resource('products/laptop', 'LaptopController');
Route::get('/products/brands/{id}/{name}',
    ['as' => 'brands', 'uses' => 'BrandController@showProducts']);


/**
 * Test routes
 */
Route::get('test', function(){
    return \Illuminate\Support\Facades\Auth::user()->id;
});