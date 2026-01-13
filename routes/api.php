<?php

use App\Http\Controllers\Api\AuthController;
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
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class,'update']);


        Route::post('/logout', [AuthController::class, 'logout']);

    // Route::get('/student/dashboard', function (Request $request) {
    //     return response()->json(['message' => 'Welcome to the student dashboard']);
    // });
});
