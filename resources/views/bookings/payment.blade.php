<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Payment for Booking #{{ $booking->uuid }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">Select Payment Method</h3>

                <form method="POST" action="{{ route('bookings.pay', $booking->id) }}">
                    @csrf

                    <div class="space-y-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="method" value="mpesa" required>
                            <span>M-Pesa</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="radio" name="method" value="tigopesa" required>
                            <span>Tigo Pesa</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="radio" name="method" value="card" required>
                            <span>Card</span>
                        </label>
                    </div>

                    <x-primary-button class="mt-4">Pay {{ $booking->currency }} {{ $booking->total_amount }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
