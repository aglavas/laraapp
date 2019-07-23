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


Route::get('login', 'Auth\AuthController@login');

Route::group(['middleware' => 'auth:api'], function(){
    //Auth
    Route::post('logout', 'Auth\AuthController@logout');

    //Product
    Route::get('company/{company}/product/{product}', 'ProductController@show');
    Route::get('company/{company}/products', 'ProductController@list');
    Route::post('company/{company}/product', 'ProductController@store');
    Route::delete('company/{company}/product/{product}', 'ProductController@destroy');
    Route::patch('company/{company}/product/{product}', 'ProductController@update');
    Route::get('products', 'ProductController@getProducts');
});
