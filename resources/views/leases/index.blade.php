<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lease Agreements
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($leases as $lease)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">
                            Property: {{ $lease->property->name ?? 'Unnamed Property' }}
                        </h3>

                        <p><strong>Tenant:</strong> {{ $lease->user->name ?? 'N/A' }}</p>
                        <p><strong>Start Date:</strong> {{ $lease->term_start }}</p>
                        <p><strong>End Date:</strong> {{ $lease->term_end }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($lease->status) }}</p>
             <div class="mt-2">
            @if ($lease->status === 'generated' || $lease->status === 'active')
    <a href="{{ route('leases.renew.form', $lease->id) }}"
       class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
       >Renew Contract</a>
@endif

                </div>
<div class="mt-4 border-t pt-3">
    <h4 class="font-semibold">Property Owners:</h4>
    <ul class="list-disc pl-6">
      @foreach ($lease->property->ownerships as $ownership)
    @php
        $owner = $ownership->owner;
        $user = optional($owner)->user;
    @endphp
    <li>
        <strong>{{ $user->name ?? 'N/A' }}</strong>
       
        <br>
        <span class="text-gray-600 text-sm">
           
            📧 {{ $user->email ?? 'N/A' }}
        </span><br>
        <span class="text-gray-500 text-sm">
            Ownership Type: {{ $ownership->ownership_type }},
            Share: {{ $ownership->share_percentage ?? 0 }}%,
            Status: {{ ucfirst($ownership->status) }}
        </span>
    </li>
@endforeach

    </ul>
</div>


                        <div class="mt-4">
                            <a href="{{ route('leases.show', $lease->id) }}" 
                               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
