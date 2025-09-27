<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Price Plan Categories</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        <a href="{{ route('price_plan_categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Add Category</a>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td class="border px-4 py-2">{{ $category->id }}</td>
                        <td class="border px-4 py-2">{{ $category->name }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('price_plan_categories.edit', $category->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('price_plan_categories.destroy', $category->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
