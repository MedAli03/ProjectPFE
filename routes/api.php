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
Route::post('/password/reset', 'App\Http\Controllers\AuthController@sendPasswordResetLink');

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

    Route::prefix('admin')->group(function () {
        Route::put('/activate/{id}', 'App\Http\Controllers\AdminController@activatePressingAccount');
        Route::put('/update/{id}', 'App\Http\Controllers\AdminController@updateAdminUser');
        Route::get('/pressingnoactive', 'App\Http\Controllers\AdminController@getPressingsNotActive');
        Route::get('/clients', 'App\Http\Controllers\AdminController@getClients');
        Route::get('/pressings', 'App\Http\Controllers\PressingController@activePressings');
        Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');
        Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
        Route::post('/addadmin', 'App\Http\Controllers\AdminController@createAdminUser');
        Route::delete('/delete/{id}', 'App\Http\Controllers\PressingController@destroy');

        //  Articles
        Route::get('/articles', 'App\Http\Controllers\ArticleController@index');
        Route::get('/articles/getnotavailable', 'App\Http\Controllers\ArticleController@getNotAvailableArticle');
        Route::post('/articles/add', 'App\Http\Controllers\ArticleController@store');
        Route::put('/articles/edit/{id}', 'App\Http\Controllers\ArticleController@makeAvailable');
        Route::delete('/articles/delete/{id}', 'App\Http\Controllers\ArticleController@destroy');

        //  services
        Route::post('/addservice', 'App\Http\Controllers\ServiceController@store');
        Route::get('/getservice', 'App\Http\Controllers\ServiceController@getAvailableServices');
        Route::get('/servicenotavailable', 'App\Http\Controllers\ServiceController@getServicesNotAvailable');
        Route::put('/accepte/{id}', 'App\Http\Controllers\ServiceController@makeAvailable');
        Route::delete('/delete/{id}', 'App\Http\Controllers\ServiceController@destroy');
    });
});


// Client routes
Route::group(['middleware' => ['auth:sanctum', 'role:client']], function () {

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
            Route::get('/all', 'App\Http\Controllers\ArticleController@index');
            Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
        });

        Route::prefix('tarif')->group(function () {
            Route::get('/', 'App\Http\Controllers\TarifController@index');
            Route::get('/{id}', 'App\Http\Controllers\TarifController@show');
        });

        Route::prefix('facture')->group(function () {
            Route::get('/', 'App\Http\Controllers\FactureController@index');
            Route::get('/{id}', 'App\Http\Controllers\FactureController@show');
        });
    });
});


// Pressing routes
Route::group(['middleware' => ['auth:sanctum', 'role:pressing']], function () {

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

    Route::prefix('pressing')->group(function () {

        Route::prefix('pressings')->group(function () {
            Route::get('/', 'App\Http\Controllers\PressingController@index');
            Route::get('/{id}', 'App\Http\Controllers\PressingController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\PressingController@updatePressingProfile');
        });

        Route::prefix('article')->group(function () {
            Route::get('/', 'App\Http\Controllers\ArticleController@index');
            Route::get('/notavailable', 'App\Http\Controllers\ArticleController@getNotAvailableArticle');

            Route::post('/add', 'App\Http\Controllers\ArticleController@store');
            Route::post('/frompressing', 'App\Http\Controllers\ArticleController@storeFromPressing');

            Route::get('/{id}', 'App\Http\Controllers\ArticleController@show');
            Route::put('/{id}', 'App\Http\Controllers\ArticleController@update');
            Route::delete('/{id}', 'App\Http\Controllers\ArticleController@destroy');
        });

        Route::prefix('service')->group(function () {
            Route::get('/', 'App\Http\Controllers\ServiceController@getAvailableServices');
            Route::get('/notavailable', 'App\Http\Controllers\ServiceController@getServicesNotAvailable');

           
            Route::post('/frompressing', 'App\Http\Controllers\ServiceController@storeFromPressing');


            Route::put('/makeavailable/{id}', 'App\Http\Controllers\ServiceController@makeAvailable');
            Route::put('/{id}', 'App\Http\Controllers\ServiceController@update');
            Route::delete('/{id}', 'App\Http\Controllers\ServiceController@destroy');
        });

        Route::prefix('commande')->group(function () {

            Route::get('/', 'App\Http\Controllers\CommandeController@getCommandsByPressing');
            Route::get('/{id}', 'App\Http\Controllers\CommandeController@show');
            Route::put('/status/{id}', 'App\Http\Controllers\CommandeController@modifyStatus');
            Route::put('/accepte/{id}', 'App\Http\Controllers\CommandeController@markAsInProgress');
            Route::put('/terminer/{id}', 'App\Http\Controllers\CommandeController@finish');
            Route::post('/invoice/{id}', 'App\Http\Controllers\CommandeController@addingInvoice');
            Route::delete('/delete/{id}', 'App\Http\Controllers\CommandeController@destroy');
        });

        Route::prefix('tarif')->group(function () {
            Route::get('/', 'App\Http\Controllers\TarifController@getTarifsForPressing');
            Route::post('/ajouter', 'App\Http\Controllers\TarifController@store');
            Route::get('/{id}', 'App\Http\Controllers\TarifController@show');
            Route::put('/{id}', 'App\Http\Controllers\TarifController@update');
            Route::delete('/{id}', 'App\Http\Controllers\TarifController@destroy');
        });

        Route::prefix('facture')->group(function () {
            Route::post('/facturer/{id}', 'App\Http\Controllers\FactureController@facturer');
            Route::get('/all', 'App\Http\Controllers\FactureController@getPressingFactures');
            Route::get('/{id}', 'App\Http\Controllers\FactureController@show');
            Route::put('/{id}', 'App\Http\Controllers\FactureController@markAsPaid');
            Route::delete('/{id}', 'App\Http\Controllers\FactureController@destroy');
        });
        Route::prefix('rating')->group(function () {
            Route::get('/', 'App\Http\Controllers\RatingController@pressingGetRatings');
            Route::get('/m', 'App\Http\Controllers\RatingController@pressingAverageRating');

        });
    });
});
