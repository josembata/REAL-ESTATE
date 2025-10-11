<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Price Plan for {{ $room->room_name }}</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rooms.price-plans.update', [$room->room_id, $pricePlan->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ $pricePlan->category_id == $category->id ? 'selected' : '' }}>
                            {{ ucfirst($category->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="price" class="block font-semibold mb-2">Price</label>
                <input type="number" name="price" id="price" class="w-full border rounded px-3 py-2" 
                       value="{{ $pricePlan->price }}" step="0.01" min="0" required>
            </div>

            <div class="mb-4">
                <label for="currency" class="block font-semibold mb-2">Currency</label>
                <input type="text" name="currency" id="currency" class="w-full border rounded px-3 py-2" 
                       value="{{ $pricePlan->currency }}" maxlength="3" required>
            </div>

            <div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Update Price Plan
                </button>
                <a href="{{ route('rooms.price-plans.index', $room->room_id) }}" 
                   class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
