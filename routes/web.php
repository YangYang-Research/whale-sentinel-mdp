<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\MainDashboardController;
use App\Http\Controllers\Dashboard\InstanceController;
use App\Http\Controllers\Dashboard\ApplicationController;
use App\Http\Controllers\Dashboard\AgentController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ProfileController;

Route::get('/', function () {
        return view('welcome');
    });

Route::group(['prefix' => 'whale-sentinel'], function() {
    Route::get('/login', [LoginController::class, 'getLogin'])->name('login');

    Route::post('/login', [LoginController::class, 'postLogin'])->name('submiter.login');

    Route::group(['middleware' => ['auth.sentinel']],function () {

        Route::get('/dashboard', function () {return redirect()->route('dashboard.index');})->name('root');

        Route::resource('dashboard', MainDashboardController::class);

        Route::group(['prefix' => 'management'], function() {

            Route::resource('instance', InstanceController::class);

            Route::resource('application', ApplicationController::class);

            Route::resource('agent', AgentController::class);
            
            Route::post('agent/export/env', [AgentController::class, 'exportEnv'])->name('agent.export.env');
            
            Route::resource('service', ServiceController::class);

            Route::resource('profile', ProfileController::class);
        });
        
        Route::post('/logout', [LoginController::class, 'postLogout'])->name('submiter.logout');
    });
});



