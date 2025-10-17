<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight flex items-center space-x-2">
            <i class="fas fa-receipt text-blue-600"></i>
            <span>Booking Details</span>
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200">
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">📋 Booking Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p><strong>Booking ID:</strong> <span class="text-gray-700">{{ $booking->uuid }}</span></p>
                            <p><strong>Unit:</strong> <span class="text-gray-700">{{ $booking->unit->name ?? 'N/A' }}</span></p>
                            <p><strong>Property:</strong> <span class="text-gray-700">{{ $booking->property->name ?? 'N/A' }}</span></p>
                            <p><strong>Customer:</strong> <span class="text-gray-700">{{ $booking->customer->name ?? 'N/A' }}</span></p>
                        </div>

                        <div>
                            <p><strong>Agent:</strong> <span class="text-gray-700">{{ $booking->agent->name ?? 'N/A' }}</span></p>
                            <p><strong>Check In:</strong> <span class="text-gray-700">{{ $booking->check_in }}</span></p>
                            <p><strong>Check Out:</strong> <span class="text-gray-700">{{ $booking->check_out }}</span></p>
                            <p><strong>Total:</strong> 
                                <span class="text-lg font-semibold text-green-700">
                                    {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-x-3">
                            <span class="px-3 py-1 rounded-full text-white text-sm 
                                @if($booking->status === 'pending') bg-yellow-500 
                                @elseif($booking->status === 'confirmed') bg-blue-600 
                                @elseif($booking->status === 'paid') bg-green-600 
                                @elseif($booking->status === 'cancelled') bg-red-600 
                                @else bg-gray-500 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>

                            <span class="px-3 py-1 rounded-full text-white text-sm 
                                @if($booking->payment_status === 'unpaid') bg-red-500
                                @elseif($booking->payment_status === 'partial') bg-yellow-500
                                @elseif($booking->payment_status === 'paid') bg-green-600
                                @elseif($booking->payment_status === 'refunded') bg-gray-600
                                @endif">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>

                        <div class="flex space-x-3">
                            @if($booking->status === 'pending' && $booking->payment_status === 'unpaid')
                                <a href="{{ route('payments.choose', $invoice->id)}}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition">
                                   💳 Make Payment
                                </a>

                                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg shadow transition">
                                        ❌ Cancel
                                    </button>
                                </form>
                            @elseif($booking->payment_status === 'paid')
                                <span class="text-green-700 font-semibold text-lg">✅ Payment Completed</span>
                            @endif
                        </div>
                    </div>

                    @if($booking->status === 'pending' && $booking->payment_status === 'unpaid' && $booking->expires_at)
                        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-5 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-yellow-700 flex items-center space-x-2">
                                <i class="fas fa-clock"></i>
                                <span>Payment Time Remaining</span>
                            </h4>
                            <p id="countdown" class="text-2xl font-bold text-red-600 mt-2"></p>
                            <small class="text-gray-600">Please complete your payment before time runs out, or your booking will be cancelled automatically.</small>
                        </div>

                        <script>
                            const expiresAt = new Date("{{ $booking->expires_at }}").getTime();
                            const countdownEl = document.getElementById('countdown');
                            const timer = setInterval(() => {
                                const now = new Date().getTime();
                                const diff = expiresAt - now;

                                if (diff <= 0) {
                                    clearInterval(timer);
                                    countdownEl.textContent = "❌ Booking expired!";
                                    countdownEl.classList.replace('text-red-600', 'text-gray-500');
                                    return;
                                }

                                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                countdownEl.textContent = `${hours}h ${minutes}m ${seconds}s`;
                            }, 1000);
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
