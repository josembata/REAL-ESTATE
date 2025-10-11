<?php

namespace App\Services;

use App\Models\Ownership;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LandlordReportService
{
    public function getReport($landlordId)
    {
        // 1Property IDs owned by landlord
        $propertyIds = Ownership::where('owner_id', $landlordId)
                                ->where('status', 'verified')
                                ->pluck('property_id');

        // Booking IDs for these properties
        $bookingIds = DB::table('bookings')
            ->join('units', 'bookings.unit_id', '=', 'units.id')
            ->whereIn('units.property_id', $propertyIds)
            ->pluck('bookings.id');

        // Invoice IDs associated with those bookings
        $invoiceIds = DB::table('invoice_bookings')
            ->whereIn('booking_id', $bookingIds)
            ->pluck('invoice_id');

        // Date ranges
        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();
        $yearStart = $now->copy()->startOfYear();

        // Total income
        $weeklyIncome = Payment::whereIn('invoice_id', $invoiceIds)
            ->whereBetween('created_at', [$weekStart, $now])
            ->sum('amount');

        $monthlyIncome = Payment::whereIn('invoice_id', $invoiceIds)
            ->whereBetween('created_at', [$monthStart, $now])
            ->sum('amount');

        $yearlyIncome = Payment::whereIn('invoice_id', $invoiceIds)
            ->whereBetween('created_at', [$yearStart, $now])
            ->sum('amount');

        // Income breakdown per property (weekly, monthly, yearly)
        $properties = DB::table('properties')
            ->whereIn('properties.id', $propertyIds)
            ->select('id', 'name')
            ->get();

        $propertyIncome = $properties->map(function($property) use ($invoiceIds, $weekStart, $monthStart, $yearStart, $now) {
            // Payments for this property
            $propertyInvoiceIds = DB::table('invoice_bookings')
                ->join('bookings', 'invoice_bookings.booking_id', '=', 'bookings.id')
                ->join('units', 'bookings.unit_id', '=', 'units.id')
                ->where('units.property_id', $property->id)
                ->pluck('invoice_bookings.invoice_id');

            $weekly = Payment::whereIn('invoice_id', $propertyInvoiceIds)
                ->whereBetween('created_at', [$weekStart, $now])
                ->sum('amount');

            $monthly = Payment::whereIn('invoice_id', $propertyInvoiceIds)
                ->whereBetween('created_at', [$monthStart, $now])
                ->sum('amount');

            $yearly = Payment::whereIn('invoice_id', $propertyInvoiceIds)
                ->whereBetween('created_at', [$yearStart, $now])
                ->sum('amount');

            return [
                'id' => $property->id,
                'name' => $property->name,
                'weekly_income' => $weekly,
                'monthly_income' => $monthly,
                'yearly_income' => $yearly,
            ];
        });

        return [
            'weekly_income' => $weeklyIncome,
            'monthly_income' => $monthlyIncome,
            'yearly_income' => $yearlyIncome,
            'property_income' => $propertyIncome,
        ];
    }
}
