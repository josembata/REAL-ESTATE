<x-app-layout>
    <div class="max-w-xl mx-auto py-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">
            New Inquiry for <span class="text-blue-600">{{ $property->name }}</span>
        </h2>

        <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
            <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">

                {{-- Subject --}}
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                {{-- Message --}}
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" id="message" rows="5" required
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('properties.show', $property->id) }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        Submit Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
