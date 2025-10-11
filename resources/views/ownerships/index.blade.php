<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ownerships</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex justify-between mb-4">
                
                <!-- <a href="{{ route('owners.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow">Manage Owners</a> -->
            </div>
             <div class="flex justify-between mb-4">
                <h1 class="text-2xl font-bold">Ownerships</h1>
                <a href="{{ route('ownerships.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow">Add Ownership</a>
            </div>

            <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Property</th>
                            <th class="p-2 text-left">Owner</th>
                              <th class="p-2 text-left">Company</th>
                            <th class="p-2">Type</th>
                             <th class="p-2">Share (%)</th>
                            <th class="p-2 text-left">Purchase Date</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Documents</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ownerships as $ownership)
                        <tr class="border-b">
                    <td class="p-2">{{ optional($ownership->property)->name ?? 'N/A' }}</td>
                            <td class="p-2">{{ ($ownership->owner->user)->name ?? 'N/A' }}</td>
                              <td class="p-2">{{ optional($ownership->owner)->company_name ?? 'N/A' }}</td>

                              <td class="p-2">{{ ucfirst($ownership->ownership_type) }}</td>
                              <td class="p-2">{{ $ownership->share_percentage }}</td>
                            <td class="p-2">{{ $ownership->purchase_date }}</td>
                            <td class="p-2 capitalize">{{ $ownership->status }}</td>
                            <td class="p-2">
                                @foreach($ownership->documents as $doc)
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-blue-600 underline">
                                        {{ $doc->document_name }}
                                    </a><br>
                                @endforeach
                            </td>
                            <td class="p-2 flex gap-2">
                                <a href="{{ route('ownerships.edit', $ownership) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md">Edit</a>
                                <form action="{{ route('ownerships.destroy', $ownership) }}" method="POST" onsubmit="return confirm('Delete this ownership?')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded-md">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">No ownerships found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $ownerships->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
