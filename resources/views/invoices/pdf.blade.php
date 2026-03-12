<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice #{{ $invoice->id }}</h2>
        <p>Date: {{ $invoice->created_at->format('Y-m-d') }}</p>
    </div>
    <div class="details">
        <p><strong>Student:</strong> {{ $invoice->student->user->name ?? '' }}</p>
        <p><strong>Class:</strong> {{ $invoice->schoolClass?->name }}</p>
    </div>
    <table>
        <thead>
            <tr><th>Description</th><th>Amount</th><th>Due Date</th><th>Status</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->description }}</td>
                <td>{{ number_format($invoice->amount,2) }}</td>
                <td>{{ $invoice->due_date?->format('Y-m-d') }}</td>
                <td>{{ $invoice->isPaid() ? 'Paid' : 'Unpaid' }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>