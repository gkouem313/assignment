<?php

use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\ShopCategoryController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\UserController;
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

Route::group([
    'prefix' => 'users',
    'controller' => UserController::class
], function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login')->middleware('auth.basic');
});

Route::group([
    'prefix' => 'shops',
    'middleware' => 'auth:sanctum',
    'controller' => ShopController::class
], function () {
    Route::get('/', 'index');
    Route::get('/create', 'create');
    Route::post('/', 'store');
    Route::get('/{shopId}', 'show');
    Route::get('/{shopId}/edit', 'edit');
    Route::patch('/{shopId}', 'update');
});

Route::group([
    'prefix' => 'offers',
    'middleware' => 'auth:sanctum',
    'controller' => OfferController::class
], function () {
    Route::post('/', 'store');
});