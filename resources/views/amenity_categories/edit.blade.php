<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Edit Amenity Category
            </h2>
            <a href="{{ route('amenity-categories.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Update Category</h3>

            <form action="{{ route('amenity-categories.update', $category) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div>
                    <label class="block text-gray-600 mb-1">Category Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300" required>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('amenity-categories.index') }}"
                       class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
