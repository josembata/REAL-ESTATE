<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Price Plans for {{ $room->room_name }}</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('rooms.price-plans.create', $room->room_id) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded">Add Price Plan</a>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mt-2">{{ session('success') }}</div>
        @endif

        <table class="table-auto w-full mt-4 border">
            <thead>
                <tr class="bg-gray-200">
                    <th>Category</th>
                    <th>Price</th>
                    <th>Currency</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pricePlans as $plan)
                    <tr>
                        <td>{{ $plan->category->name ?? 'Unknown' }}</td>
                        <td>{{ $plan->price }}</td>
                        <td>{{ $plan->currency }}</td>
                        <td>
                            <a href="{{ route('rooms.price-plans.edit', [$room->room_id, $plan->id]) }}" 
                               class="text-blue-600">Edit</a>
                            <form method="POST" 
                                  action="{{ route('rooms.price-plans.destroy', [$room->room_id, $plan->id]) }}" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
