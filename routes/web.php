<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Middleware\AbilityCheck;
use App\Http\Middleware\AuthCheck;
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

Route::middleware(AbilityCheck::class.':moderator')
    ->resource('/skills', SkillController::class)
    ->only(['store', 'update', 'destroy']);
Route::resource('/skills', SkillController::class)
    ->only(['index', 'show']);

Route::middleware(AbilityCheck::class.':moderator')
    ->resource('/vacancies', VacancyController::class)
    ->only(['store', 'update', 'destroy']);
Route::resource('/vacancies', VacancyController::class)
    ->only(['index', 'show']);

Route::middleware(AuthCheck::class)
    ->resource('/resumes', ResumeController::class)
    ->only(['store', 'update', 'destroy']);

Route::middleware(AuthCheck::class)->group(static function (): void {
    Route::get('/user', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/resume', [UserController::class, 'resume'])->name('user.resume');
});

require __DIR__.'/auth.php';
