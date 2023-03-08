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


   
Route::post('/activate/{id}', 'App\Http\Controllers\UserController@activatePressingAccount');



Route::prefix('pressing')->group(function () {

    Route::get('/', 'App\Http\Controllers\PressingController@index');
    Route::post('/', 'App\Http\Controllers\PressingController@store');
    Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
    Route::put('/{id}', 'App\Http\Controllers\PressingController@update');
    Route::delete('/{id}', 'App\Http\Controllers\PressingController@destroy');
});


// Route::prefix('rating')->group(function () {

//     Route::get('/', 'App\Http\Controllers\RatingController@index');
//     Route::post('/', 'App\Http\Controllers\RatingController@store');
//     Route::get('/{id}', 'App\Http\Controllers\RatingController@show');
//     Route::put('/{id}', 'App\Http\Controllers\RatingController@update');
//     Route::delete('/{id}', 'App\Http\Controllers\RatingController@destroy');
// });




// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'role:admin']],function () {
    
    Route::post('/activate/{id}', 'App\Http\Controllers\UserController@activatePressingAccount');
});



// Client routes
Route::group(['middleware' => ['auth:sanctum', 'role:client']],function () {
    
    Route::get('/users', 'App\Http\Controllers\UserController@index');

    Route::prefix('rating')->group(function () {
    Route::get('/', 'App\Http\Controllers\RatingController@index');
    Route::post('/', 'App\Http\Controllers\RatingController@rate');
    Route::get('/{id}', 'App\Http\Controllers\RatingController@show');
    Route::put('/{id}', 'App\Http\Controllers\RatingController@updateRating');
    Route::delete('/{id}', 'App\Http\Controllers\RatingController@deleteRate');
});

});


// Pressing routes
Route::group(['middleware' => ['auth:sanctum', 'role:pressing']],function () {
    
    Route::get('/users', 'App\Http\Controllers\UserController@index');

    Route::prefix('article')->group(function () {
        Route::get('/', 'App\Http\Controllers\ArticleController@index');
        Route::post('/', 'App\Http\Controllers\ArticleController@store');
        Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
        Route::put('/{id}', 'App\Http\Controllers\ArticleController@update');
        Route::delete('/{id}', 'App\Http\Controllers\ArticleController@destroy');
    });
    
});



