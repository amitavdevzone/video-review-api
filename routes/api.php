<?php

use App\Http\Controllers\AdminVideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/register', UserRegistrationController::class)->name('user.register');
Route::post('/user/auth', [LoginController::class, 'handleLogin'])->name('user.login');
Route::get('/user/verify/{token:token}', TokenController::class)->name('user.verify');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user/logout', LogoutController::class)->name('user.logout');

    Route::post('/video/add', [VideoController::class, 'store'])->name('video.add');
    Route::get('/video/{video}', [VideoController::class, 'view'])->name('video.view');
    Route::get('/videos/list', [VideoController::class, 'index'])->name('video.list');

    Route::post('/video/comment', [CommentController::class, 'store'])->name('comment.save');

    Route::post('/like', [LikeController::class, 'store'])->name('like.entity');

    Route::get('latest-courses', [CourseController::class, 'index'])->name('latest-courses.list');
    Route::post('course', [CourseController::class, 'store'])->name('course.add');
    Route::post('course/activate', [CourseController::class, 'activate'])->name('course.activate');
    Route::get('my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');

    /*Admin routes*/
    Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
        Route::get('/video/list/unpublished', [AdminVideoController::class, 'unPublished'])
            ->name('admin.video.list');

        Route::post('/video/publish', [AdminVideoController::class, 'publish'])
            ->name('admin.video.publish');
    });
});
