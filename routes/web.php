<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('/locale/{locale}', function (string $locale) {
    session(['locale' => $locale]);

    return back();
})->whereIn('locale', SetLocale::LOCALES)->name('locale');

// Master data
Route::resource('genres', GenreController::class)->except('show');
Route::resource('languages', LanguageController::class)->except('show');
Route::resource('people', PersonController::class)->except('show');
Route::resource('seasons', SeasonController::class)->except('show');

// Content
Route::resource('movies', MovieController::class)->except('show');
Route::resource('shows', ShowController::class)->except('show');

// Plans and payments. Payments cannot be edited or deleted.
Route::resource('packages', PackageController::class)->except('show');
Route::resource('coupons', CouponController::class)->except('show');
Route::resource('subscriptions', SubscriptionController::class)->only(['index', 'create', 'store']);
Route::resource('rentals', RentalController::class)->only(['index', 'create', 'store']);

// Viewers
Route::resource('users', UserController::class);

// Light endpoint for health checks.
Route::get('/up', fn () => response('ok'))->name('up');
