<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Create Amenity categories
        </h2>
    </x-slot>
<div class="p-6 bg-white shadow rounded">
    <form action="{{ route('amenity-categories.store') }}" method="POST">
    @csrf
   <div class="mb-3">
                <label class="block">Category Name</label>
                <input type="text" name="name" class="border p-2 w-full" required>
            </div>
     <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Add category
            </button>
</form>
<div>

</x-app-layout>
