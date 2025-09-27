<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Price Plan for {{ $unit->unit_name }}</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('price-plans.update', [$unit, $pricePlan]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required
                       value="{{ old('name', $pricePlan->name) }}">
            </div>

            <div class="mb-4">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" required
                       value="{{ old('price', $pricePlan->price) }}">
            </div>

            <div class="mb-4">
                <label>Currency</label>
                <input type="text" name="currency" class="w-full border rounded px-3 py-2" required
                       value="{{ old('currency', $pricePlan->currency) }}">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('price-plans.index', $unit) }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</x-app-layout>
