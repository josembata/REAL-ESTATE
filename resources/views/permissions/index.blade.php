<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Permission Management</h2>
    </x-slot>

    <div class="p-6">
        {{-- Create Permission --}}
        <form action="{{ route('permissions.store') }}" method="POST" class="mb-6 flex gap-4">
            @csrf
            <input type="text" name="name" placeholder="Permission Name" class="border rounded p-2 w-1/3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Permission</button>
        </form>

        {{-- List Permissions --}}
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Permission</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr class="border-b">
                        <td class="p-2">{{ $permission->name }}</td>
                        <td class="p-2">
                            <a href="{{ route('permissions.edit', $permission) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
