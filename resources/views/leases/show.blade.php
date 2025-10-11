<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl">Lease {{ $lease->lease_number }}</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <p><strong>Status:</strong> {{ ucfirst($lease->status) }}</p>
        <p><strong>Issued:</strong> {{ $lease->created_at }}</p>
        <a href="{{ route('leases.download', $lease->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Download PDF</a>
    </div>
</x-app-layout>
