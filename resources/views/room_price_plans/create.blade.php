<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Price Plan for {{ $room->room_name }}</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('rooms.price-plans.store', $room->room_id) }}">
            @csrf

            <div class="mb-4">
                <label>Plan Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ ucfirst($cat->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label>Currency</label>
                <input type="text" name="currency" value="USD" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Plan
            </button>
        </form>
    </div>
</x-app-layout>
