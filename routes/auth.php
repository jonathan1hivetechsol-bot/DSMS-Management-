<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PortalSelectorController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\TeacherLoginController;
use App\Http\Controllers\Auth\StudentLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::get('/instructions', function () {
    return view('auth.instructions');
})
    ->name('auth.instructions');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ============================================================================
// DSMS: Role-Specific Portal Login Routes (ENTERPRISE SECURITY)
// ============================================================================
// Pattern: /portal/:role/login
// - Verified role-based authentication
// - Middleware protection before login display
// - Audit logging for security compliance
// ============================================================================

// Portal Selection (Pre-Login) - Choose which role you're logging in as
Route::get('/portal-login', function () {
    return view('auth.portal-selector-login');
})
    ->middleware('guest')
    ->name('portal.selector.view');

// Admin Portal Login
Route::get('/portal/admin/login', [AdminLoginController::class, 'show'])
    ->middleware('guest')
    ->name('portal.admin.login');

Route::post('/portal/admin/login', [AdminLoginController::class, 'store'])
    ->middleware('guest')
    ->name('portal.admin.login.store');

// Teacher Portal Login
Route::get('/portal/teacher/login', [TeacherLoginController::class, 'show'])
    ->middleware('guest')
    ->name('portal.teacher.login');

Route::post('/portal/teacher/login', [TeacherLoginController::class, 'store'])
    ->middleware('guest')
    ->name('portal.teacher.login.store');

// Student Portal Login
Route::get('/portal/student/login', [StudentLoginController::class, 'show'])
    ->middleware('guest')
    ->name('portal.student.login');

Route::post('/portal/student/login', [StudentLoginController::class, 'store'])
    ->middleware('guest')
    ->name('portal.student.login.store');


