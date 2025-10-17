<x-app-layout>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl">Rooms in {{ $unit->unit_name }}</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('units.rooms.create', $unit->id) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded mb-6 inline-block">Add Room</a>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach($rooms as $room)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border hover:shadow-xl transition">
                    <!-- Room Image -->
                    @php
                        $firstImage = $room->images->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->image_path) }}" 
                             alt="{{ $room->room_name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <img src="{{ asset('default-room.jpg') }}" 
                             alt="Default Room" 
                             class="w-full h-48 object-cover">
                    @endif

                    <!-- Room Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $room->room_name }}</h3>
                        <p class="text-sm text-gray-500 mb-1"><strong>Type:</strong> {{ $room->room_type }}</p>
                        <p class="text-sm text-gray-500 mb-1"><strong>Size:</strong> {{ $room->size_sqft }} sqft</p>
                        <p class="text-sm">
                            <strong>Status:</strong> 
                            <span class="px-2 py-1 rounded text-blue text-xs 
                                {{ $room->availability_status == 'available' ? 'bg-blue-500' : 'bg-red-500' }}">
                                {{ $room->availability_status }}
                            </span>
                        </p>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('units.rooms.show', [$unit, $room]) }}" 
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                View
                            </a>

                            <a href="{{ route('units.rooms.edit', [$unit->id, $room->room_id]) }}" 
                               class="bg-green-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </a>

                            <a href="{{ route('rooms.price-plans.index', $room->room_id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Price Plans
                            </a>

                            <a href="{{ route('rooms.book.create', [$unit->id, $room->room_id]) }}" 
                               class="bg-blue-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">
                                Book Now
                            </a>

                            <form method="POST" 
                                  action="{{ route('units.rooms.destroy', [$unit->id, $room->room_id]) }}" 
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Rooms Message -->
        @if($rooms->isEmpty())
            <p class="text-gray-500 mt-6">No rooms available for this unit yet.</p>
        @endif
    </div>
</x-app-layout>
