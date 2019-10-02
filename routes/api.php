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
Route::get('/');
Route::post('/login', 'Auth\LoginController@authenticate');  
Route::post('/register', 'Auth\RegisterController@register');  
Route::get('/threads', 'ThreadController@index');
Route::post('/threads/create', 'ThreadController@store');
Route::put('/threads/{id}', 'ThreadController@update');