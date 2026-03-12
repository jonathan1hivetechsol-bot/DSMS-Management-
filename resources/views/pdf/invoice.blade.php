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
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            align-items: center;
        }
        .header-left, .header-center, .header-right {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 14px;
        }
        .invoice-title {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .detail-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #007bff;
        }
        .detail-section-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .detail-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .detail-label {
            color: #999;
            font-size: 12px;
        }
        .detail-value {
            color: #333;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #e9ecef;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        td {
            border: 1px solid #ddd;
            padding: 12px;
            color: #666;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        .summary-notes {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .summary-notes-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .summary-totals {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 3px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .total-row.final {
            border-bottom: none;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #28a745;
            color: white;
        }
        .status-pending {
            background-color: #ffc107;
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
        .amount-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>🏫 School Name</h1>
                <p>Fee Invoice</p>
            </div>
            <div class="header-center">
                <p style="margin-top: 10px;">School Administration</p>
                <p>Any City, State 12345</p>
                <p>Phone: +1-800-SCHOOL</p>
            </div>
            <div class="header-right">
                <div class="detail-label">Invoice #</div>
                <div style="font-size: 18px; font-weight: bold; color: #333;">{{ $invoice->id }}</div>
            </div>
        </div>

        <div class="invoice-title">Fee Invoice & Payment Receipt</div>

        <div class="invoice-details">
            <div class="detail-section">
                <div class="detail-section-title">STUDENT INFORMATION</div>
                <div class="detail-item">
                    <div class="detail-label">Name</div>
                    <div class="detail-value">{{ $invoice->student->user->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Roll Number</div>
                    <div class="detail-value">{{ $invoice->student->roll_number }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Class</div>
                    <div class="detail-value">{{ $invoice->schoolClass->name ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">INVOICE INFORMATION</div>
                <div class="detail-item">
                    <div class="detail-label">Issued Date</div>
                    <div class="detail-value">{{ $invoice->created_at->format('M d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value">{{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($invoice->paid_at)
                            <span class="status-badge status-paid">PAID</span>
                        @else
                            <span class="status-badge status-pending">PENDING</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->description }}</td>
                    <td class="amount-right">₹{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-section">
            <div class="summary-notes">
                <div class="summary-notes-title">Payment Instructions</div>
                <p style="margin: 0; font-size: 12px; color: #666; line-height: 1.6;">
                    Please pay the amount shown below by the due date. 
                    You can pay via the school office, online banking, or cheque.
                    Make cheques payable to "School Name".
                </p>
            </div>
            <div class="summary-totals">
                @if($invoice->discount)
                    <div class="total-row">
                        <div>Gross Amount</div>
                        <div class="amount-right">₹{{ number_format($invoice->amount + $invoice->discount, 2) }}</div>
                    </div>
                    <div class="total-row">
                        <div>Discount</div>
                        <div class="amount-right">-₹{{ number_format($invoice->discount, 2) }}</div>
                    </div>
                @endif
                <div class="total-row final">
                    <div>Amount Due</div>
                    <div class="amount-right">₹{{ number_format($invoice->amount, 2) }}</div>
                </div>
                @if($invoice->paid_at)
                    <div class="total-row" style="border: none; padding-top: 10px; color: #28a745;">
                        <strong>Paid on {{ $invoice->paid_at->format('M d, Y') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your payment. This is a system-generated invoice.</p>
            <p>For more information, visit: www.school.edu | Email: info@school.edu</p>
            <p>Generated on {{ now()->format('M d, Y H:i A') }}</p>
        </div>
    </div>
</body>
</html>
