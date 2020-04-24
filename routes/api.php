<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('service', "ServiceController@index");
Route::post('service/{id}', "ServiceController@store");
Route::get('service/{id}', "ServiceController@show");
Route::get('service/tes', "ServiceController@show");


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', "UserController@logout");
    Route::get('login/check', "UserController@LoginCheck"); //cek token
});
