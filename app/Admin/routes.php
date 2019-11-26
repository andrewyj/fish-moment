<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('user', 'UserController');
    $router->resource('school', 'SchoolController');
    $router->resource('post', 'PostController');
    $router->resource('article', 'ArticleController');
});
