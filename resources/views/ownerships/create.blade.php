<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($ownership) ? 'Edit Ownership' : 'Add Ownership' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ isset($ownership) ? route('ownerships.update', $ownership) : route('ownerships.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($ownership)) @method('PUT') @endif

                  <!-- Owner -->
<div class="mb-4">
    <label class="block font-semibold">Owner</label>
    <select name="owner_id" class="w-full border-gray-300 rounded-md" required>
        <option value="">-- Select Owner --</option>
        @foreach($landlords as $landlord)
            <option value="{{ $landlord->id }}"
                {{ (isset($ownership) && $ownership->owner_id == $landlord->id) ? 'selected' : (old('owner_id') == $landlord->id ? 'selected' : '') }}>
                {{ $landlord->user->name ?? 'No User' }} 
                @if($landlord->company_name)
                    - {{ $landlord->company_name }}
                @endif
            </option>
        @endforeach
    </select>
</div>


                    <!-- Property -->
                    <div class="mb-4">
                        <label class="block font-semibold">Property</label>
                        <select name="property_id" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Property --</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" 
                                    {{ (isset($ownership) && $ownership->property_id == $property->id) ? 'selected' : old('property_id') }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                      <div class="mb-4">
        <label class="block">Ownership Type</label>
        <select name="ownership_type" class="w-full border p-2" required>
            <option value="freehold">Freehold</option>
            <option value="leasehold">Leasehold</option>
            <option value="mortgage">Mortgage</option>
            <option value="joint">Joint</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block">Share Percentage</label>
        <input type="number" step="0.01" name="share_percentage" class="w-full border p-2" value="100">
    </div>

                    <!-- Purchase Date -->
                    <div class="mb-4">
                        <label class="block font-semibold">Purchase Date</label>
                        <input type="date" name="purchase_date" class="w-full border-gray-300 rounded-md"
                            value="{{ isset($ownership) ? $ownership->purchase_date : old('purchase_date') }}">
                    </div>

                    <!-- Documents Upload -->
                    <div class="mb-4">
                        <label class="block font-semibold">Upload Documents</label>
                        <input type="file" name="documents[]" multiple class="w-full border-gray-300 rounded-md">
                        @if(isset($ownership) && $ownership->documents->count())
                            <div class="mt-2">
                                <p class="font-semibold">Existing Documents:</p>
                                <ul class="list-disc ml-5">
                                    @foreach($ownership->documents as $doc)
                                        <li class="flex justify-between items-center mb-1">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 underline">
                                                {{ $doc->document_name }}
                                            </a>
                                            <form action="{{ route('ownerships.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Delete this document?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600">Delete</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Verification Status (for Edit only) -->
                    @if(isset($ownership))
                    <div class="mb-4">
                        <label class="block font-semibold">Verification Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md" required>
                            <option value="pending" {{ $ownership->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $ownership->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ $ownership->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full border-gray-300 rounded-md">{{ $ownership->remarks }}</textarea>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('ownerships.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</a>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">
                            {{ isset($ownership) ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
