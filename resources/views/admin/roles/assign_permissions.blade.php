<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Role Permission Management</h2>
        @if (session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>

    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Section 1: Roles List -->
        <div class="bg-white p-4 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 border-b pb-2">Available Roles</h3>
            <ul class="space-y-2">
                @foreach($roles as $role)
                    <li>
                        <button 
                            type="button" 
                            class="w-full text-left px-4 py-2 rounded-md bg-gray-100 hover:bg-blue-100 hover:text-blue-700 transition"
                            onclick="selectRole('{{ $role->id }}', '{{ $role->name }}')"
                        >
                            {{ ucfirst($role->name) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Section 2: Assign Permissions -->
        <div class="bg-white p-4 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 border-b pb-2">Assign Permissions</h3>

            <form id="assignPermissionsForm" method="POST" action="{{ route('roles.assign.permissions') }}">
                @csrf
                <input type="hidden" name="role_id" id="role_id">

                <p id="selectedRole" class="mb-3 text-sm text-gray-600 italic">Select a role to manage permissions.</p>

                <div id="permissionsContainer" class="max-h-64 overflow-y-auto space-y-2 border rounded p-3">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded accent-blue-600">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Assign Selected Permissions
                </button>
            </form>
        </div>

        <!-- Section 3: Role Permissions Summary -->
        <div class="bg-white p-4 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 border-b pb-2">Role Permissions Overview</h3>

            <div class="space-y-5">
                @foreach($roles as $role)
                    <div class="border-b pb-3">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ ucfirst($role->name) }}</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($permissions as $permission)
                                <label class="flex items-center space-x-2 text-sm">
                                    <input 
                                        type="checkbox" 
                                        class="permission-toggle accent-green-600"
                                        data-role-id="{{ $role->id }}" 
                                        data-permission-id="{{ $permission->id }}"
                                        @if($role->hasPermissionTo($permission->name)) checked @endif
                                    >
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Set selected role in assign form
        function selectRole(roleId, roleName) {
            document.getElementById('role_id').value = roleId;
            document.getElementById('selectedRole').textContent = `Managing permissions for: ${roleName}`;
        }

        // Handle live toggle for assigned permissions
        document.querySelectorAll('.permission-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const roleId = this.dataset.roleId;
                const permissionId = this.dataset.permissionId;

                fetch("{{ route('roles.toggle.permission') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        role_id: roleId,
                        permission_id: permissionId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(`Permission ${data.status} for role`);
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</x-app-layout>
