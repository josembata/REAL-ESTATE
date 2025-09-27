<x-app-layout>
    <div class="max-w-6xl mx-auto py-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6"> All Inquiries </h2>

        <div class="overflow-x-auto bg-white shadow-md rounded-2xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">User</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Property</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Created</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($inquiries as $inq)
                        <tr class="hover:bg-gray-50">
                            <!-- User -->
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $inq->tenant->name }}
                            </td>

                            <!-- Property -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $inq->property->name }}
                            </td>

                            <!-- Subject -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $inq->subject }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($inq->status === 'open')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Open
                                    </span>
                                @elseif($inq->status === 'awaiting_reply')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Awaiting Reply
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                                        Closed
                                    </span>
                                @endif
                            </td>

                            <!-- Created -->
                            <td class="px-6 py-4 text-gray-500">
                                {{ $inq->created_at->format('Y-m-d') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 flex items-center justify-center space-x-3">
                                <!-- View Button -->
                                <a href="{{ route('inquiries.show',$inq) }}"
                                   class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                    View
                                </a>

                                <!-- Mark Closed -->
                                @if($inq->status != 'closed')
                                    <form method="POST" action="{{ route('inquiries.close',$inq) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                                            Mark Closed
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
