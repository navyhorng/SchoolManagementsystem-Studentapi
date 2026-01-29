<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Student\AttendanceController;
use App\Http\Controllers\Api\Student\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/student/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:student,api'])
    ->prefix('student')
    ->group(function () {

    //Prifile
        Route::get('/profile/show', [ProfileController::class, 'show']);
        Route::put('/profile/update', [ProfileController::class,'update']);

    //Attendance
        Route::get('/attendance', [AttendanceController::class, 'index']);
        Route::get('/attendance/summary', [AttendanceController::class, 'summary']);
        // Route::get('/attendance/report/pdf', [AttendanceController::class, 'downloadPdf']);

        Route::post('/logout', [AuthController::class, 'logout']);

    // Route::get('/student/dashboard', function (Request $request) {
    //     return response()->json(['message' => 'Welcome to the student dashboard']);
    // });
});
