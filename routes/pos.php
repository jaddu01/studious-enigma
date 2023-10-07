<?php

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
|
 */

use App\Http\Controllers\Pos\AuthController;
use App\Http\Controllers\Pos\CategoryController;
use App\Http\Controllers\Pos\ProductController;
use App\Http\Controllers\Pos\UserController;


//login
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:pos')->group(function(){
    
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/with-quantity', [ProductController::class, 'productWithQuantity']);
    });
    
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category_id}/sub-categories', [CategoryController::class, 'subCategories']);
    
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index']);
    });
});