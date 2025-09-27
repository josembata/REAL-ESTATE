<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Book Unit: {{ $unit->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('bookings.store', $unit->id) }}">
                    @csrf

                    <!-- Unit Name (read-only) -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Unit</label>
                        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ $unit->name }}" readonly>
                    </div>

                    <!-- Price Plan -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Price Plan</label>
                        <select name="unit_price_plan_id" id="price_plan" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select Price Plan</option>
                       @foreach($pricePlans as $plan)
    @php $categoryName = $plan->category ? strtolower($plan->category->name) : 'unknown'; @endphp
    <option value="{{ $plan->id }}"
        data-price="{{ $plan->price }}"
        data-category="{{ $categoryName }}">
        {{ ucfirst($categoryName) }} – {{ $plan->price }} {{ $plan->currency }}
    </option>
@endforeach
                        </select>
                    </div>

                    <!-- Check-in & Check-out -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Check-in</label>
                        <input type="date" name="check_in" id="check_in" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Check-out</label>
                        <input type="date" name="check_out" id="check_out" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <!-- Total Amount -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Total Amount</label>
                        <input type="text" id="total_amount" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Book Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Total Calculation Script -->
    <script>
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const pricePlan = document.getElementById('price_plan');
        const totalAmount = document.getElementById('total_amount');

        function calculateTotal() {
            const selected = pricePlan.selectedOptions[0];
            if (!selected) return;

            const price = parseFloat(selected.dataset.price) || 0;
            const category = selected.dataset.category;
            const checkInDate = new Date(checkIn.value);
            const checkOutDate = new Date(checkOut.value);

            if (!checkIn.value || !checkOut.value || checkOutDate <= checkInDate) {
                totalAmount.value = '';
                return;
            }

            const diffTime = Math.abs(checkOutDate - checkInDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            let total = 0;

            switch (category) {
                case 'daily':
                    total = diffDays * price;
                    break;
                case 'weekly':
                    total = Math.ceil(diffDays / 7) * price;
                    break;
                case 'monthly':
                    total = Math.ceil(diffDays / 30) * price;
                    break;
                case 'quarterly':
                    total = Math.ceil(diffDays / 90) * price;
                    break;
                case 'semi-annual':
                    total = Math.ceil(diffDays / 180) * price;
                    break;
                case 'yearly':
                    total = Math.ceil(diffDays / 365) * price;
                    break;
                default:
                    total = diffDays * price;
            }

            totalAmount.value = `$${total.toFixed(2)}`;
        }

        checkIn.addEventListener('change', calculateTotal);
        checkOut.addEventListener('change', calculateTotal);
        pricePlan.addEventListener('change', calculateTotal);
    </script>
</x-app-layout>
