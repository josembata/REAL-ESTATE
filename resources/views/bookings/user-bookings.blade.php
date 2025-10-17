<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Bookings</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <h3 class="font-bold mb-4">Bookings</h3>

        @if($bookings->isEmpty())
            <p>You have no active bookings.</p>
        @else
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">Unit</th>
                        <th class="p-2">Room</th>
                        <th class="p-2">Property</th>
                        <th class="p-2">Check-In</th>
                        <th class="p-2">Check-Out</th>
                        <th class="p-2">Amount</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Countdown</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr id="booking-row-{{ $booking->id }}">
                            <td class="p-2">{{ $booking->unit->unit_name ?? 'N/A' }}</td>
                            <td class="p-2">{{ $booking->room->room_name ?? 'No Room(All unit booked)' }}</td>
                            <td class="p-2">{{ $booking->property->name ?? 'N/A' }}</td>
                            <td class="p-2">{{ $booking->check_in }}</td>
                            <td class="p-2">{{ $booking->check_out }}</td>
                            <td class="p-2">{{ $booking->currency }} {{ $booking->total_amount }}</td>
                            <td class="p-2" id="status-{{ $booking->id }}">{{ ucfirst($booking->status) }}</td>
                            <td class="p-2">
                                @if($booking->status === 'pending' && $booking->payment_status === 'unpaid' && $booking->expires_at)
                                    <span id="countdown-{{ $booking->id }}" class="text-red-600 font-bold"></span>
                                @endif
                            </td>
                            <td class="p-2 flex space-x-2">
                                @if($booking->status !== 'cancelled')
                                    <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-danger-button>Cancel</x-danger-button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('bookings.restore', $booking->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-primary-button>Add again</x-primary-button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Countdown Script -->
                        @if($booking->status === 'pending' && $booking->payment_status === 'unpaid' && $booking->expires_at)
                        <script>
                        (function() {
                            const expireTime{{ $booking->id }} = new Date("{{ $booking->expires_at }}").getTime();
                            const countdownEl{{ $booking->id }} = document.getElementById("countdown-{{ $booking->id }}");
                            const statusEl{{ $booking->id }} = document.getElementById("status-{{ $booking->id }}");
                            const rowEl{{ $booking->id }} = document.getElementById("booking-row-{{ $booking->id }}");

                            const timer{{ $booking->id }} = setInterval(() => {
                                const now = new Date().getTime();
                                const diff = expireTime{{ $booking->id }} - now;

                                if (diff <= 0) {
                                    clearInterval(timer{{ $booking->id }});
                                    countdownEl{{ $booking->id }}.textContent = "❌ Expired";

                                    // Call backend to cancel
                                    fetch("{{ route('bookings.auto-cancel', $booking->id) }}", {
                                        method: "POST",
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                            "Accept": "application/json"
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if(data.success){
                                            statusEl{{ $booking->id }}.textContent = "Cancelled";
                                            countdownEl{{ $booking->id }}.classList.replace('text-red-600','text-gray-500');
                                        }
                                    });

                                    return;
                                }

                                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                countdownEl{{ $booking->id }}.textContent = ` ${minutes}m ${seconds}s remaining`;
                            }, 1000);
                        })();
                        </script>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

         <!-- Invoice Summary -->
            @if($invoice)
                <div class="mt-6 border-t pt-4">
                    <h3 class="font-bold">Invoice Summary</h3>
                    <p><strong>Invoice number:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Total Amount:</strong> {{ $invoice->currency }} {{ $invoice->amount_due }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>

                    <a href="{{ route('payments.choose', $invoice->id) }}"
                       class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded mt-3 inline-block">
                        Confirm & Pay
                    </a>
                </div>
            @endif
    </div>

</x-app-layout>
