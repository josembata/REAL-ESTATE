<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Permission Management</h2>
    </x-slot>

    <div class="p-6 space-y-8">
        {{--  Create Permission Category --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Add Permission Category</h3>
            <form action="{{ route('permissions.categories.store') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="name" placeholder="Category Name" class="border rounded p-2 w-1/3">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Category</button>
            </form>
        </div>

        {{--  Create Permission --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Add Permission</h3>
            <form action="{{ route('permissions.store') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="name" placeholder="Permission Name " class="border rounded p-2 w-1/3">
                <select name="category_id" class="border rounded p-2 w-1/3">
                    <option value="">Select Category (optional)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Permission</button>
            </form>
        </div>

        {{--  Grouped Permissions --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-4">Permissions by Category</h3>
            @forelse($categories as $category)
                <div class="border-b pb-3 mb-3">
                    <h4 class="font-semibold text-lg text-gray-800">{{ $category->name }}</h4>
                    @if($category->permissions->isEmpty())
                        <p class="text-gray-500 text-sm">No permissions in this category.</p>
                    @else
                        <ul class="list-disc list-inside text-gray-700">
                            @foreach($category->permissions as $permission)
                                <li class="flex justify-between items-center">
                                    {{ $permission->name }}
                                    <div>
                                        <!-- <a href="{{ route('permissions.edit', $permission) }}" class="text-blue-600 text-sm mr-2">Edit</a> -->
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No permission categories found.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
