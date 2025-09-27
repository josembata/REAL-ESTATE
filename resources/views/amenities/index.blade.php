<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Amenities
            </h2>
            <!-- Category Management Button -->
            <a href="{{ route('amenity-categories.index') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                Manage Categories
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
            @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <!-- Add Amenity Form -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Add New Amenity</h3>
            <form action="{{ route('amenities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-600">Amenity Name</label>
                    <input type="text" name="name" placeholder="Amenity name"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300" required>
                </div>

                <div>
                    <label class="block text-gray-600">Category</label>
                    <select name="category_id"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600">Icon / Image</label>
                    <input type="file" name="icon"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-indigo-300">
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    Add Amenity
                </button>
            </form>
        </div>

        <!-- Amenities List -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Available Amenities</h3>
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-2 border">Icon</th>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Category</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($amenities as $amenity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">
                                @if($amenity->icon)
                                    <img src="{{ asset('storage/'.$amenity->icon) }}" alt="icon" class="w-8 h-8 object-contain">
                                @else
                                    <span class="text-gray-400">No Icon</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $amenity->name }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                    {{ $amenity->category->name }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border flex space-x-2">
                                <a href="{{ route('amenities.edit', $amenity) }}"
                                   class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-yellow-600">
                                    Edit
                                </a>
                                <form action="{{ route('amenities.destroy', $amenity) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this amenity?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($amenities->isEmpty())
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                No amenities found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
