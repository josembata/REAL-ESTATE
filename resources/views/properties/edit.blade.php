<x-app-layout>
    <div class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4">Edit Property</h2>

        <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', $property->name) }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description', $property->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Type</label>
                <select name="type" class="w-full border rounded p-2" required>
                    <option value="house" {{ $property->type == 'house' ? 'selected' : '' }}>House</option>
                    <option value="apartment" {{ $property->type == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="land" {{ $property->type == 'land' ? 'selected' : '' }}>Land</option>
                    <option value="office" {{ $property->type == 'office' ? 'selected' : '' }}>Office</option>
                </select>
            </div>
                                  <div class="mb-4">
    <label for="agent_id" class="block text-sm font-medium text-gray-700">Assign Agent</label>
   <select name="agent_user_id" id="agent_user_id" class="border rounded-md p-2 w-full">
        <option value="">-- Select Agent --</option>
        @foreach($agents as $agent)
            <option value="{{ $agent->id }}"
                {{ (isset($property) && $property->agent_id == $agent->id) ? 'selected' : '' }}>
                {{ $agent->name }} ({{ $agent->email }})
            </option>
        @endforeach
    </select>
</div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2" required>
                    <option value="active" {{ $property->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ $property->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="archived" {{ $property->status == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

                 <div class="mb-4">
    <label class="block text-sm font-medium">Region</label>
    <select name="region" id="region" class="w-full border rounded p-2" required>
        <option value="">Select Region</option>
        @php
            $regions = [
                'Dar es Salaam' => ['Ilala','Kinondoni','Temeke'],
                'Dodoma' => ['Dodoma Urban','Chamwino'],
                'Arusha' => ['Arusha Urban','Meru'],
                'Mwanza' => ['Nyamagana','Ilemela'],
                'Mbeya' => ['Mbeya Urban','Rungwe'],
                'Kilimanjaro' => ['Moshi','Moshi Rural'],
                
            ];
        @endphp

        @foreach($regions as $regionName => $citiesArray)
            <option value="{{ $regionName }}" 
                {{ old('region', $property->region ?? '') == $regionName ? 'selected' : '' }}>
                {{ $regionName }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">City</label>
    <select name="city" id="city" class="w-full border rounded p-2" required>
        <option value="">Select City</option>
        @if(old('region', $property->region ?? null))
            @foreach($regions[old('region', $property->region)] as $city)
                <option value="{{ $city }}" {{ old('city', $property->city ?? '') == $city ? 'selected' : '' }}>
                    {{ $city }}
                </option>
            @endforeach
        @endif
    </select>
</div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Address</label>
                <input type="text" name="address" value="{{ old('address', $property->address) }}" class="w-full border rounded p-2" required>
            </div>

                     <div class="mb-6">
    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
    <input type="text" 
           id="latitude" 
           name="latitude" 
           value="{{ old('latitude', $property->latitude ?? '') }}" 
           class="w-full px-4 py-2 border rounded-md" 
           readonly>
</div>

<div class="mb-6">
    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
    <input type="text" 
           id="longitude" 
           name="longitude" 
           value="{{ old('longitude', $property->longitude ?? '') }}" 
           class="w-full px-4 py-2 border rounded-md" 
           readonly>
</div>

<div class="mb-6">
    <strong>Select Location on Map:</strong>
    <div id="map" style="height: 400px;" class="mt-3 rounded shadow"></div>
</div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Cover Image</label>
                <input type="file" name="cover_image" class="w-full border rounded p-2">
                @if($property->cover_image)
                    <div class="mt-2">
                        <img src="{{ asset($property->cover_image) }}" alt="Cover" class="w-24 h-24 rounded object-cover">
                    </div>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Update Property
            </button>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Default location Dar es Salaam 
        var defaultLat = {{ old('latitude', $property->latitude ?? -6.7924) }};
        var defaultLng = {{ old('longitude', $property->longitude ?? 39.2083) }};

        var map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        // Update inputs when marker is dragged
        marker.on("dragend", function (e) {
            var lat = marker.getLatLng().lat.toFixed(6);
            var lng = marker.getLatLng().lng.toFixed(6);
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        });

        // Update inputs when clicking on map
        map.on("click", function (e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);
            marker.setLatLng([lat, lng]);
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        });
    });



    
// scripts for region and city 

document.addEventListener("DOMContentLoaded", function() {
    const regions = @json($regions); // Pass PHP array to JS
    const regionSelect = document.getElementById('region');
    const citySelect = document.getElementById('city');

    regionSelect.addEventListener('change', function() {
        const selectedRegion = this.value;

        // Clear current city options
        citySelect.innerHTML = '<option value="">Select City</option>';

        if (selectedRegion && regions[selectedRegion]) {
            regions[selectedRegion].forEach(function(city) {
                const option = document.createElement('option');
                option.value = city;
                option.text = city;
                citySelect.appendChild(option);
            });
        }
    });
});
</script>

</x-app-layout>
