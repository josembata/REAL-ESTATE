<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Billing Information
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mt-6">
        <form action="{{ route('billing.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Hidden user_id (auth user) -->
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <!-- hidden uint id -->
            <input type="hidden" name="unit_id" value="{{ $unitId }}">

            <!-- Billing Address -->
            <div>
                <label class="block font-medium mb-1">Billing Address</label>
                <input type="text" name="billing_address" value="{{ old('billing_address') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                    required>
                @error('billing_address') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tax ID -->
            <div>
                <label class="block font-medium mb-1">Tax ID</label>
                <input type="text" name="tax_id" value="{{ old('tax_id') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200">
                @error('tax_id') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block font-medium mb-1">Payment Method</label>
                <select name="payment_method" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200" required>
                    <option value="">-- Select --</option>
                    <option value="Mpesa" {{ old('payment_method') == 'Mpesa' ? 'selected' : '' }}>Mpesa</option>
                    <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                    <option value="Bank" {{ old('payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                </select>
                @error('payment_method') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Name -->
            <div>
                <label class="block font-medium mb-1">Contact Name</label>
                <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                    required>
                @error('contact_name') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Email -->
            <div>
                <label class="block font-medium mb-1">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                    required>
                @error('contact_email') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Phone -->
            <div>
                <label class="block font-medium mb-1">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                    required>
                @error('contact_phone') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Save & Continue
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
