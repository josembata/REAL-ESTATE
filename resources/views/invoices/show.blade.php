<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Invoice #{{ $invoice->invoice_number }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                {{-- Invoice Details --}}
                <div>
                    <h3 class="text-lg font-semibold">Invoice Details</h3>
                    <p><strong>User:</strong> {{ $invoice->user->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
                    <p><strong>Total Due:</strong> {{ $invoice->currency }} {{ $invoice->amount_due }}</p>
                    <p><strong>Issued At:</strong> {{ $invoice->issued_at }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date ?? 'N/A' }}</p>
                </div>

                {{-- Related Bookings --}}
                <div>
                    <h3 class="text-lg font-semibold">Bookings in this Invoice</h3>
                    <table class="table-auto w-full border mt-2">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Booking ID</th>
                                <th>Unit / Room</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->bookings as $booking)
                                <tr>
                                    <td class="border px-4 py-2">{{ $booking->uuid }}</td>
                                    <td>{{ $booking->unit->unit_name ?? $booking->room->room_name ?? 'N/A' }}</td>
                                    <td>{{ $booking->check_in }}</td>
                                    <td>{{ $booking->check_out }}</td>
                                    <td>{{ $booking->currency }} {{ $booking->total_amount }}</td>
                                    <td>{{ ucfirst($booking->status) }}</td>
                                    <td>
                                        @if($booking->status !== 'cancelled')
                                            <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-red-600 px-2 py-1 rounded border border-red-600 hover:bg-red-100">
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Payments --}}
                <div>
                    <h3 class="text-lg font-semibold">Payments</h3>
                    @if($invoice->payments->count() > 0)
                        <ul class="list-disc pl-5">
                            @foreach($invoice->payments as $payment)
                                <li>
                                    {{ $payment->provider }} - {{ $payment->amount }} {{ $payment->currency }} 
                                    ({{ ucfirst($payment->status) }}) 
                                    @if($payment->paid_at)
                                        - Paid at: {{ $payment->paid_at }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No payments yet.</p>
                    @endif
                </div>

                {{-- Pay Now Button --}}
                @if($invoice->status !== 'paid')
                    <div class="mt-4">
                        <a href="{{ route('payments.choose', $invoice->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Pay Now
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
