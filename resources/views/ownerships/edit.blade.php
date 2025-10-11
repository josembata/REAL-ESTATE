<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Ownership
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('ownerships.update', $ownership) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block font-semibold">Select Property</label>
                        <select name="property_id" class="w-full border-gray-300 rounded-md" required>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ $ownership->property_id == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Select Owner</label>
                        <select name="owner_id" class="w-full border-gray-300 rounded-md" required>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ $ownership->owner_id == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Ownership Type</label>
                        <select name="ownership_type" class="w-full border-gray-300 rounded-md" required>
                            <option value="freehold" {{ $ownership->ownership_type == 'freehold' ? 'selected' : '' }}>Freehold</option>
                            <option value="leasehold" {{ $ownership->ownership_type == 'leasehold' ? 'selected' : '' }}>Leasehold</option>
                            <option value="mortgage" {{ $ownership->ownership_type == 'mortgage' ? 'selected' : '' }}>Mortgage</option>
                            <option value="joint" {{ $ownership->ownership_type == 'joint' ? 'selected' : '' }}>Joint</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Share Percentage</label>
                        <input type="number" step="0.01" name="share_percentage" class="w-full border-gray-300 rounded-md" value="{{ $ownership->share_percentage }}">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('ownerships.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</a>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
