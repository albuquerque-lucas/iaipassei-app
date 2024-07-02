<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminExaminationController;
use App\Http\Controllers\AdminNoticeController;
use App\Http\Controllers\AdminStudyAreaController;
use App\Http\Controllers\AdminSubjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::resource('admin/examinations', AdminExaminationController::class)->names([
    'index' => 'admin.examinations.index',
    'create' => 'admin.examinations.create',
    'store' => 'admin.examinations.store',
    'show' => 'admin.examinations.show',
    'edit' => 'admin.examinations.edit',
    'update' => 'admin.examinations.update',
    'destroy' => 'admin.examinations.destroy',
]);

Route::resource('admin/notices', AdminNoticeController::class)->names([
    'index' => 'admin.notices.index',
    'create' => 'admin.notices.create',
    'store' => 'admin.notices.store',
    'show' => 'admin.notices.show',
    'edit' => 'admin.notices.edit',
    'update' => 'admin.notices.update',
    'destroy' => 'admin.notices.destroy',
]);

Route::delete('admin/study_areas/bulk_delete', [AdminStudyAreaController::class, 'bulkDelete'])->name('admin.study_areas.bulkDelete');
Route::resource('admin/study_areas', AdminStudyAreaController::class)->names([
    'index' => 'admin.study_areas.index',
    'create' => 'admin.study_areas.create',
    'store' => 'admin.study_areas.store',
    'show' => 'admin.study_areas.show',
    'edit' => 'admin.study_areas.edit',
    'update' => 'admin.study_areas.update',
    'destroy' => 'admin.study_areas.destroy',
]);

Route::delete('admin/subjects/bulk_delete', [AdminSubjectController::class, 'bulkDelete'])->name('admin.subjects.bulkDelete');
Route::resource('admin/subjects', AdminSubjectController::class)->names([
    'index' => 'admin.subjects.index',
    'create' => 'admin.subjects.create',
    'store' => 'admin.subjects.store',
    'show' => 'admin.subjects.show',
    'edit' => 'admin.subjects.edit',
    'update' => 'admin.subjects.update',
    'destroy' => 'admin.subjects.destroy',
]);
