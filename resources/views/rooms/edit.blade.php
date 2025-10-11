<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Room: {{ $room->room_name }} ({{ $unit->unit_name }})</h2>
    </x-slot>

    <div class="p-6">
       <form method="POST" action="{{ route('units.rooms.update', ['unit' => $unit, 'room' => $room]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Room Name</label>
                <input type="text" name="room_name" 
                       value="{{ old('room_name', $room->room_name) }}" 
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label>Room Type</label>
                <select name="room_type" class="w-full border rounded px-3 py-2" required>
                    <option value="bedroom" {{ $room->room_type == 'bedroom' ? 'selected' : '' }}>Bedroom</option>
                    <option value="bathroom" {{ $room->room_type == 'bathroom' ? 'selected' : '' }}>Bathroom</option>
                    <option value="office" {{ $room->room_type == 'office' ? 'selected' : '' }}>Office</option>
                    <option value="shop" {{ $room->room_type == 'shop' ? 'selected' : '' }}>Shop</option>
                    <option value="warehouse" {{ $room->room_type == 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                    <option value="other" {{ $room->room_type == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Size (sqft)</label>
                <input type="number" step="0.01" name="size_sqft" 
                       value="{{ old('size_sqft', $room->size_sqft) }}" 
                       class="w-full border rounded px-3 py-2">
            </div>

           

            <div class="mb-4">
                <label>Status</label>
                <select name="availability_status" class="w-full border rounded px-3 py-2">
                    <option value="available" {{ $room->availability_status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ $room->availability_status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="reserved" {{ $room->availability_status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('units.rooms.index', $unit->id) }}" 
               class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>
