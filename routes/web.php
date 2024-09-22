<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Middleware\AbilityCheck;
use Illuminate\Support\Facades\Route;

Route::middleware(AbilityCheck::class.':moderator')
    ->resource('/categories', CategoryController::class)
    ->only(['store', 'update', 'destroy']);
Route::resource('/categories', CategoryController::class)
    ->only(['index', 'show']);

Route::middleware(AbilityCheck::class.':moderator')
    ->resource('/organizations', OrganizationController::class)
    ->only(['store', 'update', 'destroy']);
Route::resource('/organizations', OrganizationController::class)
    ->only(['index', 'show']);

require __DIR__.'/auth.php';
