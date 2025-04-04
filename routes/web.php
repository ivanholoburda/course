<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginIndex')->name('login');
    Route::get('/register', 'registerIndex')->name('register');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')
        ->name('logout')
        ->middleware('auth');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
})
    ->middleware(['auth']);

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile');
    Route::put('/profile', 'update')->name('profile');
    Route::post('/profile/upload-avatar', 'uploadAvatar')->name('profile.upload-avatar');
});

Route::controller(ProductController::class)->group(function () {
    Route::post('/products', 'store')->name('products');
});

Route::get('/', function () {
    return Inertia::render('Homepage/Homepage', []);
})
    ->name('index');
