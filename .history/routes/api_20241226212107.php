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
    Route::post('logout', [ApiController::class, 'logout']);
    Route::post('refresh', [ApiController::class, 'refresh']);
    Route::post('me', [ApiController::class, 'me']);
    Route::post('clientl/login', [ClientController::class, 'login']); // Login client (ajouté ici)
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'client'

], function ($router) {
    Route::get('scanner-code-barre', [ClientController::class, 'scannerCodeBarre'])->middleware('auth.client');
    Route::get('me', [ClientController::class, 'me'])->middleware('auth.client');


    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);  // Paramètre dynamique {id}
    Route::patch('clients/{id}', [ClientController::class, 'update']);  // Paramètre dynamique {id}

});