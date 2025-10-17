<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Renew Lease
        </h2>
    </x-slot>

    <div class="py-10 max-w-3xl mx-auto">
        <div class="bg-white p-6 shadow rounded-lg">
            <h3 class="text-lg font-semibold mb-4">
                Renew Lease for: {{ $lease->property->name ?? 'Unit' }}
            </h3>

            <div class="mb-4">
                <p class="text-gray-600">
                    <strong>Current Term:</strong>
                    {{ \Carbon\Carbon::parse($lease->term_start)->toFormattedDateString() }}
                    —
                    {{ \Carbon\Carbon::parse($lease->term_end)->toFormattedDateString() }}
                </p>
                <p class="text-gray-600">
                    <strong>Status:</strong>
                    <span class="capitalize">{{ $lease->status }}</span>
                </p>
            </div>
            
 <div class="mb-6">
          @if($lease->status === 'generated' && now()->diffInDays($lease->term_end, false) <= 7)
<form action="{{ route('leases.renew', $lease->id) }}" method="POST">
    @csrf
    <label for="new_term_end" class="block mb-2">Select New Term End:</label>
    <input type="date" name="new_term_end" id="new_term_end" class="border p-2 rounded" required>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-3">
        Request Renewal
    </button>
</form>
@endif
        </div>
            <div class="mt-6">
                <a href="{{ route('leases.index') }}" class="text-blue-600 hover:underline">
                    &larr; Back to Leases
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
