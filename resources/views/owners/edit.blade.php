<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Owner
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('owners.update', $owner) }}" method="POST">
                    @csrf @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block font-semibold">Name</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-md"
                            value="{{ old('name', $owner->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block font-semibold">Email</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-md"
                            value="{{ old('email', $owner->email) }}">
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label class="block font-semibold">Phone</label>
                        <input type="text" name="phone" class="w-full border-gray-300 rounded-md"
                            value="{{ old('phone', $owner->phone) }}">
                    </div>

                    <!-- National ID -->
                    <div class="mb-4">
                        <label class="block font-semibold">National ID</label>
                        <input type="text" name="national_id" class="w-full border-gray-300 rounded-md"
                            value="{{ old('national_id', $owner->national_id) }}">
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label class="block font-semibold">Address</label>
                        <input type="text" name="address" class="w-full border-gray-300 rounded-md"
                            value="{{ old('address', $owner->address) }}">
                    </div>

                    

                  

                   

                 

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('owners.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</a>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
