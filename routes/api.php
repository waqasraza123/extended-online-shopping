<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return \App\Mobile::where('title', 'LIKE', 'iphone')->get();
})->middleware('auth:api');

Route::get("/search/{search_term}", 'APIController@searchData')->name('search-api');
Route::get("/search/single/{id}", 'APIController@returnSinglePhoneData')->name('search-api-single');
Route::get("/search/shop/{id}", 'APIController@returnShopData')->name('api-shop');
