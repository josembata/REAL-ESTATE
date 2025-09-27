<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Amenity
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Update Amenity Details</h3>

            <form action="{{ route('amenities.update', $amenity) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Amenity Name -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Amenity Name</label>
                    <input type="text" name="name" value="{{ old('name', $amenity->name) }}" required
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Category</label>
                    <select name="category_id" class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == $amenity->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Icon Upload -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Icon / Image</label>
                    <input type="file" name="icon" class="block w-full text-gray-700">

                    @if($amenity->icon)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Current Icon:</p>
                            <img src="{{ asset('storage/'.$amenity->icon) }}" class="w-16 h-16 rounded border">
                        </div>
                    @endif
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('amenities.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
