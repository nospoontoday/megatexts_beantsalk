<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthorController;
use App\Http\Controllers\Auth\PublisherController;
use App\Http\Controllers\Auth\EditorController;
use App\Http\Controllers\Auth\ManufacturerController;
use App\Http\Controllers\Auth\DeveloperController;
use App\Http\Controllers\Auth\PlatformController;
use App\Http\Controllers\Auth\AccessModelController;
use App\Http\Controllers\Auth\CustomerController;

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

Route::fallback(function() {
    return response()->json([
        'message' => 'Api Not Found. If error persists, contact info@megatexts.com'], 404);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** Public Routes */
Route::get('customers', [CustomerController::class, 'indexToJson']);
Route::get('authors', [AuthorController::class, 'index']);
Route::get('publishers', [PublisherController::class, 'index']);
Route::get('editors', [EditorController::class, 'index']);
Route::get('manufacturers', [ManufacturerController::class, 'index']);
Route::get('developers', [DeveloperController::class, 'index']);
Route::get('platforms', [PlatformController::class, 'index']);
Route::get('access-models', [AccessModelController::class, 'index']);