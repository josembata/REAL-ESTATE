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
                   
                </div>

                 
               

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
               
            </div>
        </div>
    </div>
</x-app-layout>