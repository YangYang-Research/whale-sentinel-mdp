<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
        return view('welcome');
    });

Route::group(['prefix' => 'whale-sentinel'], function() {
    Route::get('/login', [LoginController::class, 'getLogin'])->name('login');

    Route::post('/login', [LoginController::class, 'postLogin'])->name('submiter.login');

    Route::group(['middleware' => ['auth.sentinel']],function () {

        Route::get('/dashboard', function () {return redirect()->route('dashboard.index');})->name('root');

        Route::resource('dashboard', DashboardController::class);

        Route::group(['prefix' => 'management'], function() {

            Route::resource('instance', InstanceController::class);

            Route::resource('agent', AgentController::class);

            Route::resource('service', ServiceController::class);
        });
        
        Route::post('/logout', [LoginController::class, 'postLogout'])->name('submiter.logout');
    });
});



