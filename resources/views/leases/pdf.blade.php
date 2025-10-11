<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Lease Agreement - {{ $lease->lease_number }}</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --light-gray: #f8f9fa;
            --border-color: #e0e0e0;
            --text-color: #333;
            --text-light: #666;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: var(--text-color);
            background-color: #fff;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        
        header { 
            text-align: center; 
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }
        
        h1 { 
            font-size: 28px; 
            margin: 0 0 10px;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .document-info {
            font-size: 14px;
            color: var(--text-light);
            background-color: var(--light-gray);
            padding: 8px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .section { 
            margin-top: 25px;
            padding: 20px;
            border-radius: 6px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-left: 4px solid var(--accent-color);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        
        .section-title:before {
            content: "";
            display: inline-block;
            width: 6px;
            height: 6px;
            background-color: var(--accent-color);
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .small { 
            font-size: 13px; 
            color: var(--text-light); 
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 6px;
            overflow: hidden;
        }
        
        th { 
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
        }
        
        td { 
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        tr:hover {
            background-color: #f0f5ff;
        }
        
        .party-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .party-card {
            width: 48%;
            padding: 15px;
            background-color: var(--light-gray);
            border-radius: 6px;
        }
        
        .party-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary-color);
        }
        
        .signature { 
            margin-top: 40px; 
            display: flex; 
            justify-content: space-between; 
        }
        
        .sig-block { 
            width: 45%; 
            text-align: center; 
        }
        
        .sig-line { 
            margin-top: 50px; 
            border-top: 1px solid var(--border-color); 
            width: 100%; 
        }
        
        .highlight-box {
            background-color: #e8f4fd;
            border-left: 4px solid var(--accent-color);
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .term-dates {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .term-date {
            text-align: center;
            padding: 10px;
            background-color: var(--light-gray);
            border-radius: 4px;
            width: 45%;
        }
        
        .date-label {
            font-size: 13px;
            color: var(--text-light);
        }
        
        .date-value {
            font-weight: 600;
            font-size: 16px;
            margin-top: 5px;
            color: var(--primary-color);
        }
        
        footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }
        
        .total-rent {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            background-color: #e8f4fd;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1>LEASE AGREEMENT</h1>
    <div class="document-info">
        Lease #: {{ $lease->lease_number }} | Issued: {{ $lease->created_at->format('Y-m-d') }}
    </div>
</header>

<div class="section">
    <div class="section-title">Parties</div>
    <div class="party-info">
       <div class="party-card">
    <div class="party-name">Landlord</div>

    {{-- Display user's name --}}
    <div>{{ optional($owner->user)->name ?? 'Owner' }}</div>


    <div class="small">
        {{ optional($owner->user)->email ?? 'N/A' }}
        
    
    </div>
</div>

        <div class="party-card">
            <div class="party-name">Tenant</div>
            <div>{{ $tenant->name }}</div>
            <div class="small">{{ $tenant->email }}</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Premises</div>
    <p>
        This lease covers the following premises (bookings included in invoice #{{ $lease->invoice->invoice_number }}):
    </p>
    <table>
        <thead>
            <tr>
                <th>Booking</th>
                <th>Unit / Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Amount ({{ $lease->invoice->currency }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lease->invoice->bookings as $booking)
                <tr>
                    <td>{{ $booking->uuid }}</td>
                    <td>{{ $booking->unit->unit_name ?? $booking->room->room_name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d') }}</td>
                    <td>{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Term</div>
    <p>
        The term of the lease shall be from:
    </p>
    <div class="term-dates">
        <div class="term-date">
            <div class="date-label">Start Date</div>
            <div class="date-value">{{ $term_start->format('Y-m-d') }}</div>
        </div>
        <div class="term-date">
            <div class="date-label">End Date</div>
            <div class="date-value">{{ $term_end->format('Y-m-d') }}</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Rent / Payment</div>
    <p>Total rent:</p>
    <div class="total-rent">
        {{ $lease->invoice->currency }} {{ number_format($lease->invoice->amount_due, 2) }}
    </div>
    <p class="small" style="margin-top: 10px;">
        Payment received via invoice #{{ $lease->invoice->invoice_number }}.
    </p>
</div>

<div class="section">
    <div class="section-title">Signatures</div>
    <div class="signature">
        <div class="sig-block">
            <div style="margin-bottom: 10px;">Landlord</div>
            <div class="sig-line"></div>
            <div class="small" style="margin-top: 5px;">{{ $owner->name ?? '' }}</div>
        </div>

        <div class="sig-block">
            <div style="margin-bottom: 10px;">Tenant</div>
            <div class="sig-line"></div>
            <div class="small" style="margin-top: 5px;">{{ $tenant->name }}</div>
        </div>
    </div>
</div>

<footer class="small">
    This document is an agreement between the parties and is generated automatically by the system.
</footer>
</body>
</html>