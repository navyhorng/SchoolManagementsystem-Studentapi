<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Student\AttendanceController;
use App\Http\Controllers\Api\Student\Auth\PasswordController;
use App\Http\Controllers\Api\Student\FeePaymentController;
use App\Http\Controllers\Api\Student\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('student/auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [PasswordController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum', 'role:student,api'])
    ->prefix('student')
    ->group(function () {

    //Password
        Route::post('/change-password', [PasswordController::class, 'changePassword']);

    //Prifile
        Route::get('/profile/show', [ProfileController::class, 'show']);
        Route::put('/profile/update', [ProfileController::class,'update']);

    //Attendance
        Route::get('/attendance', [AttendanceController::class, 'index']);
        Route::get('/attendance/summary', [AttendanceController::class, 'summary']);
        // Route::get('/attendance/report/pdf', [AttendanceController::class, 'downloadPdf']);

        Route::get('fee-payments', [FeePaymentController::class, 'index']);
        Route::get('fee-payments/{id}', [FeePaymentController::class, 'show']);

        Route::post('/logout', [AuthController::class, 'logout']);
});
