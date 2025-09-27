<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Assign Roles
             @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Assign a Role to User</h3>

        <form action="{{ route('roles.assign') }}" method="POST" class="bg-white p-6 rounded shadow-lg w-1/2 mx-auto">
    @csrf
    <div class="mb-4">
        <label class="block font-semibold mb-2">Select User</label>
        <select name="user_id" class="w-full border px-3 py-2 rounded" required>
            <option value="" disabled selected>-- Select user --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>

  <div class="mb-4">
    <label class="block font-semibold mb-2">Select Role</label>
    <select name="role" class="w-full border px-3 py-2 rounded" required>
        <option value="" disabled selected>-- Select Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
        @endforeach
    </select>
</div>


    <!-- Additional fields for Agent  -->
    <div id="agent-fields" class="hidden">
        <label class="block mb-2">License Number</label>
        <input type="text" name="license_number" class="w-full border px-3 py-2 rounded mb-2">

        <label class="block mb-2">Experience (Years)</label>
        <input type="number" name="experience_years" class="w-full border px-3 py-2 rounded mb-2">
    </div>

    <!--  Additional fields for Landlord  -->
    <div id="landlord-fields" class="hidden">
        <label class="block mb-2">Company Name</label>
        <input type="text" name="company_name" class="w-full border px-3 py-2 rounded mb-2">

        <label class="block mb-2">Address</label>
        <input type="text" name="address" class="w-full border px-3 py-2 rounded mb-2">
    </div>

    <!--  Additional fields for Staff  -->
    <div id="staff-fields" class="hidden">
        <label class="block mb-2">Department</label>
        <input type="text" name="department" class="w-full border px-3 py-2 rounded mb-2">

        <label class="block mb-2">Position</label>
        <input type="text" name="position" class="w-full border px-3 py-2 rounded mb-2">

         <label class="block mb-2">Employee Number</label>
        <input type="text" name="employee_number" class="w-full border px-3 py-2 rounded mb-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Assign Role</button>
</form>
        </div>
    </div>
</x-app-layout>

<script>
    const roleSelect = document.querySelector('select[name="role"]');
    const agentFields = document.getElementById('agent-fields');
    const landlordFields = document.getElementById('landlord-fields');
    const staffFields = document.getElementById('staff-fields');

    roleSelect.addEventListener('change', function() {
        agentFields.classList.add('hidden');
        landlordFields.classList.add('hidden');
        staffFields.classList.add('hidden');

        if (this.value === 'Agent') {
            agentFields.classList.remove('hidden');
        } else if (this.value === 'Landlord') {
            landlordFields.classList.remove('hidden');
        } else if (this.value === 'Staff') {
            staffFields.classList.remove('hidden');
        }
    });
</script>
