<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pending Document Verifications
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Property</th>
                            <th class="p-2 text-left">Owner</th>
                            <th class="p-2 text-left">Document</th>
                            <th class="p-2 text-left">Uploaded At</th>
                            <th class="p-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                        <tr class="border-b">
                            <td class="p-2">{{ $doc->ownership->property->name }}</td>
                            <td class="p-2">{{ optional($doc->ownership->owner->user)->name ?? $doc->ownership->owner->company_name }}</td>
                            <td class="p-2">
                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-blue-600 underline">
                                    {{ $doc->document_name }}
                                </a>
                            </td>
                            <td class="p-2">{{ $doc->created_at->format('Y-m-d') }}</td>
                            <td class="p-2 flex flex-col gap-1">
                                {{-- Verify Form --}}
                                <form action="{{ route('admin.documents.verify', $doc) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md w-full">
                                        Verify & Notify
                                    </button>
                                </form>

                                {{-- Reject Form --}}
                                <form action="{{ route('admin.documents.verify', $doc) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md w-full">
                                        Reject & Notify
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">No pending documents.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
