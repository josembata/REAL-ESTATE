<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Create Amenity
        </h2>
    </x-slot>

   <form action="{{ route('amenities.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Amenity Name</label>
    <input type="text" name="name" required>

    <label>Category</label>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label>Icon/Image</label>
    <input type="file" name="icon">

    <button type="submit">Add Amenity</button>
</form>


</x-app-layout>
