<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppAlert;
use App\Models\WhatsAppGroup;
use App\Models\WhatsAppTemplate;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class WhatsAppAutomationController extends Controller
{
    private WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Show automation dashboard
     */
    public function dashboard()
    {
        $stats = [
            'today_sent' => WhatsAppAlert::whereDate('created_at', today())->count(),
            'pending' => WhatsAppAlert::where('status', 'pending')->count(),
            'delivered' => WhatsAppAlert::where('status', 'delivered')->count(),
            'failed' => WhatsAppAlert::where('status', 'failed')->count(),
            'total_groups' => $this->getGroupCount(),
        ];

        $recentAlerts = WhatsAppAlert::latest()->take(10)->get();
        $templates = WhatsAppTemplate::all();
        $groups = $this->getAllGroups();

        return view('whatsapp.automation.dashboard', compact('stats', 'recentAlerts', 'templates', 'groups'));
    }

    /**
     * Safely get group count
     */
    private function getGroupCount()
    {
        try {
            return \Schema::hasTable('whatsapp_groups') ? WhatsAppGroup::count() : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely get all groups
     */
    private function getAllGroups()
    {
        try {
            return \Schema::hasTable('whatsapp_groups') ? WhatsAppGroup::all() : collect();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Show groups management page
     */
    public function groups()
    {
        if (!Schema::hasTable('whatsapp_groups')) {
            return view('whatsapp.automation.groups', ['groups' => collect()]);
        }
        
        $groups = WhatsAppGroup::all();
        
        return view('whatsapp.automation.groups', compact('groups'));
    }

    /**
     * Create new group
     */
    public function createGroup()
    {
        $types = [
            'students' => 'All Students',
            'teachers' => 'All Teachers',
            'parents' => 'Parents',
            'guardians' => 'Guardians',
            'custom' => 'Custom List',
        ];

        $classes = \App\Models\SchoolClass::all();

        return view('whatsapp.automation.group-create', compact('types', 'classes'));
    }

    /**
     * Store new group
     */
    public function storeGroup(Request $request)
    {
        if (!Schema::hasTable('whatsapp_groups')) {
            return back()->with('error', 'Database table not ready. Please run migrations: php artisan migrate');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:whatsapp_groups',
            'description' => 'nullable|string',
            'type' => 'required|in:students,teachers,parents,guardians,custom',
            'class_id' => 'nullable|exists:school_classes,id',
            'phone_numbers' => 'nullable|string',
        ]);

        $filters = [];
        if ($request->class_id) {
            $filters['class_id'] = $request->class_id;
        }

        if ($request->type === 'custom' && $request->phone_numbers) {
            $phones = collect(explode("\n", $request->phone_numbers))
                ->map(fn($phone) => trim($phone))
                ->filter()
                ->toArray();
            $filters['phone_numbers'] = $phones;
        }

        $group = WhatsAppGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'filters' => $filters,
            'is_active' => true,
        ]);

        // Count members
        $members = $group->getMembers();
        $group->update(['member_count' => $members->count()]);

        return redirect()->route('whatsapp.groups')
            ->with('success', "Group '{$group->name}' created with {$group->member_count} members");
    }

    /**
     * Show broadcast form
     */
    public function broadcast()
    {
        if (!Schema::hasTable('whatsapp_groups')) {
            return view('whatsapp.automation.broadcast', [
                'groups' => collect(),
                'templates' => WhatsAppTemplate::all()
            ]);
        }

        $groups = WhatsAppGroup::where('is_active', true)->get();
        $templates = WhatsAppTemplate::all();

        return view('whatsapp.automation.broadcast', compact('groups', 'templates'));
    }

    /**
     * Send broadcast message
     */
    public function sendBroadcast(Request $request)
    {
        if (!Schema::hasTable('whatsapp_groups')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not ready. Please run migrations first.',
            ], 503);
        }

        $validated = $request->validate([
            'group_id' => 'required|exists:whatsapp_groups,id',
            'template_id' => 'nullable|exists:whatsapp_templates,id',
            'message' => 'required_if:template_id,null|string',
            'confirm' => 'required|accepted',
        ]);

        $group = WhatsAppGroup::findOrFail($validated['group_id']);
        $template = $validated['template_id'] ? WhatsAppTemplate::find($validated['template_id']) : null;
        $message = $validated['message'] ?? $template?->template;

        // Get members to verify count
        $members = $group->getMembers();
        $memberCount = $members->count();

        if ($memberCount === 0) {
            return back()->with('error', 'This group has no members');
        }

        // Broadcast to all members
        $sentCount = $group->broadcastMessage($message, $template);

        Log::channel('whatsapp')->info('Broadcast sent', [
            'group_id' => $group->id,
            'group_name' => $group->name,
            'members' => $sentCount,
            'template_id' => $template?->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('whatsapp.automation.dashboard')
            ->with('success', "{$sentCount} messages sent successfully!");
    }

    /**
     * Quick send to custom number
     */
    public function quickSend(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        $templateId = $request->get('template_id');
        $template = $templateId ? WhatsAppTemplate::find($templateId) : null;

        try {
            $alert = $this->whatsappService->sendMessage(
                $validated['phone_number'],
                $validated['message'],
                $template
            );

            if ($alert && $alert->status === 'sent') {
                return response()->json([
                    'success' => true,
                    'message' => 'Message sent successfully!',
                    'alert_id' => $alert->id,
                    'status' => $alert->status,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error sending message',
                'error' => $alert->error_message ?? 'Unknown error',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send to students with filters
     */
    public function sendToStudents(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'message' => 'required|string',
            'template_id' => 'nullable|exists:whatsapp_templates,id',
            'send_to' => 'required|in:student,guardian',
        ]);

        $query = Student::query();

        if ($validated['class_id']) {
            $query->where('school_class_id', $validated['class_id']);
        }

        $students = $query->get();
        $template = $validated['template_id'] ? WhatsAppTemplate::find($validated['template_id']) : null;
        $message = $validated['message'] ?? $template?->template;

        $sentCount = 0;
        foreach ($students as $student) {
            $phone = $validated['send_to'] === 'student' 
                ? $student->user?->phone 
                : $student->guardian_phone;

            if ($phone) {
                $alert = $this->whatsappService->sendMessage($phone, $message, $template);
                if ($alert) $sentCount++;
            }
        }

        Log::channel('whatsapp')->info('Bulk send to students', [
            'count' => $sentCount,
            'class_id' => $validated['class_id'],
            'send_to' => $validated['send_to'],
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$sentCount} درخواستیں بھیجی گئیں",
        ]);
    }

    /**
     * Send to teachers
     */
    public function sendToTeachers(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'template_id' => 'nullable|exists:whatsapp_templates,id',
        ]);

        $teachers = Teacher::all();
        $template = $validated['template_id'] ? WhatsAppTemplate::find($validated['template_id']) : null;
        $message = $validated['message'] ?? $template?->template;

        $sentCount = 0;
        foreach ($teachers as $teacher) {
            $phone = $teacher->phone ?? $teacher->user?->phone;
            
            if ($phone) {
                $alert = $this->whatsappService->sendMessage($phone, $message, $template);
                if ($alert) $sentCount++;
            }
        }

        Log::channel('whatsapp')->info('Bulk send to teachers', [
            'count' => $sentCount,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$sentCount} درخواستیں بھیجی گئیں",
        ]);
    }

    /**
     * Send attendance notification
     */
    public function sendAttendanceAlert(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'date' => 'required|date',
        ]);

        $students = Student::where('school_class_id', $validated['class_id'])->get();
        
        $message = "حاضری شماری: {$validated['date']} کو آپ کی حاضری شماری کا ریکارڈ درج ہو گیا۔";
        
        $sentCount = 0;
        foreach ($students as $student) {
            if ($student->guardian_phone) {
                $alert = $this->whatsappService->sendMessage(
                    $student->guardian_phone,
                    $message
                );
                if ($alert) $sentCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$sentCount} والدین کو نوٹیفکیشن بھیجی گئی",
        ]);
    }

    /**
     * Send grade notification
     */
    public function sendGradeNotification(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exam_schedules,id',
        ]);

        $grades = \App\Models\Grade::where('exam_id', $validated['exam_id'])->get();
        
        $sentCount = 0;
        foreach ($grades as $grade) {
            $student = $grade->student;
            $phone = $student->guardian_phone;

            if ($phone) {
                $message = "نتیجہ: {$student->name} نے {$grade->marks}/{$grade->total} نمبرات حاصل کیے ہیں۔";
                
                $alert = $this->whatsappService->sendMessage($phone, $message);
                if ($alert) $sentCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$sentCount} والدین کو نتائج بھیجے گئے",
        ]);
    }

    /**
     * Send fee reminder
     */
    public function sendFeeReminder(Request $request)
    {
        $validated = $request->validate([
            'days_overdue' => 'required|integer|min:0',
        ]);

        $invoices = \App\Models\Invoice::where('status', 'pending')
            ->whereDate('due_date', '<=', now()->subDays($validated['days_overdue']))
            ->get();

        $sentCount = 0;
        foreach ($invoices as $invoice) {
            $student = $invoice->student;
            $phone = $student->guardian_phone;

            if ($phone) {
                $message = "فیس کی یادہانی: Invoice #{$invoice->id} کی رقم Rs.{$invoice->amount} معطل ہے۔\n\nبھرنے کی مہلت: {$invoice->due_date}";
                
                $alert = $this->whatsappService->sendMessage($phone, $message);
                if ($alert) $sentCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$sentCount} والدین کو فیس کی یادہانی بھیجی گئی",
        ]);
    }

    /**
     * Delete group
     */
    public function deleteGroup(WhatsAppGroup $group)
    {
        if (!Schema::hasTable('whatsapp_groups')) {
            return back()->with('error', 'Database table not ready');
        }

        try {
            $groupName = $group->name;
            $group->delete();
            return back()->with('success', "Group '{$groupName}' deleted successfully");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting group: ' . $e->getMessage());
        }
    }
}
