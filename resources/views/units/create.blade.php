<x-app-layout>
    <div class="max-w-3xl mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Add New Unit</h2>

       <form method="POST" action="{{ route('units.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Property</label>
                <select name="property_id" required class="w-full border p-2 rounded">
                    <option value="">Select Property</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Unit Name</label>
                <input type="text" name="unit_name" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border p-2 rounded"></textarea>
            </div>


            {{-- Unit Type --}}
            <div>
                <label class="block text-sm font-medium mb-2">Unit Type</label>
                <select name="unit_type" id="unit_type" required class="w-full border p-2 rounded">
                    <option value="">Select type</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="suite">Suite</option>
                    <option value="office">Office</option>
                    <option value="others">Others</option>
                </select>
            </div>

             <!-- Custom Unit Type -->
            <div id="custom_unit_type_wrapper" class="hidden">
                <label class="block text-sm font-medium mb-2">Enter Others type</label>
                <input type="text" name="custom_unit_type" class="w-full border p-2 rounded">
            </div>

            
            <div id="furnishing_wrapper">
                <label class="block text-sm font-medium mb-2">Furnishing</label>
                <select name="furnishing" class="w-full border p-2 rounded">
                    <option value="unfurnished">Unfurnished</option>
                    <option value="partially_furnished">Partially Furnished</option>
                    <option value="furnished">Furnished</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Size (sqft)</label>
                <input type="number" name="size_sqft" class="w-full border p-2 rounded">
            </div>
           <div>
    <label class="block text-sm font-medium mb-2">Unit Images</label>
    <input type="file" name="images[]" class="w-full border p-2 rounded" multiple>
</div>

            

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Save Unit
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const unitTypeSelect = document.getElementById("unit_type");
            const customUnitTypeWrapper = document.getElementById("custom_unit_type_wrapper");
            const furnishingWrapper = document.getElementById("furnishing_wrapper");

            unitTypeSelect.addEventListener("change", function () {
                if (this.value === "others") {
                    customUnitTypeWrapper.classList.remove("hidden");
                    furnishingWrapper.classList.add("hidden");
                } else {
                    customUnitTypeWrapper.classList.add("hidden");
                    furnishingWrapper.classList.remove("hidden");
                }
            });
        });
    </script>
</x-app-layout>
