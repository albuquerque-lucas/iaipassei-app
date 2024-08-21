<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminExaminationController;
use App\Http\Controllers\AdminNoticeController;
use App\Http\Controllers\AdminStudyAreaController;
use App\Http\Controllers\AdminSubjectController;
use App\Http\Controllers\AdminAccountPlanController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\AdminExamQuestionController;
use App\Http\Controllers\AdminQuestionAlternativeController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\OpenAIAPIController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\CheckAccountLevel;
use App\Http\Middleware\CheckAccessLevel;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PublicPagesController;
use App\Http\Controllers\PublicExamController;

// Rota para página inicial
Route::get('/', [PublicPagesController::class, 'home'])->name('welcome');

// Rotas de autenticação pública
Route::get('register', [AuthController::class, 'showPublicRegisterForm'])->name('public.register.index');
Route::post('register', [AuthController::class, 'publicRegister'])->name('public.register.store');
Route::get('login', [AuthController::class, 'showPublicLoginForm'])->name('public.login.index');
Route::post('login', [AuthController::class, 'publicLogin'])->name('public.login.store');
Route::post('logout', [AuthController::class, 'publicLogout'])->name('public.logout');


Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


Route::get('/email/verify', [EmailVerificationController::class, 'show'])
->middleware('auth')
->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
->middleware(['auth', 'signed'])
->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
->middleware(['auth', 'throttle:6,1'])
->name('verification.send');

// Rotas de autenticação de administrador
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login.index');
Route::post('admin/login', [AuthController::class, 'login'])->middleware(CheckAccessLevel::class)->name('admin.login.store');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


// Rotas públicas para usuários
Route::middleware(['auth'])->group(function () {
    Route::get('perfil/{slug}', [UserProfileController::class, 'publicProfile'])->name('public.profile.index');
    Route::get('concursos', [PublicPagesController::class, 'examinations'])->name('public.examinations.index');
    Route::get('concurso/{slug}', [PublicPagesController::class, 'examination'])->name('public.examinations.show');
    Route::post('concurso/{id}/subscribe', [PublicPagesController::class, 'subscribe'])->name('examinations.subscribe');
    Route::delete('concurso/{id}/unsubscribe', [PublicPagesController::class, 'unsubscribe'])->name('examinations.unsubscribe');

    Route::post('provas/{exam:slug}/submit', [PublicExamController::class, 'submit'])->name('public.exams.submit');
    Route::get('provas/{exam:slug}/resultados', [PublicExamController::class, 'results'])->name('public.exams.results');
    Route::resource('provas', PublicExamController::class)
        ->only(['show'])
        ->parameters([
            'provas' => 'exam:slug'
        ])
        ->names([
            'index' => 'public.exams.index',
            'show' => 'public.exams.show',
        ]);

    Route::resource('public/users', PublicUserController::class)
        ->only(['update'])
        ->parameters([
            'users' => 'user:slug'
        ])
        ->names([
            'update' => 'public.users.update',
        ]);

    Route::get('confirm-email-change/{id}/{email}', [AdminUserController::class, 'confirmEmailChange'])->name('verification.verify.new.email');
});







