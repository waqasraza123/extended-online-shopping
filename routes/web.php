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
 * search routes
 */
Route::post('/search', 'SearchController@search')->name('search');

/**
 * frontend user routes
 */
Route::get('/mobiles/{brand}/{id}', 'WelcomeController@showMobile')->name('show-mobile');
/**
 * Test routes
 */
Route::get('test', 'DataController@readData');
Route::get('test2', function (){
    $ip = $_SERVER['REMOTE_ADDR'];
    $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
    print_r($details);
});