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

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'role:admin']],function () {
    
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

    Route::prefix('admin')->group(function () {
        Route::post('/activate/{id}', 'App\Http\Controllers\AdminController@activatePressingAccount');
        Route::put('/update/{id}', 'App\Http\Controllers\AdminController@updateAdminUser');
        Route::get('/pressingnoactive', 'App\Http\Controllers\AdminController@getPressingsNotActive');
        Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');
        Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
        Route::post('/addadmin', 'App\Http\Controllers\AdminController@createAdminUser');
    });
   
});


// Client routes
Route::group(['middleware' => ['auth:sanctum', 'role:client']],function () {

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
  

    Route::prefix('client')->group(function () {
    
        Route::prefix('clients')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\ClientController@show');
            Route::put('/{id}', 'App\Http\Controllers\ClientController@update');
        });

        Route::prefix('pressings')->group(function () {
            Route::get('/all', 'App\Http\Controllers\PressingController@activePressings');
            Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
        });

        Route::prefix('rating')->group(function () {
            Route::post('/rate/{id}', 'App\Http\Controllers\RatingController@rate');
            Route::put('/update/{id}', 'App\Http\Controllers\RatingController@updateRating');
            Route::delete('/{id}', 'App\Http\Controllers\RatingController@deleteRate');
        });

        Route::prefix('commande')->group(function () {
            Route::get('/all', 'App\Http\Controllers\CommandeController@getCommandsByClient');
            Route::post('/add', 'App\Http\Controllers\CommandeController@store');
            Route::get('/{id}', 'App\Http\Controllers\CommandeController@show');
            Route::delete('/delete/{id}', 'App\Http\Controllers\CommandeController@deletePendingCommande');
        }); 

        Route::prefix('service')->group(function () {
            Route::get('/{id}', 'App\Http\Controllers\ServiceController@getServicesForPressing');
        });

        Route::prefix('article')->group(function () {
            Route::get('/all', 'App\Http\Controllers\ArticleController@getCommandsByClient');
            Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
        }); 

        Route::prefix('tarif')->group(function () {
            Route::get('/', 'App\Http\Controllers\TarifController@index');
            Route::get('/{id}', 'App\Http\Controllers\TarifController@show');
        });

        Route::prefix('facture')->group(function () {
            Route::get('/', 'App\Http\Controllers\FactureController@index');
            Route::get('/{id}', 'App\Http\Controllers\FactureController@show');
            Route::delete('/{id}', 'App\Http\Controllers\FactureController@destroy');
        });

    });

});


// Pressing routes
Route::group(['middleware' => ['auth:sanctum', 'role:pressing']],function () {
    
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

    Route::prefix('pressing')->group(function () {

        Route::prefix('pressings')->group(function () {
            Route::get('/', 'App\Http\Controllers\PressingController@index');
            Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\PressingController@updatePressingProfile');
        });

        Route::prefix('article')->group(function () {
            Route::get('/', 'App\Http\Controllers\ArticleController@index');
            Route::post('/', 'App\Http\Controllers\ArticleController@store');
            Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
            Route::put('/{id}', 'App\Http\Controllers\ArticleController@update');
            Route::delete('/{id}', 'App\Http\Controllers\ArticleController@destroy');
        });

        Route::prefix('service')->group(function () {
            Route::get('/', 'App\Http\Controllers\ServiceController@index');
            Route::post('/', 'App\Http\Controllers\ServiceController@store');
            Route::get('/all', 'App\Http\Controllers\ServiceController@getServicesForCurrentUserPressing');
            Route::put('/{id}', 'App\Http\Controllers\ServiceController@update');
            Route::delete('/{id}', 'App\Http\Controllers\ServiceController@destroy');
        });

        Route::prefix('commande')->group(function () {

            Route::get('/', 'App\Http\Controllers\CommandeController@getCommandsByPressing');
            Route::get('/{id}', 'App\Http\Controllers\CommandeController@show');
            Route::put('/status/{id}', 'App\Http\Controllers\CommandeController@modifyStatus');
            Route::post('/invoice/{id}', 'App\Http\Controllers\CommandeController@addingInvoice');
        });
        
        Route::prefix('tarif')->group(function () {
            Route::get('/', 'App\Http\Controllers\TarifController@index');
            Route::post('/', 'App\Http\Controllers\TarifController@store');
            Route::get('/{id}', 'App\Http\Controllers\TarifController@show');
            Route::put('/{id}', 'App\Http\Controllers\TarifController@update');
            Route::delete('/{id}', 'App\Http\Controllers\TarifController@destroy');
        });

        Route::prefix('facture')->group(function () {
            Route::get('/', 'App\Http\Controllers\FactureController@index');
            Route::get('/{id}', 'App\Http\Controllers\FactureController@show');
            Route::put('/{id}', 'App\Http\Controllers\FactureController@update');
            Route::delete('/{id}', 'App\Http\Controllers\FactureController@destroy');
        });
    });
        
});