// Rotas protegidas para administradores
Route::middleware(['auth', CheckAccountLevel::class])->group(function () {

    Route::get('admin/profile/{slug}', [AuthController::class, 'profile'])->name('admin.profile.index');

    Route::delete('admin/users/bulk_delete', [AdminUserController::class, 'bulkDelete'])->name('admin.users.bulkDelete');
    Route::resource('admin/users', AdminUserController::class)->parameters([
        'users' => 'user:slug'
    ])->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::post('admin/examinations/import', [OpenAIAPIController::class, 'import'])->name('admin.examinations.import');

    Route::delete('admin/examinations/bulk_delete', [AdminExaminationController::class, 'bulkDelete'])->name('admin.examinations.bulkDelete');
    Route::resource('admin/examinations', AdminExaminationController::class)->parameters([
        'examinations' => 'examination:slug'
    ])
    ->names([
        'index' => 'admin.examinations.index',
        'create' => 'admin.examinations.create',
        'store' => 'admin.examinations.store',
        'show' => 'admin.examinations.show',
        'edit' => 'admin.examinations.edit',
        'update' => 'admin.examinations.update',
        'destroy' => 'admin.examinations.destroy',
    ]);

    Route::get('notices/download/{id}', [AdminNoticeController::class, 'download'])->name('notices.download');
    Route::resource('admin/notices', AdminNoticeController::class)->names([
        'index' => 'admin.notices.index',
        'create' => 'admin.notices.create',
        'store' => 'admin.notices.store',
        'show' => 'admin.notices.show',
        'edit' => 'admin.notices.edit',
        'update' => 'admin.notices.update',
        'destroy' => 'admin.notices.destroy',
    ]);

    Route::delete('admin/study_areas/{studyArea}/remove_subject/{subject}', [AdminStudyAreaController::class, 'removeSubject'])->name('admin.study_areas.remove_subject');
    Route::delete('admin/study_areas/bulk_delete', [AdminStudyAreaController::class, 'bulkDelete'])->name('admin.study_areas.bulkDelete');
    Route::resource('admin/study_areas', AdminStudyAreaController::class)->parameters([
        'study_areas' => 'studyArea:slug'
    ])->names([
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

    Route::delete('admin/account_plans/bulk_delete', [AdminAccountPlanController::class, 'bulkDelete'])->name('admin.account_plans.bulkDelete');
    Route::resource('admin/account_plans', AdminAccountPlanController::class)->names([
        'index' => 'admin.account_plans.index',
        'create' => 'admin.account_plans.create',
        'store' => 'admin.account_plans.store',
        'show' => 'admin.account_plans.show',
        'edit' => 'admin.account_plans.edit',
        'update' => 'admin.account_plans.update',
        'destroy' => 'admin.account_plans.destroy',
    ]);

    Route::delete('admin/exams/bulk_delete', [AdminExamController::class, 'bulkDelete'])->name('admin.exams.bulkDelete');
    Route::resource('admin/exams', AdminExamController::class)
    ->parameters([
        'exams' => 'exam:slug'
    ])
    ->names([
        'index' => 'admin.exams.index',
        'create' => 'admin.exams.create',
        'store' => 'admin.exams.store',
        'show' => 'admin.exams.show',
        'edit' => 'admin.exams.edit',
        'update' => 'admin.exams.update',
        'destroy' => 'admin.exams.destroy',
    ]);

    Route::delete('admin/exam_questions/delete_last', [AdminExamQuestionController::class, 'deleteLastQuestion'])->name('admin.exam_questions.delete_last');
    Route::delete('admin/exam_questions/bulk_delete', [AdminExamQuestionController::class, 'bulkDelete'])->name('admin.exam_questions.bulkDelete');
    Route::resource('admin/exam_questions', AdminExamQuestionController::class)->names([
        'index' => 'admin.exam_questions.index',
        'create' => 'admin.exam_questions.create',
        'store' => 'admin.exam_questions.store',
        'show' => 'admin.exam_questions.show',
        'edit' => 'admin.exam_questions.edit',
        'update' => 'admin.exam_questions.update',
        'destroy' => 'admin.exam_questions.destroy',
    ]);

    Route::delete('admin/question_alternatives/bulk_delete', [AdminQuestionAlternativeController::class, 'bulkDelete'])->name('admin.question_alternatives.bulkDelete');
    Route::resource('admin/question_alternatives', AdminQuestionAlternativeController::class)->names([
        'index' => 'admin.question_alternatives.index',
        'create' => 'admin.question_alternatives.create',
        'store' => 'admin.question_alternatives.store',
        'show' => 'admin.question_alternatives.show',
        'edit' => 'admin.question_alternatives.edit',
        'update' => 'admin.question_alternatives.update',
        'destroy' => 'admin.question_alternatives.destroy',
    ]);

});
