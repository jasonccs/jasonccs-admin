<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::namespace('App\Http\Controllers\Jwt')->group(function ($outer) {
//    Route::post('register', 'JwtAuthController@register');
//    Route::post('login', 'JwtAuthController@login');
//    Route::group(['middleware' => 'jwt.auth'], function () {
//        Route::get('user', 'JwtAuthController@getAuthUser');
//    });
//});


Route::namespace('App\Http\Controllers')->middleware('validation')->prefix('auth')->group(function () {

    //抛出预期的错误，例如请求字段格式错误
    Route::get('/c', 'Controller@store'); // 使用注解方式实现表单验证

    Route::namespace('Jwt')->group( function ($router) {

        Route::post('login', 'AuthController@login');

        Route::post('register', 'AuthController@register');

        Route::middleware(['jwt:auth'])->group( function ($router) {
                Route::post('logout', 'AuthController@logout');
                Route::post('refresh', 'AuthController@refresh');
                Route::post('me', 'AuthController@me');
        });

    });

});

