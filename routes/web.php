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
    $save = null;
    if(!file_exists(public_path().'/uploads/mobiles/daraz')){
        \Illuminate\Support\Facades\File::makeDirectory(public_path(). '/uploads/products/mobiles/daraz', 0777, true);
    }
    /*while($save == null ){
        try{
            $save = copy('https://static.daraz.pk/p/apple-4335-9133646-1-catalog_grid_3.jpg',
                public_path().'/uploads/products/flower.png');
        }
        catch (Exception $err){
            $stop = null;
        }
    }*/
});