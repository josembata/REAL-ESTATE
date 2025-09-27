<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Amenity Categories
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
            @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <!-- Add Category Form -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Add New Category</h3>

            <form action="{{ route('amenity-categories.store') }}" method="POST" class="flex space-x-3">
                @csrf
                <input type="text" name="name" placeholder="Category name"
                       class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300" required>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                    Add
                </button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Categories</h3>

            @if($categories->count())
                <ul class="divide-y">
                    @foreach($categories as $category)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-gray-800">{{ $category->name }}</span>

                            <div class="flex space-x-2">
                                <!-- Edit -->
                                <a href="{{ route('amenity-categories.edit', $category) }}"
                                   class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-yellow-600">
                                    Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('amenity-categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No categories found. Add one above.</p>
            @endif
        </div>
    </div>
</x-app-layout>
