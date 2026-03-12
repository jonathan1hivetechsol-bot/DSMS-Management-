<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolClassController;

// ============================================================================
// DSMS: DEFAULT ROUTES (Redirect to Portal-Based Authentication)
// ============================================================================
// When user opens the app, they see the PORTAL SELECTION page first
// NOT the login page - follows DSMS rule #1
// ============================================================================

// Homepage - redirect to portal selection (unauthenticated) or dashboard (authenticated)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('second', ['dashboards', 'analytics']);
    }
    return redirect()->route('portal.selector.view');
})->name('home');

Route::resource('students', StudentController::class)->middleware('auth');
Route::resource('teachers', TeacherController::class)->middleware('auth');
Route::resource('classes', SchoolClassController::class)->middleware('auth');

// attendance
Route::get('attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report')->middleware('auth');
Route::resource('attendance', App\Http\Controllers\AttendanceController::class)->middleware('auth');
    Route::post('attendance/bulk', [App\Http\Controllers\AttendanceController::class, 'bulk'])->middleware('auth')->name('attendance.bulk');

// grades and announcements management
Route::resource('grades', App\Http\Controllers\GradeController::class)->middleware('auth');
Route::resource('announcements', App\Http\Controllers\AnnouncementController::class)->middleware('auth');

// report cards
Route::get('students/{student}/report-card', [App\Http\Controllers\ReportCardController::class, 'show'])->middleware('auth')->name('students.report');
Route::get('students/{student}/report-card/pdf', [App\Http\Controllers\ReportCardController::class, 'pdf'])->middleware('auth')->name('students.report.pdf');

// invoices
Route::resource('invoices', App\Http\Controllers\InvoiceController::class)->middleware('auth');
Route::get('invoices/{invoice}/mark-paid', [App\Http\Controllers\InvoiceController::class, 'markPaid'])->middleware('auth')->name('invoices.markPaid');
Route::get('invoices/{invoice}/pdf', [App\Http\Controllers\InvoiceController::class, 'pdf'])->middleware('auth')->name('invoices.pdf');

