<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [ApiController::class, 'login']);
    Route::post('logout', 'ApiController@logout');
    Route::post('refresh', 'ApiController@refresh');
    Route::post('me', 'ApiController@me');

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'client'

], function ($router) {

    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);  // Paramètre dynamique {id}
    Route::patch('clients/{id}', [ClientController::class, 'update']);  // Paramètre dynamique {id}

});