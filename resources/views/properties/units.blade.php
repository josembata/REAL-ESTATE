<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Units for Property: {{ $property->name }}
        </h2>

         <div class="max-w-6xl mx-auto mt-8">
      

        <a href="{{ route('units.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Add Unit
        </a>
    </div>
    </x-slot>

    <div class="py-8">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if ($units->isEmpty())
                <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500">
                    No units found for this property.
                </div>
            @else
                {{-- 4 cards per row on large screens --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($units as $unit)
                        <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            
                            {{-- Unit Image --}}
                            <div class="h-36 w-full bg-gray-200">
                                @if ($unit->unitImages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $unit->unitImages->first()->image_path) }}" 
                                         alt="{{ $unit->unit_name }}" 
                                         class="h-36 w-full object-cover">
                                @else
                                    <img src="{{ asset('images/default-unit.jpg') }}" 
                                         alt="Default Image" 
                                         class="h-36 w-full object-cover">
                                @endif
                            </div>

                            {{-- Unit Details --}}
                            <div class="p-3">
                                <h3 class="text-base font-semibold text-gray-800 mb-1">
                                    {{ $unit->unit_name ?? 'N/A' }}
                                </h3>
                                <p class="text-xs text-gray-500 mb-2">
                                    Type: <span class="font-medium text-gray-700">{{ ucfirst($unit->unit_type ?? 'N/A') }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mb-2">
                                    {{ Str::limit($unit->description ?? 'No description available.', 60) }}
                                </p>

                                {{-- Status Badge --}}
                                <span class="inline-block px-2 py-1 text-[10px] font-semibold rounded-full 
                                    {{ $unit->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($unit->status) }}
                                </span>

                                {{-- Actions --}}
                                <div class="mt-3 flex justify-between items-center">
                                     <a href="{{ route('units.rooms.index', $unit->id) }}" 
                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                           Rooms
                          </a>
                          <a href="{{ route('price-plans.index', $unit) }}" 
                          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                           Plan
                          </a>
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

                                   <a href="{{ route('bookings.create', ['unit' => $unit->id]) }}" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                 Book Now
                                  </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
