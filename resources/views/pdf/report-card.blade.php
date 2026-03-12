<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            margin: 0 auto;
            max-width: 900px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .student-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
        }
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: bold;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #e9ecef;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        td {
            border: 1px solid #ddd;
            padding: 10px;
            color: #666;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .grade-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }
        .grade-badge.low {
            background-color: #dc3545;
        }
        .grade-badge.medium {
            background-color: #ffc107;
            color: #333;
        }
        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        .summary-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .summary-label {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .empty-message {
            text-align: center;
            color: #999;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Academic Report Card</h1>
            <p>Student Performance Summary</p>
        </div>

        <div class="student-info">
            <div class="info-item">
                <span class="info-label">Student Name:</span>
                <span class="info-value">{{ $student->user->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Roll Number:</span>
                <span class="info-value">{{ $student->roll_number }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Class:</span>
                <span class="info-value">{{ $student->schoolClass->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value">{{ $student->date_of_birth?->format('M d, Y') ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Report Generated:</span>
                <span class="info-value">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        @if($grades->count() > 0)
            @foreach($grades as $term => $termGrades)
                <div class="section-title">{{ $term }} - Academic Performance</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Exam Type</th>
                            <th>Marks Obtained</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($termGrades as $grade)
                            <tr>
                                <td>{{ $grade->subject }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $grade->exam_type)) }}</td>
                                <td>{{ $grade->marks_obtained }}</td>
                                <td>{{ $grade->total_marks }}</td>
                                <td>{{ $grade->percentage }}%</td>
                                <td>
                                    <span class="grade-badge {{ $grade->percentage >= 70 ? '' : ($grade->percentage >= 50 ? 'medium' : 'low') }}">
                                        {{ $grade->grade }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <div class="empty-message">No grades recorded yet</div>
        @endif

        <div class="section-title">Attendance Summary</div>
        <div class="summary">
            <div class="summary-box">
                <div class="summary-label">Total Days Present</div>
                <div class="summary-value">{{ $attendance->where('status', 'present')->count() }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Attendance Percentage</div>
                <div class="summary-value">{{ round($attendancePercentage, 2) }}%</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Days Absent</div>
                <div class="summary-value">{{ $attendance->where('status', 'absent')->count() }}</div>
            </div>
        </div>

        <div class="footer">
            <p>This is an official report card issued by {{ config('app.name') }}</p>
            <p>For queries, contact: principal@school.edu | Phone: +1-800-SCHOOL</p>
        </div>
    </div>
</body>
</html>
