<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->name }} - Property Details</title>
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
        
        .detail-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 16px;
        }
        
        .property-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .info-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
        }
        
        .back-button {
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .property-image {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .property-image:hover {
            transform: scale(1.02);
        }
        
        .detail-section {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .detail-section:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('properties.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center">
                        <i class="fas fa-home mr-2"></i>
                        Properties
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">{{ $property->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Property Header -->
        <div class="detail-card property-header text-white p-8 mb-8 rounded-xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $property->name }}</h1>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $property->address }}, {{ $property->city }}, {{ $property->region }}</span>
                    </div>
                </div>
                <span class="status-badge mt-4 md:mt-0 {{ $property->status === 'active' ? 'bg-green-500' : ($property->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">
                    {{ ucfirst($property->status) }}
                </span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Property Details -->
            <div class="lg:col-span-2">
                <div class="detail-card bg-white p-8 rounded-xl mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-primary-600"></i>
                        </div>
                        Property Details
                    </h2>
                    
                    <div class="detail-section">
                         {{-- Start Inquiry Button --}}
    @auth
        <a href="{{ route('inquiries.create', $property->id) }}"
           class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            Start Inquiry
        </a>
    @else
        <a href="{{ route('login') }}"
           class="inline-block mt-4 px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700">
            Login to Start Inquiry
        </a>
    @endauth
                        <h3 class="text-lg font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-primary-500 mr-2"></i>
                            Description
                        </h3>
                        <p class="text-gray-600 leading-relaxed">{{ $property->description ?: 'No description provided.' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="info-card bg-gray-50 p-5 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-home text-primary-600"></i>
                                </div>
                                <h3 class="font-medium text-gray-700">Property Type</h3>
                            </div>
                            <p class="text-lg font-semibold text-gray-800">{{ ucfirst($property->type) }}</p>
                        </div>
                        
                        <div class="info-card bg-gray-50 p-5 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-map-marked-alt text-primary-600"></i>
                                </div>
                                <h3 class="font-medium text-gray-700">Location</h3>
                            </div>
                            <p class="text-gray-800">{{ $property->city }}, {{ $property->region }}</p>
                        </div>
                        
                        <div class="info-card bg-gray-50 p-5 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-address-card text-primary-600"></i>
                                </div>
                                <h3 class="font-medium text-gray-700">Address</h3>
                            </div>
                            <p class="text-gray-800">{{ $property->address }}</p>
                        </div>
                        
                        <div class="info-card bg-gray-50 p-5 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-location-arrow text-primary-600"></i>
                                </div>
                                <h3 class="font-medium text-gray-700">Coordinates</h3>
                            </div>
                            <p class="text-gray-800">Lat: {{ $property->latitude }}, Lng: {{ $property->longitude }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Map Section -->
                <div class="detail-card bg-white p-8 rounded-xl">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-map text-primary-600"></i>
                        </div>
                        Property Location
                    </h2>
                    
                    @if($property->latitude && $property->longitude)
                    <div id="property-map" class="h-96 map-container"></div>
                    @else
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No location available for this property.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Right Column - Image and Actions -->
            <div>
                <!-- Property Image -->
                <div class="detail-card bg-white p-6 rounded-xl mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-camera text-primary-600"></i>
                        </div>
                        Cover Image
                    </h2>
                    
                    @if($property->cover_image)
                    <img src="{{ asset($property->cover_image) }}" alt="{{ $property->name }}" class="property-image w-full h-64 object-cover">
                    @else
                    <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-home text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">No image available</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="detail-card bg-white p-6 rounded-xl">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-cog text-primary-600"></i>
                        </div>
                        Actions
                    </h2>
                    
                    <div class="space-y-4">
                        <a href="{{ route('properties.index') }}" class="back-button w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Properties
                        </a>
                        
                        <a href="{{ route('properties.edit', $property->id) }}" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Property
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Script -->
    @if($property->latitude && $property->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('property-map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Custom icon
            var icon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: #2563eb; width: 24px; height: 24px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); position: relative;"><div style="position: absolute; width: 12px; height: 12px; background: white; border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 24]
            });

            L.marker([{{ $property->latitude }}, {{ $property->longitude }}], {icon: icon})
                .addTo(map)
                .bindPopup("<b>{{ $property->name }}</b><br>{{ $property->address }}")
                .openPopup();
        });
    </script>
    @endif
</body>
</html>