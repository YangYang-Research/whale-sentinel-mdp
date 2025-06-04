<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\Authenticate;

Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/login', [LoginController::class, 'getLogin'])->name('login');

    Route::post('/login', [LoginController::class, 'postLogin'])->name('submiter.login');


Route::group(['middleware' => ['auth.sentinel'],'prefix' => 'whale-sentinel'],function () {

    Route::get('/', function () {return redirect()->route('dashboard.index');})->name('root');

    Route::resource('dashboard', DashboardController::class);

    Route::post('/logout', [LoginController::class, 'postLogout'])->name('submiter.logout');
});