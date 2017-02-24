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

Route::get('/', ['uses' => 'HomeController@index']);

Route::get('/login', ['uses' => 'AuthController@login']);

Route::post('/login', ['uses' => 'AuthController@auth']);

Route::get('/logout', ['uses' => 'AuthController@logout']);

Route::get('/dashboard/overview', ['uses' => 'DashboardController@overview']);

Route::get('/dashboard/apps/user', ['uses' => 'DashboardController@appsUser']);

Route::get('/dashboard/apps/admin', ['uses' => 'DashboardController@appsAdmin']);

Route::get('/dashboard/users', ['uses' => 'DashboardController@users']);

Route::post('/users/add', ['uses' => 'UserController@addUser']);

Route::get('/users/get/{id}', ['uses' => 'UserController@getUser']);

Route::get('/dashboard/message', ['uses' => 'DashboardController@message']);

Route::get('/dashboard/global/news', ['uses' => 'DashboardController@getGlobalNews']);

Route::post('/users/update/', ['uses' => 'UserController@updateUser']);

Route::get('/users/profile', ['uses' => 'UserController@profile']);

Route::post('/users/reset/password', ['uses' => 'UserController@resetPassword']);

Route::post('/users/update/profile', ['uses' => 'UserController@updateProfile']);

Route::post('instance/create', ['uses' => 'InstanceController@createInstance']);
