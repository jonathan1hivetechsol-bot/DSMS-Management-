<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\Invoice;

class PDFService
{
    /**
     * Generate a student report card PDF
     */
    public static function generateReportCard(Student $student)
    {
        $grades = $student->grades()->get()->groupBy('term');
        $attendance = $student->attendances()->whereYear('date', now()->year)->get();
        
        $attendancePercentage = $attendance->count() > 0 
            ? (($attendance->where('status', 'present')->count() / $attendance->count()) * 100)
            : 0;

        $pdf = Pdf::loadView('pdf.report-card', compact('student', 'grades', 'attendance', 'attendancePercentage'));
        
        return $pdf->download("Report-{$student->user->name}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Generate a student report card PDF and return content
     */
    public static function generateReportCardBase64(Student $student)
    {
        $grades = $student->grades()->get()->groupBy('term');
        $attendance = $student->attendances()->whereYear('date', now()->year)->get();
        
        $attendancePercentage = $attendance->count() > 0 
            ? (($attendance->where('status', 'present')->count() / $attendance->count()) * 100)
            : 0;

        $pdf = Pdf::loadView('pdf.report-card', compact('student', 'grades', 'attendance', 'attendancePercentage'));
        
        return base64_encode($pdf->output());
    }

    /**
     * Generate an invoice PDF
     */
    public static function generateInvoice(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download("Invoice-{$invoice->id}.pdf");
    }

    /**
     * Generate an invoice PDF and return content
     */
    public static function generateInvoiceBase64(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return base64_encode($pdf->output());
    }

    /**
     * Generate an attendance report PDF
     */
    public static function generateAttendanceReport($students, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $pdf = Pdf::loadView('pdf.attendance-report', compact('students', 'month', 'year'));
        
        return $pdf->download("Attendance-Report-{$year}-{$month}.pdf");
    }

    /**
     * Generate a class report PDF with all students
     */
    public static function generateClassReport($schoolClass)
    {
        $students = $schoolClass->students()->with('attendances', 'grades')->get();
        
        $pdf = Pdf::loadView('pdf.class-report', compact('schoolClass', 'students'));
        
        return $pdf->download("Class-Report-{$schoolClass->name}-" . now()->format('Y-m-d') . ".pdf");
    }
}
