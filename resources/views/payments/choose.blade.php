<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Choose Payment Method</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <p><strong>Total to Pay:</strong> {{ $invoice->currency }} {{ $invoice->amount_due }}</p>

        <div class="mt-4 space-y-3">
            <!-- Mobile Money -->
            <button onclick="toggleForm('mobile-form')" class="block w-full  bg-green-500 text-white px-4 py-2 rounded">
                Mobile Money (M-Pesa / Tigo Pesa)
            </button>
            <div id="mobile-form" class="hidden mt-3 p-4 border rounded bg-gray-50">
                <form method="POST" action="{{ route('payments.mobile', $invoice->id) }}">
                    @csrf
                    <label class="block mb-2">Select Provider</label>
                    <select name="provider" class="w-full border rounded px-3 py-2 mb-3" required>
                        <option value="">-- Select --</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="tigopesa">Tigo Pesa</option>
                    </select>

                   <label class="block mb-2">Phone Number</label>
                   <input type="text" name="phone_number"
                    class="w-full border rounded px-3 py-2 mb-3"
                    placeholder="255657004727"
                    value="{{ old('phone_number', $tenant->phone ?? '') }}"
                     required>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Pay Now</button>
                </form>
            </div>

            <!-- Card Payment -->
            <button onclick="toggleForm('card-form')" class="block w-full bg-blue-600 text-white px-4 py-2 rounded">
                Pay with Card
            </button>
            <div id="card-form" class="hidden mt-3 p-4 border rounded bg-gray-50">
                <form method="POST" action="{{ route('payments.card', $invoice->id) }}">
                    @csrf
                   <label class="block mb-2">Card Number</label>
                   <input type="text" name="card_number"
                   class="w-full border rounded px-3 py-2 mb-3"
                   placeholder=""
                   value="{{ old('card_number', $tenant->account_number ?? '') }}"
                   required>

                    <label class="block mb-2">Expiry Date</label>
                    <input type="text" name="expiry" class="w-full border rounded px-3 py-2 mb-3" placeholder="MM/YY" required>

                    <label class="block mb-2">CVV</label>
                    <input type="text" name="cvv" class="w-full border rounded px-3 py-2 mb-3" placeholder="123" required>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Pay Now</button>
                </form>
            </div>
        </div>
    </div>

    <!--  JS to toggle forms -->
    <script>
        function toggleForm(formId) {
            document.querySelectorAll('#mobile-form, #card-form').forEach(form => {
                if (form.id === formId) {
                    form.classList.toggle('hidden');
                } else {
                    form.classList.add('hidden');
                }
            });
        }
    </script>
</x-app-layout>
