<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', 'App\Http\Controllers\UserController@register');
Route::post('/login', 'App\Http\Controllers\UserController@login');

Route::prefix('clients')->group(function () {
    Route::get('/', 'App\Http\Controllers\ClientController@index');
    Route::post('/', 'App\Http\Controllers\ClientController@store');
    Route::get('/{id}', 'App\Http\Controllers\ClientController@show');
    Route::put('/{id}', 'App\Http\Controllers\ClientController@update');
    Route::delete('/{id}', 'App\Http\Controllers\ClientController@destroy');
});


Route::prefix('pressing')->group(function () {

    Route::get('/', 'App\Http\Controllers\PressingController@index');
    Route::post('/', 'App\Http\Controllers\PressingController@store');
    Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
    Route::put('/{id}', 'App\Http\Controllers\PressingController@update');
    Route::delete('/{id}', 'App\Http\Controllers\PressingController@destroy');
});


Route::prefix('rating')->group(function () {

    Route::get('/', 'App\Http\Controllers\RatingController@index');
    Route::post('/', 'App\Http\Controllers\RatingController@store');
    Route::get('/{id}', 'App\Http\Controllers\RatingController@show');
    Route::put('/{id}', 'App\Http\Controllers\RatingController@update');
    Route::delete('/{id}', 'App\Http\Controllers\RatingController@destroy');
});


Route::prefix('commande')->group(function () {
    
    Route::get('/', 'App\Http\Controllers\CommandeController@index');
    Route::post('/', 'App\Http\Controllers\CommandeController@store');
    Route::get('/{id}', 'App\Http\Controllers\CommandeController@show');
    Route::put('/{id}', 'App\Http\Controllers\CommandeController@update');
    Route::delete('/{id}', 'App\Http\Controllers\CommandeController@destroy');
    Route::put('/status/{id}', 'App\Http\Controllers\CommandeController@modifyStatus');
    Route::put('/validate/{id}', 'App\Http\Controllers\CommandeController@validation');
});