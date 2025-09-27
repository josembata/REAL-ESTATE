<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Permission</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block">Permission Name</label>
                <input type="text" name="name" value="{{ $permission->name }}" class="border rounded p-2 w-1/3">
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>
