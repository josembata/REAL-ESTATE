<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Book {{ $room->room_name ?? 'Room' }} — {{ $unit->unit_name ?? $unit->name ?? '' }}</h2>
    </x-slot>

    <div class="p-6">
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('rooms.book.store', [$unit, $room]) }}" id="room-book-form">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold">Choose Price Plan</label>
                @forelse($pricePlans as $plan)
                    <div class="flex items-center space-x-2 mt-2">
                        <input type="radio" name="room_price_plan_id" value="{{ $plan->id }}"
                               id="plan_{{ $plan->id }}"
                               data-price="{{ $plan->price }}"
                               data-category="{{ strtolower(optional($plan->category)->name ?? 'daily') }}"
                               required>
                        <label for="plan_{{ $plan->id }}">
                            {{ ucfirst(optional($plan->category)->name ?? 'Uncategorized') }}
                            — {{ number_format($plan->price, 2) }} {{ $plan->currency ?? 'USD' }}
                        </label>
                    </div>
                @empty
                    <p>No price plans defined for this room. <a href="{{ route('rooms.price-plans.create', [$unit, $room]) }}" class="text-blue-600">Add one</a></p>
                @endforelse
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Check-in</label>
                    <input type="date" id="check_in" name="check_in" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block font-semibold">Check-out</label>
                    <input type="date" id="check_out" name="check_out" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block font-semibold">Total Amount</label>
                <input type="text" id="total_amount_display" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                <input type="hidden" name="total_amount" id="total_amount_input">
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Book Now</button>
                <a href="{{ route('units.rooms.index', $unit) }}" class="ml-3 text-gray-600">Back to Rooms</a>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const form = document.getElementById('room-book-form');
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            const totalDisplay = document.getElementById('total_amount_display');
            const totalInput = document.getElementById('total_amount_input');

            function calculate() {
                const selected = document.querySelector('input[name="room_price_plan_id"]:checked');
                if (!selected) {
                    totalDisplay.value = '';
                    totalInput.value = '';
                    return;
                }

                const price = parseFloat(selected.dataset.price) || 0;
                const category = (selected.dataset.category || 'daily').toLowerCase();
                const inDate = new Date(checkIn.value);
                const outDate = new Date(checkOut.value);

                if (!checkIn.value || !checkOut.value || !(outDate > inDate)) {
                    totalDisplay.value = '';
                    totalInput.value = '';
                    return;
                }

                const diffMs = Math.abs(outDate - inDate);
                const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

                let units = 0;
                if (category === 'daily') {
                    units = diffDays;
                } else if (category === 'monthly') {
                    units = Math.ceil(diffDays / 30);
                } else if (category === 'quarterly') {
                    units = Math.ceil(diffDays / 90);
                } else if (category === 'semi-annual' || category === 'semiannual' || category === 'semi_annual') {
                    units = Math.ceil(diffDays / 180);
                } else if (category === 'yearly' || category === 'annual') {
                    units = Math.ceil(diffDays / 365);
                } else {
                    units = diffDays;
                }
                if (units <= 0) units = 1;

                const total = units * price;
                totalDisplay.value = `$${total.toFixed(2)}`;
                totalInput.value = total.toFixed(2);
            }

            // attach events
            form.addEventListener('change', function(e) {
                if (e.target.name === 'room_price_plan_id' || e.target.id === 'check_in' || e.target.id === 'check_out') {
                    calculate();
                }
            });

            // ensure total is recalculated before submit (defense)
            form.addEventListener('submit', function(e) {
                calculate();
            });
        })();
    </script>
</x-app-layout>
