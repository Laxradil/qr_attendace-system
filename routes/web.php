<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    // Only allow access if logged in as admin (for demo, allow all)
    // if (!Auth::check() || Auth::user()->role !== 'admin') {
    //     return redirect('/login');
    // }
    return view('admin');
})->name('admin');
