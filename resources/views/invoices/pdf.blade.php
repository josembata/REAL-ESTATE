<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Invoice</div>
        <p>Invoice Number: {{ $invoice->invoice_number }}</p>
        <p>Status: {{ ucfirst($invoice->status) }}</p>
        <p>Issued: {{ $invoice->issued_at }}</p>
        <p>Paid at: {{ $invoice->paid_at ?? 'Not paid' }}</p>
    </div>

    <h3>Bookings</h3>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Unit/Room</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->bookings as $booking)
                <tr>
                    <td>{{ $booking->uuid }}</td>
                    <td>{{ $booking->unit->unit_name ?? $booking->room->room_name ?? 'N/A' }}</td>
                    <td>{{ $booking->check_in }}</td>
                    <td>{{ $booking->check_out }}</td>
                    <td>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: {{ $invoice->currency }} {{ number_format($invoice->amount_due, 2) }}</h3>
</body>
</html>
