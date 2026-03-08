<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    // custom admin routes
    Route::get('dashboard', 'DashboardController@dashboard')->name('backpack.dashboard');
    Route::get('dashboard/stats', 'DashboardController@stats')->name('backpack.dashboard.stats');
    Route::get('/', 'DashboardController@redirect')->name('backpack');

    Route::crud('user', 'UserCrudController');
    Route::crud('student', 'StudentCrudController');
    Route::crud('teacher', 'TeacherCrudController');
    Route::crud('classroom', 'ClassroomCrudController');
    Route::crud('classroom-teacher', 'ClassroomTeacherCrudController');
    Route::crud('attendance', 'AttendanceCrudController');
    Route::crud('grade', 'GradeCrudController');
    Route::crud('report', 'ReportCrudController');
    Route::crud('fee-payment', 'FeePaymentCrudController');
    Route::crud('task', 'TaskCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
