<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Category</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        <form action="{{ route('price_plan_categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" value="{{ $category->name }}" class="w-full border px-3 py-2 rounded" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>
