<?php

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
|
 */

use App\Http\Controllers\Pos\CategoryController;
use App\Http\Controllers\Pos\ProductController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
});

Route::get('categories', [CategoryController::class, 'index']);