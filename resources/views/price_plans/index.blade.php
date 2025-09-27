<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Price Plans for {{ $unit->unit_name }}</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('price-plans.create', $unit) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add New Plan</a>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mt-4">{{ session('success') }}</div>
        @endif

        <table class="w-full mt-4 border">
            <thead>
                <tr>
                    <th class="border px-2 py-1">Name</th>
                    <th class="border px-2 py-1">Price</th>
                    <th class="border px-2 py-1">Currency</th>
                    <th class="border px-2 py-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                    <tr>
                        <td class="border px-2 py-1">{{ $plan->name }}</td>
                        <td class="border px-2 py-1">{{ $plan->price }}</td>
                        <td class="border px-2 py-1">{{ $plan->currency }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('price-plans.edit', [$unit, $plan]) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('price-plans.destroy', [$unit, $plan]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600" onclick="return confirm('Delete this plan?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
