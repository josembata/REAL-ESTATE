<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Landlord Income Report
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Total Income -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="font-bold text-lg mb-4">Total Income</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-green-100 p-4 rounded">
                        <span class="block text-gray-700 font-semibold">Weekly</span>
                        <span class="block text-xl font-bold">{{ number_format($report['weekly_income'], 2) }}</span>
                    </div>
                    <div class="bg-blue-100 p-4 rounded">
                        <span class="block text-gray-700 font-semibold">Monthly</span>
                        <span class="block text-xl font-bold">{{ number_format($report['monthly_income'], 2) }}</span>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded">
                        <span class="block text-gray-700 font-semibold">Yearly</span>
                        <span class="block text-xl font-bold">{{ number_format($report['yearly_income'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Income Per Property -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-4">Income Per Property</h3>

                @if(count($report['property_income']) > 0)
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">Property</th>
                                <th class="p-2 border">Weekly Income</th>
                                <th class="p-2 border">Monthly Income</th>
                                <th class="p-2 border">Yearly Income</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['property_income'] as $property)
                                <tr>
                                    <td class="p-2 border">{{ $property['name'] }}</td>
                                    <td class="p-2 border">{{ number_format($property['weekly_income'], 2) }}</td>
                                    <td class="p-2 border">{{ number_format($property['monthly_income'], 2) }}</td>
                                    <td class="p-2 border">{{ number_format($property['yearly_income'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No income data available for your properties yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
