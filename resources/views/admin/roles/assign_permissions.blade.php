<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Role & User Permission Management</h2>
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

            <h3 class="font-semibold text-lg mt-6 mb-4 text-gray-700 border-b pb-2">Users</h3>
            <ul class="space-y-2">
                @foreach($users as $user)
                    <li>
                        <button 
                            type="button" 
                            class="w-full text-left px-4 py-2 rounded-md bg-gray-100 hover:bg-green-100 hover:text-green-700 transition"
                            onclick="selectUser('{{ $user->id }}', '{{ $user->name }}')"
                        >
                            {{ ucfirst($user->name) }}{{ $user->email ? ' (' . $user->email . ')' : '' }}
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
                <input type="hidden" name="user_id" id="user_id">

                <p id="selectedRole" class="mb-3 text-sm text-gray-600 italic">Select a role or user to manage permissions.</p>

                <!-- Grouped permissions -->
                <div id="permissionsContainer" class="max-h-96 overflow-y-auto space-y-2">
                    @foreach($categories as $category)
                        <div class="border rounded-md">
                            <button type="button" 
                                class="w-full text-left bg-gray-100 px-4 py-2 flex justify-between items-center"
                                onclick="toggleCategory('{{ $category->id }}')">
                                <span class="font-semibold">{{ ucfirst($category->name) }}</span>
                                <span id="icon-{{ $category->id }}">▼</span>
                            </button>

                            <div id="category-{{ $category->id }}" class="hidden px-4 py-3 space-y-1">
                                @foreach($category->permissions as $permission)
                                    <label class="flex items-center space-x-2 text-gray-700">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded accent-blue-600">
                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Uncategorized permissions -->
                    @if($uncategorizedPermissions->count() > 0)
                        <div class="border rounded-md">
                            <button type="button" 
                                class="w-full text-left bg-gray-100 px-4 py-2 flex justify-between items-center"
                                onclick="toggleCategory('uncategorized')">
                                <span class="font-semibold">Uncategorized</span>
                                <span id="icon-uncategorized">▼</span>
                            </button>

                            <div id="category-uncategorized" class="hidden px-4 py-3 space-y-1">
                                @foreach($uncategorizedPermissions as $permission)
                                    <label class="flex items-center space-x-2 text-gray-700">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded accent-blue-600">
                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Assign Selected Permissions
                </button>
            </form>
        </div>

        <!-- Section 3: Role Permissions Summary -->
        <div class="bg-white p-4 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 border-b pb-2">Role  and their Permissions </h3>
            <div class="space-y-5">
                @foreach($roles as $role)
                    <div class="border-b pb-3">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ ucfirst($role->name) }}</h4>
                        <p class="text-sm text-gray-700">
                            {{ $role->permissions->pluck('name')->join(', ') ?: 'No permissions assigned' }}
                        </p>
                    </div>
                @endforeach
                <hr class="my-4">
                <h3 class="font-semibold text-lg mb-4 text-gray-700 border-b pb-2">Users and Their Permissions</h3>
                @foreach($users as $user)
                    <div class="border-b pb-3">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ ucfirst($user->name) }} ({{ $user->email }})</h4>
                        <p class="text-sm text-gray-700">
                            {{ $user->permissions->pluck('name')->join(', ') ?: 'No permissions assigned' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleCategory(id) {
            const section = document.getElementById(`category-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            section.classList.toggle('hidden');
            icon.textContent = section.classList.contains('hidden') ? '▼' : '▲';
        }

        function selectRole(roleId, roleName) {
            document.getElementById('role_id').value = roleId;
            document.getElementById('user_id').value = '';
            document.getElementById('selectedRole').textContent = `Managing permissions for role: ${roleName}`;
        }

        function selectUser(userId, userName) {
            document.getElementById('user_id').value = userId;
            document.getElementById('role_id').value = '';
            document.getElementById('selectedRole').textContent = `Managing permissions for user: ${userName}`;
        }
    </script>
</x-app-layout>
