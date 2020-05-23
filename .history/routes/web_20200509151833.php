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

use Facade\FlareClient\Http\Response;
use Illuminate\Http\Resources\Json\Resource;

Route::get('/', function () {
	return view('welcome');
});
Route::get('/test', 'TestController@index');
Route::get('/file', 'TestController@file');

Route::options('/', function () {
	return response(null)->header("Access-Control-Allow-Origin", "*")->header("Access-Control-Allow-Methods", "*")->header("Access-Control-Allow-Headers", "*");
	// return view('welcome');
});