<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Admin Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Users Management</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ App\Models\User::count() }}</p>
                    <p>Total Users</p>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        Manage Users 
                    </a>
                </div>

                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Customer</h3>
                    <p class="text-3xl font-bold text-green-600">{{ App\Models\User::where('role', 'customer')->count() }}</p>
                    <p>Customer</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Agents</h3>
                    <p class="text-3xl font-bold text-green-600">{{ App\Models\User::where('role', 'agent')->count() }}</p>
                    <p>Total Agents</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Landlords</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ App\Models\User::where('role', 'landlord')->count() }}</p>
                    <p>Total Landlords</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Manage Users
                    </a>
                    <a href="#" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                        View Reports
                    </a>
                    <a href="#" class="bg-purple-500 hover:bg-purple-700 text-white px-4 py-2 rounded">
                        System Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>