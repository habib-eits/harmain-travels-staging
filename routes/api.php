<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PartyAuthController;
use App\Http\Controllers\Api\PartyLoginController;
use App\Http\Controllers\Api\PartyLedgerController;
use App\Http\Controllers\Api\PartyLedgerDataController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/test', function () {
    return response()->json(['status' => 'axios working']);
});

Route::get('party/login', [PartyLoginController::class, 'show']);
Route::post('party/login', [PartyAuthController::class ,'login']);
Route::get('/party/ledger', [PartyLedgerController::class, 'index']);

Route::middleware('auth:party-api')->group(function () {
    Route::post('/party/ledger', [PartyLedgerDataController::class, 'index']);
    Route::post('/party/logout', [PartyAuthController::class, 'logout']);
});