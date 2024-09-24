<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VacancyResponseController;
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
Route::middleware(AbilityCheck::class.':moderator')
    ->get('vacancies/{vacancy}/responses', [VacancyController::class, 'responses'])
    ->name('vacancies.responses');

Route::middleware(AuthCheck::class)
    ->resource('/resumes', ResumeController::class)
    ->only(['store', 'update', 'destroy']);

Route::middleware(AuthCheck::class)
    ->resource('/vacancy-responses', VacancyResponseController::class)
    ->only(['store', 'show']);
Route::middleware(AbilityCheck::class.':moderator')
    ->resource('/vacancy-responses', VacancyResponseController::class)
    ->only(['update']);
Route::middleware(AuthCheck::class)
    ->get('/vacancy-responses/{vacancyResponse}/commentaries', [VacancyResponseController::class, 'commentaries'])
    ->name('vacancy-responses.commentaries');
Route::middleware(AuthCheck::class)
    ->post('/vacancy-responses/{vacancyResponse}/commentaries', [VacancyResponseController::class, 'storeCommentary'])
    ->name('vacancy-responses.store-commentary');
Route::middleware(AuthCheck::class)
    ->patch('/vacancy-responses/{vacancyResponse}/cancel', [VacancyResponseController::class, 'cancel'])
    ->name('vacancy-responses.cancel');

Route::middleware(AuthCheck::class)->prefix('user')->group(static function (): void {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/resume', [UserController::class, 'resume'])->name('user.resume');
    Route::get('/vacancy-responses', [UserController::class, 'responses'])->name('user.responses');
});
Route::middleware(AbilityCheck::class.':moderator')->prefix('user')->group(static function (): void {
    Route::get('/vacancies', [UserController::class, 'vacancies'])->name('user.vacancies');
});

require __DIR__.'/auth.php';
