<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Class Report</h1>
            <h3>{{ $schoolClass->name }}</h3>
            <p>Report Generated: {{ now()->format('M d, Y') }}</p>
        </div>

        <h4>Class Overview</h4>
        <table>
            <tr>
                <td><strong>Class:</strong></td>
                <td>{{ $schoolClass->name }}</td>
            </tr>
            <tr>
                <td><strong>Class Teacher:</strong></td>
                <td>{{ $schoolClass->teacher->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Total Students:</strong></td>
                <td>{{ $schoolClass->students->count() }}</td>
            </tr>
            <tr>
                <td><strong>Class Capacity:</strong></td>
                <td>{{ $schoolClass->capacity }}</td>
            </tr>
        </table>

        <h4>Student Performance</h4>
        <table>
            <thead>
                <tr>
                    <th>Roll No.</th>
                    <th>Student Name</th>
                    <th>Grades Count</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <?php
                        $gradeCount = $student->grades->count();
                        $attendances = $student->attendances;
                        $present = $attendances->where('status', 'present')->count();
                        $attendancePercentage = $attendances->count() > 0 ? round(($present / $attendances->count()) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td>{{ $student->roll_number }}</td>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $gradeCount }}</td>
                        <td>{{ $attendancePercentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
