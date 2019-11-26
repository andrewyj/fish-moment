<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('static.product.index');
});
Route::get('/WechatQR', function () {
    return view('static.product.WechatQR');
});
Route::get('/yonghu', function () {
    return view('static.product.yonghu');
});
Route::get('/yinsi', function () {
    return view('static.product.yinsi');
});
Route::get('/map', function () {
    return view('static.map.index');
});
Route::get('article/{article}', 'ArticleController@show')->name('article.show');

