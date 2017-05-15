<?php

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
Route::post("/search/{search_term}/{api_token}", 'APIController@searchData')->name('search-api');
Route::post("/search/single/{id}/{api_token}", 'APIController@returnSinglePhoneData')->name('search-api-single');
Route::post("/home/{api_token}", 'APIController@homePageData');
Route::post("/search/shop/{id}/{api_token}", 'APIController@returnShopData')->name('api-shop');
