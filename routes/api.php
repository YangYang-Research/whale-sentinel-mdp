<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::group(['prefix' => 'v1/ws/controllers/processor/profile'], function() {
    Route::post('/', [ProfileController::class, 'getProfile'])
        ->name('submiter.profile.get');
    
    Route::post('/synchronize', [ProfileController::class, 'syncProfile'])
        ->name('submiter.profile.sync');
});
