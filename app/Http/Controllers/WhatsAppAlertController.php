<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppAlert;
use App\Models\WhatsAppTemplate;
use App\Models\WhatsAppRecipient;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class WhatsAppAlertController extends Controller
{
    private WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display all WhatsApp alerts
     */
    public function index(Request $request): View
    {
        $query = WhatsAppAlert::with('template')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $alerts = $query->paginate(20);

        return view('whatsapp-alerts.index', [
            'alerts' => $alerts,
            'statuses' => ['pending', 'sent', 'delivered', 'failed'],
        ]);
    }

    /**
     * Show template management
     */
    public function templates(): View
    {
        $templates = WhatsAppTemplate::orderBy('created_at', 'desc')->paginate(15);

        return view('whatsapp-alerts.templates', [
            'templates' => $templates,
        ]);
    }

    /**
     * Show create template form
     */
    public function createTemplate(): View
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to create WhatsApp templates.');
        }
        return view('whatsapp-alerts.template-form', [
            'categories' => [
                'general' => 'General',
                'attendance' => 'Attendance',
                'payroll' => 'Payroll',
                'grades' => 'Grades',
                'fees' => 'Fee/Invoice',
                'announcement' => 'Announcement',
            ],
        ]);
    }

    /**
     * Store new template
     */
    public function storeTemplate(Request $request): RedirectResponse
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to create WhatsApp templates.');
        }
        $validated = $request->validate([
            'name' => 'required|string|unique:whats_app_templates',
            'category' => 'required|string',
            'template' => 'required|string',
            'variables' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Parse variables if provided
        if ($validated['variables'] ?? null) {
            $variables = array_map('trim', explode(',', $validated['variables']));
            $validated['variables'] = $variables;
        }

        WhatsAppTemplate::create($validated);

        return redirect()->route('whatsapp.templates')
            ->with('success', 'Template created successfully!');
    }

    /**
     * Edit template
     */
    public function editTemplate(WhatsAppTemplate $template): View
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to edit WhatsApp templates.');
        }
        return view('whatsapp-alerts.template-form', [
            'template' => $template,
            'categories' => [
                'general' => 'General',
                'attendance' => 'Attendance',
                'payroll' => 'Payroll',
                'grades' => 'Grades',
                'fees' => 'Fee/Invoice',
                'announcement' => 'Announcement',
            ],
        ]);
    }

    /**
     * Update template
     */
    public function updateTemplate(Request $request, WhatsAppTemplate $template): RedirectResponse
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to update WhatsApp templates.');
        }
        $validated = $request->validate([
            'name' => 'required|string|unique:whats_app_templates,name,' . $template->id,
            'category' => 'required|string',
            'template' => 'required|string',
            'variables' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validated['variables'] ?? null) {
            $variables = array_map('trim', explode(',', $validated['variables']));
            $validated['variables'] = $variables;
        }

        $template->update($validated);

        return redirect()->route('whatsapp.templates')
            ->with('success', 'Template updated successfully!');
    }

    /**
     * Delete template
     */
    public function destroyTemplate(WhatsAppTemplate $template): RedirectResponse
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete WhatsApp templates.');
        }
        $template->delete();

        return redirect()->route('whatsapp.templates')
            ->with('success', 'Template deleted successfully!');
    }

    /**
     * Show recipients management
     */
    public function recipients(): View
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to manage WhatsApp recipients.');
        }
        $recipients = WhatsAppRecipient::orderBy('created_at', 'desc')->paginate(20);

        return view('whatsapp-alerts.recipients', [
            'recipients' => $recipients,
            'types' => ['student', 'teacher', 'parent', 'admin'],
        ]);
    }

    /**
     * Show create recipient form
     */
    public function createRecipient(): View
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to add WhatsApp recipients.');
        }
        return view('whatsapp-alerts.recipient-form', [
            'types' => ['student', 'teacher', 'parent', 'admin'],
        ]);
    }

    /**
     * Store new recipient
     */
    public function storeRecipient(Request $request): RedirectResponse
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to add WhatsApp recipients.');
        }
        $validated = $request->validate([
            'phone_number' => 'required|string|unique:whats_app_recipients',
            'recipient_type' => 'required|in:student,teacher,parent,admin',
            'name' => 'required|string',
            'email' => 'nullable|email',
            'opt_in' => 'boolean',
        ]);

        WhatsAppRecipient::create($validated);

        return redirect()->route('whatsapp.recipients')
            ->with('success', 'Recipient added successfully!');
    }

    /**
     * Show send alert form
     */
    public function sendAlert(): View
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to send WhatsApp alerts.');
        }
        $templates = WhatsAppTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();
        $recipients = WhatsAppRecipient::where('is_active', true)
            ->where('opt_in', true)
            ->orderBy('name')
            ->get();

        return view('whatsapp-alerts.send-alert', [
            'templates' => $templates,
            'recipients' => $recipients,
        ]);
    }

    /**
     * Send WhatsApp alert
     */
    public function sendAlertAction(Request $request): RedirectResponse
    {
        if (Gate::denies('manage_alerts')) {
            abort(403, 'You are not authorized to send WhatsApp alerts.');
        }
        $validated = $request->validate([
            'recipient_id' => 'nullable|exists:whats_app_recipients,id',
            'phone_number' => 'nullable|string',
            'template_id' => 'nullable|exists:whats_app_templates,id',
            'custom_message' => 'nullable|string',
        ]);

        $phoneNumber = $validated['phone_number'] ?? null;
        $message = $validated['custom_message'] ?? null;
        $template = null;

        if ($validated['recipient_id'] ?? null) {
            $recipient = WhatsAppRecipient::find($validated['recipient_id']);
            $phoneNumber = $recipient->phone_number;

            if ($validated['template_id'] ?? null) {
                $template = WhatsAppTemplate::find($validated['template_id']);
                $message = $template->template;
            }
        }

        if (!$phoneNumber || !$message) {
            return back()->with('error', 'Please provide a phone number and message.');
        }

        $alert = $this->whatsappService->sendMessage(
            $phoneNumber,
            $message,
            $template
        );

        if ($alert) {
            return redirect()->route('whatsapp.index')
                ->with('success', 'Alert sent successfully!');
        }

        return back()->with('error', 'Failed to send alert. Check logs for details.');
    }

    /**
     * Show alert details
     */
    public function show(WhatsAppAlert $alert): View
    {
        return view('whatsapp-alerts.show', [
            'alert' => $alert->load('template'),
        ]);
    }

    /**
     * Retry failed alert
     */
    public function retry(WhatsAppAlert $alert): RedirectResponse
    {
        if (!$alert->isFailed()) {
            return back()->with('error', 'Only failed alerts can be retried.');
        }

        $alert->increment('retry_count');
        $newAlert = $this->whatsappService->sendMessage(
            $alert->recipient_phone,
            $alert->message,
            $alert->template,
            $alert->data
        );

        if ($newAlert && $newAlert->isSent()) {
            return back()->with('success', 'Alert resent successfully!');
        }

        return back()->with('error', 'Failed to resend alert.');
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard(): View
    {
        $stats = [
            'total_sent' => WhatsAppAlert::where('status', 'sent')->count(),
            'total_failed' => WhatsAppAlert::where('status', 'failed')->count(),
            'total_pending' => WhatsAppAlert::where('status', 'pending')->count(),
            'today_sent' => WhatsAppAlert::where('status', 'sent')
                ->whereDate('sent_at', today())
                ->count(),
            'total_recipients' => WhatsAppRecipient::where('is_active', true)->count(),
            'opted_in' => WhatsAppRecipient::where('opt_in', true)->count(),
        ];

        $recentAlerts = WhatsAppAlert::with('template')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('whatsapp-alerts.dashboard', [
            'stats' => $stats,
            'recentAlerts' => $recentAlerts,
        ]);
    }
}
