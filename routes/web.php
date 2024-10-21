<?php
use App\Http\Controllers\DatabaseTestController;

Route::get('/test-db', [DatabaseTestController::class, 'index']);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
