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
           class="bg-blue-600 text-white px-4 py-2 rounded">Add Room</a>

        <table class="table-auto w-full mt-4 border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Name</th>
                    <th>Type</th>
                    <th>Size (sqft)</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    <td class="border px-4 py-2">{{ $room->room_name }}</td>
                    <td>{{ $room->room_type }}</td>
                    <td>{{ $room->size_sqft }}</td>
                    <td>{{ $room->price }}</td>
                    <td>{{ $room->availability_status }}</td>
                    <td class="space-x-2">
                        <a href="{{ route('units.rooms.edit', [$unit->id, $room->room_id]) }}" 
                           class="text-blue-600">Edit</a>

                        <a href="{{ route('units.rooms.show', [$unit, $room]) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded">
                           View
                        </a>

                        <a href="{{ route('rooms.price-plans.create', $room->room_id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                           Add Price Plan
                        </a>

                        <a href="{{ route('rooms.book.create', [$unit->id, $room->room_id]) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">
                      Book Now
                       </a>


                        <form method="POST" 
                              action="{{ route('units.rooms.destroy', [$unit->id, $room->room_id]) }}" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2"
                                onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
