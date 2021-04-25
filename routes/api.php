<?php

use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/video/add', [VideoController::class, 'store'])->name('video.add');
Route::get('/videos/list', [VideoController::class, 'index'])->name('video.list');
