<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Assign Amenities to Property: {{ $property->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Assign Amenities Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('properties.assignamenities', $property->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    @foreach($categories as $category)
                        <div>
                          

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 ml-2">
                                @foreach($category->amenities as $amenity)
                                    <label class="flex items-center space-x-2 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                               {{ $property->amenities->contains($amenity->id) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        @if($amenity->icon)
                                            <img src="{{ asset('storage/'.$amenity->icon) }}" alt="{{ $amenity->name }}"
                                                 class="w-6 h-6 object-contain">
                                        @endif
                                        <span class="text-gray-800">{{ $amenity->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 transition">
                        Save Amenities
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