// library
Route::resource('books', App\Http\Controllers\BookController::class)->middleware('auth');
Route::resource('loans', App\Http\Controllers\LoanController::class)->middleware('auth')->except(['edit','update','show']);
Route::post('loans/{loan}/return', [App\Http\Controllers\LoanController::class, 'return'])->middleware('auth')->name('loans.return');

    // messaging
    Route::resource('messages', App\Http\Controllers\MessageController::class)->middleware('auth');
    Route::get('messages/{message}/mark-read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->middleware('auth')->name('messages.markAsRead');
    Route::get('messages/inbox', [App\Http\Controllers\MessageController::class, 'inbox'])->middleware('auth')->name('messages.inbox');

    // subject and exams
    Route::resource('subjects', App\Http\Controllers\SubjectController::class)->middleware('auth');
    Route::resource('exam-schedules', App\Http\Controllers\ExamScheduleController::class)->middleware('auth');

    // teacher attendance
    Route::get('teacher-attendance/report', [App\Http\Controllers\TeacherAttendanceController::class, 'report'])->name('teacher-attendance.report')->middleware('auth');
    Route::resource('teacher-attendance', App\Http\Controllers\TeacherAttendanceController::class)->middleware('auth');
    Route::post('teacher-attendance/bulk', [App\Http\Controllers\TeacherAttendanceController::class, 'bulk'])->middleware('auth')->name('teacher-attendance.bulk');

    // payroll
    Route::resource('payroll', App\Http\Controllers\PayrollController::class)->middleware('auth');
    Route::get('payroll/{payroll}/approve', [App\Http\Controllers\PayrollController::class, 'approve'])->middleware('auth')->name('payroll.approve');
    Route::get('payroll/{payroll}/mark-paid', [App\Http\Controllers\PayrollController::class, 'markPaid'])->middleware('auth')->name('payroll.markPaid');
    Route::get('payroll/generate', [App\Http\Controllers\PayrollController::class, 'generate'])->middleware('auth')->name('payroll.generate');

    // student leaves
    Route::get('student-leaves/dashboard', [App\Http\Controllers\StudentLeaveController::class, 'dashboard'])->middleware('auth')->name('student-leaves.dashboard');
    Route::resource('student-leaves', App\Http\Controllers\StudentLeaveController::class)->middleware('auth');
    Route::post('student-leaves/{studentLeave}/approve', [App\Http\Controllers\StudentLeaveController::class, 'approve'])->middleware('auth')->name('student-leaves.approve');
    Route::post('student-leaves/{studentLeave}/reject', [App\Http\Controllers\StudentLeaveController::class, 'reject'])->middleware('auth')->name('student-leaves.reject');

    // WhatsApp Alerts
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [App\Http\Controllers\WhatsAppAlertController::class, 'index'])->name('index');
        Route::get('dashboard', [App\Http\Controllers\WhatsAppAlertController::class, 'dashboard'])->name('dashboard');
        
        // Templates
        Route::get('templates', [App\Http\Controllers\WhatsAppAlertController::class, 'templates'])->name('templates');
        Route::get('templates/create', [App\Http\Controllers\WhatsAppAlertController::class, 'createTemplate'])->name('template.create');
        Route::post('templates', [App\Http\Controllers\WhatsAppAlertController::class, 'storeTemplate'])->name('template.store');
        Route::get('templates/{template}/edit', [App\Http\Controllers\WhatsAppAlertController::class, 'editTemplate'])->name('template.edit');
        Route::put('templates/{template}', [App\Http\Controllers\WhatsAppAlertController::class, 'updateTemplate'])->name('template.update');
        Route::delete('templates/{template}', [App\Http\Controllers\WhatsAppAlertController::class, 'destroyTemplate'])->name('template.destroy');
        
        // Recipients
        Route::get('recipients', [App\Http\Controllers\WhatsAppAlertController::class, 'recipients'])->name('recipients');
        Route::get('recipients/create', [App\Http\Controllers\WhatsAppAlertController::class, 'createRecipient'])->name('recipient.create');
        Route::post('recipients', [App\Http\Controllers\WhatsAppAlertController::class, 'storeRecipient'])->name('recipient.store');
        
        // Send Alerts
        Route::get('send', [App\Http\Controllers\WhatsAppAlertController::class, 'sendAlert'])->name('send');
        Route::post('send', [App\Http\Controllers\WhatsAppAlertController::class, 'sendAlertAction'])->name('send.action');
        
        // Alert Details & Actions
        Route::get('{alert}/show', [App\Http\Controllers\WhatsAppAlertController::class, 'show'])->name('show');
        Route::post('{alert}/retry', [App\Http\Controllers\WhatsAppAlertController::class, 'retry'])->name('retry');

        // ====== AUTOMATION ROUTES ======
        Route::prefix('automation')->name('automation.')->group(function () {
            // Dashboard
            Route::get('/', [App\Http\Controllers\WhatsAppAutomationController::class, 'dashboard'])->name('dashboard');
            
            // Groups Management
            Route::get('groups', [App\Http\Controllers\WhatsAppAutomationController::class, 'groups'])->name('groups');
            Route::get('groups/create', [App\Http\Controllers\WhatsAppAutomationController::class, 'createGroup'])->name('group.create');
            Route::post('groups', [App\Http\Controllers\WhatsAppAutomationController::class, 'storeGroup'])->name('group.store');
            Route::delete('groups/{group}', [App\Http\Controllers\WhatsAppAutomationController::class, 'deleteGroup'])->name('group.delete');
            
            // Broadcast
            Route::get('broadcast', [App\Http\Controllers\WhatsAppAutomationController::class, 'broadcast'])->name('broadcast');
            Route::post('broadcast', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendBroadcast'])->name('broadcast.send');
            
            // Quick Send (AJAX)
            Route::post('quick-send', [App\Http\Controllers\WhatsAppAutomationController::class, 'quickSend'])->name('quick-send');
            
            // Bulk Send (AJAX)
            Route::post('send-to-students', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendToStudents'])->name('send-students');
            Route::post('send-to-teachers', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendToTeachers'])->name('send-teachers');
            
            // Automated Alerts
            Route::post('attendance-alert', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendAttendanceAlert'])->name('attendance-alert');
            Route::post('grade-notification', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendGradeNotification'])->name('grade-notification');
            Route::post('fee-reminder', [App\Http\Controllers\WhatsAppAutomationController::class, 'sendFeeReminder'])->name('fee-reminder');
        });
    });

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('edit', [App\Http\Controllers\ProfileController::class, 'editProfile'])->name('edit');
        Route::put('update', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('update');
        Route::post('upload-avatar', [App\Http\Controllers\ProfileController::class, 'uploadAvatar'])->name('upload-avatar');
        Route::delete('delete-avatar', [App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('delete-avatar');
        
        // Student profiles
        Route::get('student/{student}', [App\Http\Controllers\ProfileController::class, 'showStudent'])->name('student.show');
        Route::get('student/{student}/edit', [App\Http\Controllers\ProfileController::class, 'editStudent'])->name('student.edit');
        Route::put('student/{student}', [App\Http\Controllers\ProfileController::class, 'updateStudent'])->name('student.update');
        
        // Teacher profiles
        Route::get('teacher/{teacher}', [App\Http\Controllers\ProfileController::class, 'showTeacher'])->name('teacher.show');
        Route::get('teacher/{teacher}/edit', [App\Http\Controllers\ProfileController::class, 'editTeacher'])->name('teacher.edit');
        Route::put('teacher/{teacher}', [App\Http\Controllers\ProfileController::class, 'updateTeacher'])->name('teacher.update');
    });

Route::get('/debug/user', function () {
    $user = auth()->user();
    
    if (!$user) {
        return response()->json(['message' => 'Not logged in']);
    }
    
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'can_manage_teachers' => $user->role === 'admin',
    ]);
})->middleware('auth');

Route::get('/dashboard', function () {
    return redirect()->route('second', ['dashboards', 'analytics']);
})->middleware('auth');

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});

// ============================================================================
// WhatsApp Evolution API Webhook Routes (NO AUTH REQUIRED)
// ============================================================================
// These endpoints are called by Evolution API with webhooks
// MUST NOT require authentication
Route::post('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'handle'])->name('whatsapp.webhook.handle');
Route::get('/webhook/whatsapp', [App\Http\Controllers\WhatsAppWebhookController::class, 'verify'])->name('whatsapp.webhook.verify');
Route::get('/webhook/whatsapp/test', [App\Http\Controllers\WhatsAppWebhookController::class, 'test'])->name('whatsapp.webhook.test');


