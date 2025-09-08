<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">All Properties</h2>
            <a href="{{ route('properties.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                 Add Property
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">#</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Type</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">City</th>
                    <th class="border border-gray-300 px-4 py-2">Region</th>
                    <th class="border border-gray-300 px-4 py-2">Image</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $index => $property)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $property->name }}</td>
                        <td class="border border-gray-300 px-4 py-2 capitalize">{{ $property->type }}</td>
                          <td class="p-3">
                          <span class="px-2 py-1 rounded text-white 
                     {{ $property->status === 'active' 
                    ? 'bg-green-500' 
                   : ($property->status === 'archived' 
                     ? 'bg-blue-500' 
                     : ($property->status === 'pending' 
                    ? 'bg-red-600' 
                     : 'bg-gray-500')) }}">
                     {{ ucfirst($property->status ?? 'N/A') }}
                    </span>

                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $property->city }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $property->region }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if($property->cover_image)
                                <img src="{{ asset($property->cover_image) }}" alt="Cover" class="w-16 h-16 rounded object-cover">
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                            <a href="{{ route('properties.show', $property->id) }}" 
                             class="bg-green-500 text-white px-3 py-1 rounded">
                             View</a>

                            <a href="{{ route('properties.edit', $property) }}" 
                               class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                Edit
                            </a>
                            <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">No properties found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
