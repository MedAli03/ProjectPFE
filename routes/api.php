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


Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');



   
Route::post('/activate/{id}', 'App\Http\Controllers\UserController@activatePressingAccount');



Route::prefix('commande')->group(function () {

    Route::get('/', 'App\Http\Controllers\CommandeController@index');
    Route::post('/', 'App\Http\Controllers\CommandeController@store');
    Route::post('/', 'App\Http\Controllers\CommandeController@store');
    Route::get('/{id}', 'App\Http\Controllers\CommandeController@show');
    Route::put('/{id}', 'App\Http\Controllers\CommandeController@update');
    Route::delete('/{id}', 'App\Http\Controllers\CommandeController@destroy');
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
    
    Route::prefix('admin')->group(function () {

        Route::post('/activate/{id}', 'App\Http\Controllers\AdminController@activatePressingAccount');
        Route::get('/', 'App\Http\Controllers\AdminController@getPressingsNotActive');
        
        Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
        Route::put('/users/{id}', 'App\Http\Controllers\UserController@update');
        Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
        Route::post('/addadmin', 'App\Http\Controllers\AdminController@createAdminUser');
    });
   
});



// Client routes
Route::group(['middleware' => ['auth:sanctum', 'role:client']],function () {
    
    // Route::get('/users', 'App\Http\Controllers\UserController@index');

    Route::prefix('clients')->group(function () {
        Route::get('/', 'App\Http\Controllers\ClientController@index');
        Route::get('/{id}', 'App\Http\Controllers\ClientController@show');
        Route::put('/{id}', 'App\Http\Controllers\ClientController@update');
        Route::delete('/{id}', 'App\Http\Controllers\ClientController@destroy');
    });

    Route::prefix('pressings')->group(function () {
        Route::get('/', 'App\Http\Controllers\PressingController@index');
        Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
    });

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
    
    // Route::get('/users', 'App\Http\Controllers\UserController@index');

    Route::prefix('pressings')->group(function () {
        Route::get('/', 'App\Http\Controllers\PressingController@index');
        Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
        Route::put('/{id}', 'App\Http\Controllers\PressingController@update');
        Route::delete('/{id}', 'App\Http\Controllers\PressingController@destroy');
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', 'App\Http\Controllers\ClientController@index');
        Route::get('/{id}', 'App\Http\Controllers\ClientController@show');
    });

    Route::prefix('article')->group(function () {
        Route::get('/', 'App\Http\Controllers\ArticleController@index');
        Route::post('/', 'App\Http\Controllers\ArticleController@store');
        Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
        Route::put('/{id}', 'App\Http\Controllers\ArticleController@update');
        Route::delete('/{id}', 'App\Http\Controllers\ArticleController@destroy');
    });
    
});



