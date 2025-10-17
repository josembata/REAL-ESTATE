<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Property - RealEstate Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
        }
        
        .form-card {
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .location-pin {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .step-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
        }
        
        .step-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            transform: translateY(-50%);
            z-index: 1;
        }
        
        .step {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #9ca3af;
            position: relative;
            z-index: 2;
        }
        
        .step.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .step-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 8px;
            font-size: 0.75rem;
            color: #6b7280;
            white-space: nowrap;
        }
        
        .step.active .step-label {
            color: #3b82f6;
            font-weight: 500;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Add New Property</h1>
            <p class="text-gray-600">Fill in the details to list your property on our platform</p>
        </div>
        
        <!-- Progress Steps -->
      

        <!-- Form Card -->
        <div class="form-card bg-white rounded-xl p-8">
            <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-home text-primary-600"></i>
                            </div>
                            Property Information
                        </h2>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Name</label>
                            <div class="relative">
                                <input type="text" name="name" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" required placeholder="Enter property name">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" rows="4" placeholder="Describe your property"></textarea>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title Deed Number</label>
                            <div class="relative">
                                <input type="text" name="title_deed_number" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Enter title deed number">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-file-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                            <div class="relative">
                                <select name="type" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none" required>
                                    <option value="" disabled selected>Select property type</option>
                                    <option value="house">House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="land">Land</option>
                                    <option value="office">Office</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="border rounded-md p-2 w-full">
                                <option value="for rent">For Rent</option>
                                <option value="for sale">For Sale</option>
                            </select>
                        </div>

     <div class="mb-4">

<div>
    <label for="agent_user_id" class="block text-sm font-medium text-gray-700">Assign Agent</label>
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



</div>

                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">Click to upload or drag and drop</p>
                                    </div>
                                    <input type="file" name="cover_image" class="hidden" />
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-map-marker-alt text-primary-600"></i>
                            </div>
                            Location Details
                        </h2>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                            <div class="relative">
                                <select name="region" id="region" class="input-field state w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none" required>
                                    <option value="" selected>Select Region</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-globe-africa text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <div class="relative">
                                <select name="city" id="city" class="input-field city w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none" required>
                                    <option value="" selected>Select City</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-city text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <div class="relative">
                                <input type="text" name="address" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" required placeholder="Enter full address">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-map-pin text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                                <div class="relative">
                                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $property->latitude ?? '') }}" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fas fa-location-arrow text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                                <div class="relative">
                                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $property->longitude ?? '') }}" class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fas fa-location-arrow text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Location on Map</label>
                            <div id="map" class="h-64 rounded-lg shadow-md"></div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <i class="fas fa-info-circle text-primary-500 mr-1"></i>
                                Click on the map or select a city to set the location
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const config = {
                cUrl: 'https://api.countrystatecity.in/v1/countries',
                ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
            };

            const countryCode = "TZ"; // Tanzania
            const regionSelect = document.getElementById("region");
            const citySelect   = document.getElementById("city");

            // Init Map 
            var map = L.map('map').setView([-6.7924, 39.2083], 7); // Default Dar es Salaam
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Custom icon
            var icon = L.divIcon({
                className: 'location-pin',
                html: '<div style="background-color: #2563eb; width: 24px; height: 24px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); position: relative;"><div style="position: absolute; width: 12px; height: 12px; background: white; border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 24]
            });

            var marker = L.marker([-6.7924, 39.2083], {icon: icon, draggable: true}).addTo(map);

            function updateLatLng(lat, lng) {
                document.getElementById("latitude").value = lat.toFixed(6);
                document.getElementById("longitude").value = lng.toFixed(6);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 12);
            }

            // Update when marker dragged
            marker.on("dragend", function () {
                let lat = marker.getLatLng().lat;
                let lng = marker.getLatLng().lng;
                updateLatLng(lat, lng);
            });

            // Update when clicking map
            map.on("click", function (e) {
                updateLatLng(e.latlng.lat, e.latlng.lng);
            });

            //  Load Regions 
            fetch(`${config.cUrl}/${countryCode}/states`, {
                headers: {"X-CSCAPI-KEY": config.ckey}
            })
            .then(res => res.json())
            .then(data => {
                regionSelect.innerHTML = '<option value="">Select Region</option>';
                data.forEach(region => {
                    const option = document.createElement("option");
                    option.value = region.name;
                    option.textContent = region.name;
                    option.dataset.iso2 = region.iso2;
                    regionSelect.appendChild(option);
                });
            });

            //  When Region Selected 
            regionSelect.addEventListener("change", function () {
                let selectedOption = this.options[this.selectedIndex];
                let regionIso2 = selectedOption?.dataset?.iso2;
                if (!regionIso2) return;

                // Load Cities
                fetch(`${config.cUrl}/${countryCode}/states/${regionIso2}/cities`, {
                    headers: {"X-CSCAPI-KEY": config.ckey}
                })
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.forEach(city => {
                        const option = document.createElement("option");
                        option.value = city.name;
                        option.textContent = city.name;
                        option.dataset.lat = city.latitude;
                        option.dataset.lng = city.longitude;
                        citySelect.appendChild(option);
                    });
                });
            });

            // When City Selected 
            citySelect.addEventListener("change", function () {
                let selectedOption = this.options[this.selectedIndex];
                let lat = parseFloat(selectedOption?.dataset?.lat);
                let lng = parseFloat(selectedOption?.dataset?.lng);

                if (!isNaN(lat) && !isNaN(lng)) {
                    updateLatLng(lat, lng);
                }
            });
        });
    </script>
</body>
</html>