<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Price Plan Category</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        <form action="{{ route('price_plan_categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
