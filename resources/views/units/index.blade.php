<x-app-layout>
     @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
    <div class="max-w-6xl mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Units List</h2>

        <a href="{{ route('units.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Add Unit
        </a>

        <table class="w-full mt-6 border border-gray-200 shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Unit Name</th>
                    <th class="p-3 text-left">Property</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr class="border-t">
                        <td class="p-3">{{ $unit->unit_name }}</td>
                        <td class="p-3">{{ $unit->property->name }}</td>
                        <td class="p-3">{{ ucfirst($unit->unit_type) }}</td>
                        <td class="p-3">{{ $unit->price }} {{ $unit->currency }}</td>
                        <td class="p-3">
                          <span class="px-2 py-1 rounded text-white 
                     {{ $unit->status === 'available' 
                    ? 'bg-green-500' 
                   : ($unit->status === 'booked' 
                     ? 'bg-blue-500' 
                     : ($unit->status === 'unavailable' 
                    ? 'bg-red-600' 
                     : 'bg-gray-500')) }}">
                     {{ ucfirst($unit->status ?? 'N/A') }}
                    </span>

                        </td>
                        <td class="px-4 py-2 border">
                        <!-- <a href="{{ route('units.show', $unit->id) }}" 
                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                         View
                        </a> -->
                        <a href="{{ route('units.edit', $unit->id) }}" 
                         class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                         Edit
                          </a>
                         <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="inline">
                         @csrf
                         @method('DELETE')
                        <button type="submit" 

                        
                         
                         onclick="return confirm('Are you sure you want to delete this unit?')"
                          class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                          Delete
                          </button>
                          </form>
                           <a href="{{ route('units.rooms.index', $unit->id) }}" 
                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                          View Rooms
                          </a>

                           <a href="{{ route('price-plans.index', $unit) }}" 
                          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                          Add Price Plan
                          </a>

    <a href="{{ route('bookings.create', ['unit' => $unit->id]) }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
       Book Now
    </a>

   


                         </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $units->links() }}
        </div>
    </div>
</x-app-layout>
