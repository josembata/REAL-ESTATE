<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Room Details: {{ $room->room_name }} ({{ $unit->unit_name }})
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto bg-white shadow rounded p-6">
        <div class="mb-4">
            <strong>Room Name:</strong> {{ $room->room_name }}
        </div>

        <div class="mb-4">
            <strong>Room Type:</strong> {{ ucfirst($room->room_type) }}
        </div>

        <div class="mb-4">
            <strong>Size (sqft):</strong> {{ $room->size_sqft ?? 'N/A' }}
        </div>

      

        <div class="mb-4">
            <strong>Status:</strong> {{ ucfirst($room->availability_status) }}
        </div>
        <h3 class="text-lg font-bold mt-4">Room Images</h3>
<div class="grid grid-cols-3 gap-4 mt-2">
    @forelse($room->images as $image)
        <img src="{{ asset('storage/' . $image->image_path) }}" class="rounded shadow">
    @empty
        <p>No images uploaded.</p>
    @endforelse
</div>


        <div class="flex mt-6">
            <a href="{{ route('units.rooms.index', $unit) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded mr-2">
               Back to Rooms
            </a>

            <a href="{{ route('units.rooms.edit', [$unit, $room]) }}" 
               class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">
               Edit Room
            </a>
        </div>
    </div>
</x-app-layout>
