<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Category
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', 'CategoryController@index');
});

//Author
Route::group(['prefix' => 'authors'], function () {
    Route::get('/', 'AuthorController@index');
});

//Book
Route::group(['prefix' => 'books'], function () {
    Route::get('/', 'BookController@index');
    Route::get('/detail/{id}', 'BookController@detail');
});


