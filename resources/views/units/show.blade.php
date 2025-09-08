<x-app-layout>
    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Unit Details</h2>

        <div class="mb-4">
            <strong>Unit Name:</strong> {{ $unit->unit_name }}
        </div>
        <div class="mb-4">
            <strong>Description:</strong> {{ $unit->description }}
        </div>
        <div class="mb-4">
            <strong>Price:</strong> {{ $unit->currency }} {{ number_format($unit->price, 2) }}
        </div>
        <div class="mb-4">
            <strong>Type:</strong> {{ ucfirst($unit->unit_type) }}
        </div>
        <div class="mb-4">
            <strong>Furnishing:</strong> {{ ucfirst($unit->furnishing) }}
        </div>
        <div class="mb-4">
    <strong>Status:</strong> 
   <span class="px-2 py-1 rounded text-white 
    {{ $unit->status === 'available' 
        ? 'bg-green-500' 
        : ($unit->status === 'booked' 
            ? 'bg-blue-500' 
            : ($unit->status === 'unavailable' 
                ? 'bg-red-600' 
                : 'bg-gray-500')) }}">
    {{ ucfirst($unit->status ?? 'N/A') }}
</span>

</div>

        <div class="mb-4">
            <strong>Size:</strong> {{ $unit->size_sqft }} sqft
        </div>
        <div class="mb-4">
            <strong>Furnished:</strong> {{ $unit->furnished ? 'Yes' : 'No' }}
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('units.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to Units
            </a>
            <a href="{{ route('units.edit', $unit->id) }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Edit Unit
            </a>
        </div>
    </div>
</x-app-layout>
