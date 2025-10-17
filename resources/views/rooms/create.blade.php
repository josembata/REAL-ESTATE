<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add New Room</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <form action="{{ route('units.rooms.store', $unit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="block">Room Name</label>
                <input type="text" name="room_name" class="border p-2 w-full" required>
            </div>

            <div class="mb-3">
                <label class="block">Description</label>
                <textarea name="description" class="border p-2 w-full"></textarea>
            </div>

            <div class="mb-4">
                <label>Room Type</label>
                <select name="room_type" class="w-full border rounded px-3 py-2" required>
                    <option value="bedroom" >Bedroom</option>
                    <option value="bathroom" >Bathroom</option>
                    <option value="office">Office</option>
                    <option value="shop" >Shop</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="other" >Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Size (sqft)</label>
                <input type="number" step="0.01" name="size_sqft" 
                       class="w-full border rounded px-3 py-2">
            </div>
          

            <div class="mb-3">
                <label class="block">Availability Status</label>
                <select name="availability_status" class="border p-2 w-full">
                    <option value="available">Available</option>
                    <option value="reserved">Reserved</option>
                    <option value="occupied">Occupied</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block">Upload Room Images</label>
                <input type="file" name="images[]" multiple class="border p-2 w-full">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Room
            </button>
        </form>
    </div>
</x-app-layout>
