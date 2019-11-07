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
Route::group(["prefix" => "v1"], function () {
    Route::group(['middleware' => ['auth:api']], function() {
        
        //user
        Route::get('/user', 'UserController@user');
        Route::post('/logout', 'UserController@login');
        Route::post('/user', 'UserController@modify');
        
        //social pool
        Route::post("/social-pool/join/{socialPool}", 'SocialPoolController@join');
        Route::post("/social-pool/quit/{socialPool}", 'SocialPoolController@quit');
        Route::post("/social-pool", 'SocialPoolController@store');
        Route::patch("/social-pool/{socialPool}", 'SocialPoolController@update');
    });
    
    //user
    Route::post('/login', 'UserController@login');
    Route::post("user-third-auth/wx", 'UserThirdAuthController@wxStore');
    Route::get('/wx-check', 'UserThirdAuthController@wxCheck');
    
    Route::get('/social-pools', 'SocialPoolController@socialPools');
    Route::get('/social-pool/{socialPool}', 'SocialPoolController@detail');
});


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
