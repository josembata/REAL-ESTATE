<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .invoice-box { border: 1px solid #ccc; padding: 20px; }
        .header { font-size: 20px; margin-bottom: 10px; }
        .amount { font-size: 18px; margin: 10px 0; }
        button { margin-top: 20px; padding: 10px 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">Invoice #{{ $invoice->invoice_number }}</div>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        <p><strong>Amount Due:</strong> {{ $invoice->currency }} {{ number_format($invoice->amount_due, 2) }}</p>
        <p><strong>Issued:</strong> {{ $invoice->issued_at }}</p>
        <p><strong>Due:</strong> {{ $invoice->due_date ?? 'N/A' }}</p>
        <p><strong>Bookings Included:</strong></p>
        <ul>
            @foreach($invoice->bookings as $booking)
                <li>Booking #{{ $booking->uuid }} — {{ $booking->unit->unit_name ?? $booking->room->room_name ?? 'N/A' }} 
                    ({{ $booking->check_in }} to {{ $booking->check_out }}) 
                    - {{ $booking->currency }} {{ $booking->total_amount }}
                </li>
            @endforeach
        </ul>
    </div>

    <button onclick="window.print()">Print</button>
</body>
</html>
