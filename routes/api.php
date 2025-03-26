<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientControllerAuth;
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

});


Route::group([

    'middleware' => 'api',
    'prefix' => 'user'

], function ($router) {

    Route::patch('password', [ClientController::class, 'updatePassword']);

});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    // ... autres routes auth
    Route::post('client/login', [ClientControllerAuth::class, 'login']); // Route de login client
    Route::post('client/logout', [ClientControllerAuth::class, 'logout']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'client'

], function ($router) {
    Route::post('scanner-code-barre', [ClientControllerAuth::class, 'scannerCodeBarre'])->middleware('auth.client');
    Route::get('scanner-code-barre/{id}', [ClientControllerAuth::class, 'getClientScans'])->middleware('auth.client');
    Route::post('me', [ClientControllerAuth::class, 'me'])->middleware('auth.client');
    Route::patch('password', [ClientControllerAuth::class, 'updatePassword'])->middleware('auth.client');
    Route::get('test/{id}', [ClientControllerAuth::class, 'testMethod']);
    Route::patch('scans/{id}/update-date-pointage-sortie', [ClientControllerAuth::class, 'updateDatePointageSortie'])->middleware('auth.client');
    Route::get('scans-count-by-day/{date}', [ClientControllerAuth::class, 'getScansWithinWeek'])->middleware('auth.client');
    Route::get('active-scans', [ClientControllerAuth::class, 'getActiveScans'])->middleware('auth.client');

   
    Route::get('clients/active-scans', [ClientController::class, 'getActiveScans']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);  // Paramètre dynamique {id}
    Route::patch('clients/{id}', [ClientController::class, 'update']);  // Paramètre dynamique {id}
    Route::get('clients/scans-count-by-day/{date}', [ClientController::class, 'getScansCountByDay']);

});