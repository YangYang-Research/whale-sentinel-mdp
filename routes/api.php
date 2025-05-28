<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::group(['prefix' => 'v1/ws/controllers/processor/'], function() {
    Route::post('profile', [ProfileController::class, 'getProfile'])
        ->name('submiter.profile.get');
});
