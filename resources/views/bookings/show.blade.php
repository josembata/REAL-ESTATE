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
                <p><strong>Unit:</strong> {{ $booking->unit->name ?? 'N' }}</p>
                <p><strong>Property:</strong> {{ $booking->property->name ?? 'N/A' }}</p>
                <p><strong>Customer:</strong> {{ $booking->customer->name ?? 'N/A' }}</p>
                <p><strong>Agent:</strong> {{ $booking->agent->name ?? 'N' }}</p>


                <p><strong>Check In:</strong> {{ $booking->check_in }}</p>
                <p><strong>Check Out:</strong> {{ $booking->check_out }}</p>
                <p><strong>Total:</strong> {{ $booking->currency }} {{ $booking->total_amount }}</p>

                <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</p>

               <div class="mt-6 flex space-x-4">
    @if($booking->status === 'pending' && $booking->payment_status === 'unpaid')
        <a href="{{ route('bookings.payment', $booking->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
           Make Payment
        </a>

        <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
            @csrf
            @method('PATCH')
            <x-danger-button>Cancel</x-danger-button>
        </form>
    @elseif($booking->payment_status === 'paid')
        <span class="text-blue-600 font-semibold">Payment Completed</span>
    @endif
</div>

            </div>
        </div>
    </div>
</x-app-layout>
