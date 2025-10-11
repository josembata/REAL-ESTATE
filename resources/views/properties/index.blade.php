<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Property<span class="text-primary-600">Manager</span>
            </h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search properties..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                @can('create-properties')
                <a href="{{ route('properties.create') }}"
                   class="bg-blue-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add Property</span>
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card p-6 shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">Total Properties</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $properties->count() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-home text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Active Listings</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $properties->where('status', 'active')->count() }}
                    </h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $properties->where('status', 'pending')->count() }}
                    </h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Archived</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $properties->where('status', 'archived')->count() }}
                    </h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-archive text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="text-xl font-semibold text-gray-900">Property</h2>

            <div class="flex flex-wrap gap-3">
                <select class="px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option>All Types</option>
                    <option>House</option>
                    <option>Apartment</option>
                    <option>Land</option>
                    <option>Office</option>
                </select>

                <select class="px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option>All Statuses</option>
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Archived</option>
                </select>

                <button
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                    <i class="fas fa-filter text-gray-500"></i>
                    <span>More Filters</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($properties as $property)
            <div class="property-card bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="relative">
                    @if($property->cover_image)
                        <img src="{{ asset($property->cover_image) }}" alt="{{ $property->name }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-home text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <div >
                        @if($property->status === 'active')
                            <span class="status-badge bg-green-500 text-white">Active</span>
                        @elseif($property->status === 'pending')
                            <span class="status-badge bg-red-600 text-white">Pending</span>
                        @elseif($property->status === 'archived')
                            <span class="status-badge bg-blue-500 text-white">Archived</span>
                        @else
                            <span class="status-badge bg-gray-500 text-white">{{ ucfirst($property->status) }}</span>
                        @endif
                    </div>

                </div>

                <div class="p-5">
                    <h3 class="font-semibold text-lg text-gray-900">{{ $property->name }}</h3>
                    <p class="text-gray-500 mb-4">
                        <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                        {{ $property->city }}, {{ $property->region }}
                    </p>
                  <p class="text-gray-500 mb-4">
    <i class="fas fa-user-tie text-primary-500 mr-2"></i>Agent:
    {{ optional($property->agent)->name ?? 'Not Assigned' }}
</p>


                    <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('properties.show', $property->id) }}"
                                class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded">
                                <i class="fas fa-eye">show</i>
                            </a>
                            @can('edit-properties')
                            <a href="{{ route('properties.edit', $property->id) }}"
                               class="action-btn bg-blue-50 text-blue-600 p-2 rounded-lg">
                                <i class="fas fa-edit">edit</i>
                            </a>
                            @endcan

                              @auth
                        <a href="{{ route('inquiries.create', $property->id) }}"
                           class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Start Inquiry
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            Login to Inquire
                        </a>
                    @endauth
                             <!-- Assign Amenities -->
        <a href="{{ route('properties.assignamenities', $property->id) }}"
           class="action-btn bg-green-50 text-green-600 p-2 rounded-lg"
           title="Assign Amenities">
            <i class="fas fa-plus-circle">Assign Amenities</i>
        </a>
          
     <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="inline-block">
    @csrf
    @method('DELETE')
    @can('delete-properties')
    <button type="submit"
        onclick="return confirm('Are you sure you want to delete this property?')"
        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-blue-700">
        <i class="fas fa-trash"></i> Delete
    </button>
    @endcan
</form>

                        </div>
                        <span class="text-xs text-gray-400 time-ago"
                              data-created-at="{{ $property->created_at }}"></span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-home text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500">No properties found</h3>
                <p class="text-gray-400 mt-2">Get started by adding your first property</p>
                <a href="{{ route('properties.create') }}"
                   class="inline-block mt-4 bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
                    Add Property
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($properties->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6 flex justify-between items-center">
            <p class="text-gray-500">
                Showing <span class="font-medium">{{ $properties->count() }}</span> properties
            </p>
            <div class="flex space-x-2">
                <a href="#" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="#" class="px-3 py-1 rounded-lg bg-primary-600 text-white">1</a>
                <a href="#" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">2</a>
                <a href="#" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">3</a>
                <a href="#" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const icon = this.querySelector('i');
                        icon.classList.toggle('far');
                        icon.classList.toggle('fas');
                        icon.classList.toggle('text-red-500');
                    });
                });
            });

            function confirmDelete(propertyId) {
                if (confirm('Are you sure you want to delete this property?')) {
                    const form = document.querySelector(`form[action*="/properties/${propertyId}"]`);
                    if (form) form.submit();
                }
            }

            function timeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);

                let interval = seconds / 31536000;
                if (interval > 1) return Math.floor(interval) + " years ago";
                interval = seconds / 2592000;
                if (interval > 1) return Math.floor(interval) + " months ago";
                interval = seconds / 86400;
                if (interval > 1) return Math.floor(interval) + " days ago";
                interval = seconds / 3600;
                if (interval > 1) return Math.floor(interval) + " hours ago";
                interval = seconds / 60;
                if (interval > 1) return Math.floor(interval) + " minutes ago";
                return Math.floor(seconds) + " seconds ago";
            }

            function updateTimeAgo() {
                document.querySelectorAll('.time-ago').forEach(el => {
                    const createdAt = el.getAttribute('data-created-at');
                    el.textContent = timeAgo(createdAt);
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                updateTimeAgo();
                setInterval(updateTimeAgo, 60000);
            });
        </script>
    @endpush
</x-app-layout>
