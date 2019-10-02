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
Route::post('/login', 'Auth\LoginController@store');  
Route::post('/register', 'Auth\RegisterController@register');  
Route::get('/threads', 'ThreadController@index');
Route::post('/threads/create', 'ThreadController@store');
Route::get('/threads/{id}', 'ThreadController@show');
Route::post('/threads/{id}/comment/create', 'CommentController@store');
Route::post('/threads/{id}/comment/{commentId}/MarkAsVisible', 'CommentController@MarkAsVisible');
Route::put('/threads/{id}/comment/{commentId}', 'CommentController@update');
Route::put('/threads/{id}', 'ThreadController@update');