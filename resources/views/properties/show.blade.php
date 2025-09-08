<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ $property->name }}</h1>

        <div class="mb-4">
            <strong>Description:</strong>
            <p>{{ $property->description }}</p>
        </div>

        <div class="mb-4">
            <strong>Type:</strong> {{ ucfirst($property->type) }}
        </div>

        <div class="mb-4">
            <strong>Status:</strong>
            <span class="px-2 py-1 rounded text-white
                {{ $property->status === 'active' ? 'bg-green-500' : ($property->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">
                {{ ucfirst($property->status) }}
            </span>
        </div>

        <div class="mb-4">
            <strong>City:</strong> {{ $property->city }}
        </div>

        <div class="mb-4">
            <strong>Region:</strong> {{ $property->region }}
        </div>

        <div class="mb-4">
            <strong>Address:</strong> {{ $property->address }}
        </div>

        <div class="mb-4">
            <strong>Location:</strong>
            Lat: {{ $property->latitude }} | Lng: {{ $property->longitude }}
        </div>
        
        <!-- code to display map -->
         <div class="mb-6">
        <strong>Property Location:</strong>
        @if($property->latitude && $property->longitude)
        <div id="property-map" style="height: 400px;" class="mt-3 rounded shadow"></div>
        @else
         <p>No location available for this property.</p>
        @endif
        </div>


        <div class="mb-4">
            <strong>Cover Image:</strong><br>
                @if($property->cover_image)
                 <img src="{{ asset($property->cover_image) }}" alt="Cover" class="w-30 h-30 rounded object-cover">
                 @else
                  <span class="text-gray-500">No Image</span>
                @endif
        </div>

        <div class="flex space-x-4 mt-6">
            <a href="{{ route('properties.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
            <a href="{{ route('properties.edit', $property->id) }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
        </div>
    </div>


  <!-- scripts support map display -->
    @if($property->latitude && $property->longitude)
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var map = L.map('property-map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([{{ $property->latitude }}, {{ $property->longitude }}])
            .addTo(map)
            .bindPopup("<b>{{ $property->name }}</b><br>{{ $property->address }}")
            .openPopup();
    });
</script>
@endif

</x-app-layout>
