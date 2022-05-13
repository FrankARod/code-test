<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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


Route::post('token', [LoginController::class, 'token']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/image', [ProductController::class, 'uploadImage']);
    
    Route::prefix('profile')->group(function () {
        Route::get('products', [ProfileController::class, 'products']);

        Route::middleware('can:manageProducts,App\Models\User')->group(function () {
            Route::post('add-product', [ProfileController::class, 'attachProduct'])->name('profile.attach-product');
            Route::post('remove-product', [ProfileController::class, 'detachProduct'])->name('profile.detach-product');
        });
    });
    
});
