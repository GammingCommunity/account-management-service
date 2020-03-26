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
    response('',405);
});
Route::get('/test', 'TestController@index');
Route::get('/file', 'TestController@file');

Route::options('/', function () {
	return view('welcome');
});