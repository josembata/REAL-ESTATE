<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Role Management</h2>
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>

    <div class="p-6">
        <form action="{{ route('roles.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-4">
                <input type="text" name="name" placeholder="Role Name" class="border rounded p-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Role</button>
            </div>
        </form>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Role</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr class="border-b">
                        <td class="p-2">{{ $role->name }}</td>
                        <td class="p-2">
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
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
