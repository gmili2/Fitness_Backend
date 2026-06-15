<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientControllerAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Authentification utilisateur (JWT) ──────────────────────────────────
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [ApiController::class, 'login']);
    Route::post('logout', [ApiController::class, 'logout']);
    Route::post('refresh', [ApiController::class, 'refresh']);
    Route::post('me', [ApiController::class, 'me']);

    // Authentification client
    Route::post('client/login', [ClientControllerAuth::class, 'login']);
    Route::post('client/logout', [ClientControllerAuth::class, 'logout']);
});

// ── Utilisateur connecté ─────────────────────────────────────────────────
Route::group(['middleware' => 'api', 'prefix' => 'user'], function () {
    Route::patch('password', [ClientController::class, 'updatePassword']);
});

// ── Client connecté ──────────────────────────────────────────────────────
Route::group(['middleware' => 'api', 'prefix' => 'client'], function () {
    Route::post('scanner-code-barre', [ClientControllerAuth::class, 'scannerCodeBarre'])->middleware('auth.client');
    Route::get('scanner-code-barre/{id}', [ClientControllerAuth::class, 'getClientScans'])->middleware('auth.client');
    Route::post('me', [ClientControllerAuth::class, 'me'])->middleware('auth.client');
    Route::patch('password', [ClientControllerAuth::class, 'updatePassword'])->middleware('auth.client');
    Route::patch('scans/{id}/update-date-pointage-sortie', [ClientControllerAuth::class, 'updateDatePointageSortie'])->middleware('auth.client');
    Route::get('scans-count-by-day/{date}', [ClientControllerAuth::class, 'getScansWithinWeek'])->middleware('auth.client');
    Route::get('active-scans', [ClientControllerAuth::class, 'getActiveScans'])->middleware('auth.client');

    // Gestion des clients (salle)
    Route::get('clients/active-scans', [ClientController::class, 'getActiveScans']);
    Route::get('clients/scans-count-by-day/{date}', [ClientController::class, 'getScansCountByDay']);
    Route::post('clients/pointer/{clieId}', [ClientController::class, 'addScan']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::patch('clients/{id}', [ClientController::class, 'update']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);
});

// ── Serveur d'images ─────────────────────────────────────────────────────
Route::get('/image/{folder}/{filename}', function ($folder, $filename) {
    if (!in_array($folder, ['clients', 'users'])) {
        abort(403);
    }

    $path = public_path("$folder/$filename");

    if (!file_exists($path)) {
        abort(404);
    }

    return response(file_get_contents($path), 200)
        ->header('Content-Type', mime_content_type($path))
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept');
});
