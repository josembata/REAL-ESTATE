<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Owner
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('owners.store') }}" method="POST">
                    @csrf

                    <!-- Company Name -->
                    <div class="mb-4">
                        <label class="block font-semibold">Owner/Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" 
                               class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label class="block font-semibold">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" 
                               class="w-full border-gray-300 rounded-md">
                    </div>

                    <!-- Tax ID -->
                    <div class="mb-4">
                        <label class="block font-semibold">Tax ID</label>
                        <input type="text" name="tax_id" value="{{ old('tax_id') }}" 
                               class="w-full border-gray-300 rounded-md">
                    </div>

                    <!-- Bank Account -->
                    <div class="mb-4">
                        <label class="block font-semibold">Bank Account</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" 
                               class="w-full border-gray-300 rounded-md">
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('owners.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</a>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
