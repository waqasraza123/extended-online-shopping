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

Route::get('/home', 'HomeController@index')->name('dashboard');
Route::post('/home/', 'HomeController@handle')->name('dashboard-post');
Route::get('/passport-tokens', function (){
    return view('auth.passport-authentication');
});
Route::post('/python-data/{shop_name}/{category}/{token}', 'PythonController@save');

/**
 * Shop routes
 */
Route::get('/register/shop', 'ShopController@showRegisterShopForm')->name('register-shop');
Route::post('register-shop', 'ShopController@create');
Route::resource('products/mobile', 'MobileController');
Route::resource('products/laptop', 'LaptopController');
Route::get('/products/brands/{id}/{name}',
    ['as' => 'brands', 'uses' => 'BrandController@showProducts']);
Route::get('/login/shop', 'ShopLoginController@shopLoginForm')->name('shop-login');
Route::post('/login/shop', 'ShopLoginController@shopLogin')->name('shop-login-post');
Route::get('/user/{id}/select-shop', 'ShopController@showShopsForm')->name('select-shops-form');

/**
 * search routes
 */
Route::post('/search', 'SearchController@search')->name('search');
Route::get('/search/{filters}', 'SearchController@search')->name('filter-results');

/**
 * frontend user routes
 */
Route::get('/mobiles/{brand}/{id}', 'WelcomeController@showMobile')->name('show-mobile');
Route::post('/mobiles/get-titles', 'MobileController@getTitles')->name('get-titles');
Route::post('/mobiles/get-colors', 'MobileController@getColors')->name('get-colors');
Route::post('/mobiles/get-storages', 'MobileController@getStorages')->name('get-storages');
Route::post('/mobiles/get-bulk', 'MobileController@getBulkData')->name('get-bulk');
Route::post('/mobiles/save/bulk-data', 'MobileController@saveBulkData')->name('save-bulk-data');
Route::post('/mobiles/save/excel-bulk-data', 'MobileController@saveExcelBulk')->name('save-excel');
Route::get('/user/{userId}/sales', 'SalesController@showSales')->name('user-sales');

/**
 * Email verification routes
 */
//Route::get('register/verify/{token}', 'Auth\RegisterController@verify');
Route::post('/register/users/email-verification', 'UserController@verify');
Route::get('/login/email-verification/{id}', 'UserController@showVerificationForm')->name('email-verification-form');

/**
 * Test routes
 */
Route::get('gsm', function (){
    dispatch(new \App\Jobs\SaveGsmDataJob());
});
Route::get('savestores', 'DataController@listFolderFiles');
Route::get('test2', function (){
    /*$colors = \App\Color::all();
    foreach ($colors as $c){
        if(preg_match('/\\d/', $c->color)){
            echo $c->color.'<br>';
            $c->color = preg_replace('/\\d/', ' ', $c->color);
            \App\Color::find($c->id)->update(['color' => $c->color]);
        }
    }*/
    /*$arr = ['Blue Area, Islamabad, Pakistan', 'F-8 Markaz, Islamabad, Islamabad Capital Territory, Pakistan',
        'F-7 Markaz, Islamabad, Islamabad Capital Territory, Pakistan', 'Singapore Plaza, Bank Road, Saddar, Rawalpindi, Pakistan'];

    foreach (\App\Shop::where('location', '<>', 'online')->get() as $item){
        $item->location = $arr[rand(0, 3)];
        $item->save();
    }*/
    return Auth::user()->shops;
    return Auth::user()->shops()->where('shops.id', 179)->first()->products()->get();

});
