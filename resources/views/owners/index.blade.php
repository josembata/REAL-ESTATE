<table class="min-w-full bg-white rounded-lg shadow">
    <thead>
        <tr class="bg-gray-200 text-gray-700">
            <th class="py-2 px-4">#</th>
            <th class="py-2 px-4">Name</th>
            <th class="py-2 px-4">Address</th>
            <th class="py-2 px-4">Tax ID</th>
            <th class="py-2 px-4">Bank Account</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($owners as $owner)
            <tr>
                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                <td class="py-2 px-4">{{ $owner->company_name }}</td>
                <td class="py-2 px-4">{{ $owner->address }}</td>
                <td class="py-2 px-4">{{ $owner->tax_id }}</td>
                <td class="py-2 px-4">{{ $owner->bank_account }}</td>
                <td class="py-2 px-4">
                    <a href="{{ route('owners.edit', $owner->id) }}" class="text-blue-600">Edit</a> |
                    <form action="{{ route('owners.destroy', $owner->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600" onclick="return confirm('Delete this owner?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
