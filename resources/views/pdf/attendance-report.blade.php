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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Attendance Report</h1>
            <p>Month: {{ now()->format('F Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <?php
                        $present = $student->attendances->where('status', 'present')->count();
                        $absent = $student->attendances->where('status', 'absent')->count();
                        $late = $student->attendances->where('status', 'late')->count();
                        $total = $student->attendances->count();
                        $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $student->schoolClass->name ?? 'N/A' }}</td>
                        <td>{{ $present }}</td>
                        <td>{{ $absent }}</td>
                        <td>{{ $late }}</td>
                        <td>{{ $percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
