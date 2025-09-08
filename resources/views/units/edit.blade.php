<x-app-layout>
    <div class="max-w-3xl mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Edit Unit</h2>

        <form method="POST" action="{{ route('units.update', $unit) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Property</label>
                <select name="property_id" required class="w-full border p-2 rounded">
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ $unit->property_id == $property->id ? 'selected' : '' }}>
                            {{ $property->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Unit Name</label>
                <input type="text" name="unit_name" value="{{ $unit->unit_name }}" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border p-2 rounded">{{ $unit->description }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ $unit->price }}" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Currency</label>
                    <input type="text" name="currency" value="{{ $unit->currency }}" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Unit Type</label>
                <select name="unit_type" required class="w-full border p-2 rounded">
                    <option value="single" {{ $unit->unit_type == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="double" {{ $unit->unit_type == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="suite" {{ $unit->unit_type == 'suite' ? 'selected' : '' }}>Suite</option>
                    <option value="office" {{ $unit->unit_type == 'office' ? 'selected' : '' }}>Office</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Furnishing</label>
                <select name="furnishing" class="w-full border p-2 rounded">
                    <option value="unfurnished" {{ $unit->furnishing == 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                    <option value="partially_furnished" {{ $unit->furnishing == 'partially_furnished' ? 'selected' : '' }}>Partially Furnished</option>
                    <option value="furnished" {{ $unit->furnishing == 'furnished' ? 'selected' : '' }}>Furnished</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Size (sqft)</label>
                <input type="number" name="size_sqft" value="{{ $unit->size_sqft }}" class="w-full border p-2 rounded">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="furnished" value="1" {{ $unit->furnished ? 'checked' : '' }} class="mr-2">
                <span>Is Furnished?</span>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Status</label>
                <select name="status" required class="w-full border p-2 rounded">
                    <option value="available" {{ $unit->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="booked" {{ $unit->status == 'booked' ? 'selected' : '' }}>Booked</option>
                    <option value="unavailable" {{ $unit->status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Update Unit
            </button>
        </form>
    </div>
</x-app-layout>
