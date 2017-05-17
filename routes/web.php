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
Route::post('/home/', 'ShopController@handle')->name('dashboard-post');
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
Route::post('/shops/shop/single/info', 'ShopsController@getShopInfo')->name('shop.info');
Route::post('/shops/shop/lat/long', 'LocationController@getShopLatLong')->name('shop.lat_long');
Route::get('/products/mobiles/out-of-stock', 'MobileController@outOfStock')->name('mobile.out_of_stock');
Route::get('/users/user/profile', 'UserController@showProfile')->name('user.profile');
Route::post('/users/user/profile', 'UserController@updateProfile')->name('user.profile.update');
Route::get('/users/user/shop/settings', 'ShopController@showShopSettings')->name('shop.settings');
Route::post('/users/user/shop/settings', 'ShopController@updateShopSettings')->name('shop.settings.update');
Route::post('/send-support-email', 'EmailController@send')->name('support.email');
/**
 * search routes
 */
Route::match(['get'], '/search/', 'SearchController@search')->name('search');
Route::get('/search/{filters}', 'SearchController@search')->name('filter-results');
Route::post('/search/live/results', 'SearchController@liveSearch');

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

/**
 * Sales routes
 */
Route::get('/user/{userId}/sales', 'SalesController@showSales')->name('user-sales');
Route::get('/user/{userId}/sales/sell-items', 'SalesController@showItemsToSell')->name('show-sell-items');
Route::get('/user/{userId}/sales/sell-items/product/sell', 'SalesController@fetchProduct')->name('fetch-product');
Route::post('/sales/sell-items/product/sell/{productId}', 'SalesController@sellProduct')->name('sell-product');

/**
 * Email verification routes
 */
Route::post('/register/users/email-verification', 'UserController@verify');
Route::get('/login/email-verification/{id}', 'UserController@showVerificationForm')->name('email-verification-form');


/**
 * Shop routes for displaying frontend shops logic
 * i.e. displaying all the available shops
 */
Route::get('/shops', 'ShopsController@index')->name('shops.index');
Route::get('/shops/single/{shopId}', 'ShopsController@viewShop')->name('shops.single');
Route::post('/shops/maps', 'ShopsController@shopsInfoForMap');


/**
 * Graph routes
 */
Route::post('donut', 'GraphsController@donut')->name('graphs.donut');
Route::post('line', 'GraphsController@line')->name('graphs.line');
Route::post('line/month', 'GraphsController@lineMonth')->name('graphs.lineMonth');

/**
 * Test routes
 */
Route::get('gsm', function (){
    dispatch(new \App\Jobs\SaveGsmDataJob());
    /*$dataController = new \App\Http\Controllers\DataController();
    $dataController->readAndStoreGsmData();*/
});
Route::get('savestores', 'DataController@listFolderFiles');
Route::get('test', function (){
    /*$colors = \App\Color::all();
    foreach ($colors as $c){
        if(preg_match('/\\d/', $c->color)){
            echo $c->color.'<br>';
            $c->color = preg_replace('/\\d/', ' ', $c->color);
            \App\Color::find($c->id)->update(['color' => $c->color]);
        }
    }*/
    //return base_path().'\python\gsm.py';
    /*exec('/usr/bin/python ' . base_path().'\python\gsm.py');
    $command = escapeshellcmd(base_path().'\python\gsm.py');
    $output = shell_exec($command);
    echo $output;*/
    echo 17 < '6';
});
