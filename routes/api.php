<?php

use App\Http\Controllers\AdminVideoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/auth', [LoginController::class, 'handleLogin'])->name('user.login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/video/add', [VideoController::class, 'store'])->name('video.add');

    Route::get('/videos/list', [VideoController::class, 'index'])->name('video.list');

    /*Admin routes*/
    Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
        Route::get('/video/list/unpublished', [AdminVideoController::class, 'unPublished'])
            ->name('admin.video.list');

        Route::post('/video/publish', [AdminVideoController::class, 'publish'])
            ->name('admin.video.publish');
    });
});
