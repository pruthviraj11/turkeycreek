<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\CouponController;
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
Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryController::class,'api_parent']);
Route::get('/category/{id}', [CategoryController::class,'api_parent']);
 */

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route::get('/category', [CategoryController::class, 'api_parent']);
    // Route::get('/category/{id}', [CategoryController::class, 'api_parent']);
    // Route::get('/store/{category_id}', [StoresController::class, 'api_category_wise']);

    // Route::get('/coupons', [CouponController::class, 'api_store_wise']);
    // Route::get('/coupons/{store_id}', [CouponController::class, 'api_store_wise']);
});

Route::get('/category', [CategoryController::class, 'api_parent']);
Route::get('/category/{id}', [CategoryController::class, 'api_parent']);
Route::get('/store/{category_id}', [StoresController::class, 'api_category_wise']);

Route::get('/coupons', [CouponController::class, 'api_store_wise']);
Route::get('/coupons/{store_id}', [CouponController::class, 'api_store_wise']);