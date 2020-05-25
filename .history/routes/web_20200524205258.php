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
Route::get('/test', 'TestControl ler@index');




Route::get('/file', 'FileController@index');
Route::post('/file', 'FileController@upload');
Route::post('/chat', 'ChattingController@chat');
Route::get('/chat', 'ChattingController@index');

// Route::options('/', function () {
// 	return response(null)->header("Access-Control-Allow-Origin", "*")->header("Access-Control-Allow-Methods", "*")->header("Access-Control-Allow-Headers", "*");
// 	// return view('welcome');
// });