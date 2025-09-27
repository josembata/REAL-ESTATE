<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">Booking Information</h3>

                <p><strong>Booking ID:</strong> {{ $booking->uuid }}</p>
                <p><strong>Unit:</strong> {{ $booking->unit->name ?? 'N/A' }}</p>
                <p><strong>Property:</strong> {{ $booking->property->name ?? 'N/A' }}</p>
                <p><strong>Customer:</strong> {{ $booking->customer->name ?? 'N/A' }}</p>
                <p><strong>Agent:</strong> {{ $booking->agent->name ?? 'N/A' }}</p>

                <p><strong>Price Plan:</strong>
                    {{ $booking->unitPricePlan->name ?? 'N/A' }}
                    ({{ $booking->unitPricePlan->category->name ?? '' }})
                </p>

                <p><strong>Check In:</strong> {{ $booking->check_in }}</p>
                <p><strong>Check Out:</strong> {{ $booking->check_out }}</p>
                <p><strong>Total:</strong> {{ $booking->currency }} {{ $booking->total_amount }}</p>

                <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</p>

                <div class="mt-6 flex space-x-4">
                    @if($booking->status === 'pending')
                        <form method="POST" action="{{ route('bookings.confirm', $booking->id) }}">
                            @csrf
                            @method('PATCH')
                            <x-primary-button>Confirm</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                            @csrf
                            @method('PATCH')
                            <x-danger-button>Cancel</x-danger-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
