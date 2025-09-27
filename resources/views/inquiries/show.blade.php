<x-app-layout>
    <div class="max-w-xl mx-auto py-10">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Inquiry: <span class="text-blue-600">{{ $inquiry->subject }}</span>
        </h2>

        
       <!-- Conversation  -->

<div class="bg-white shadow rounded-2xl p-6 mb-6 h-96 overflow-y-auto border border-gray-100
            flex flex-col space-y-4"> <!-- Add flex-col + space-y-4 -->
            <!-- //conversation color box -->
    @foreach($inquiry->messages as $msg)
        <div class="@if($msg->sender_id === auth()->id()) self-end @else self-start @endif">
            <div class="inline-block max-w-xs px-4 py-2 rounded-lg 
            
                @if($msg->sender_id === auth()->id()) bg-blue-600 text-white @else  bg-red-600 text-white @endif"> 

                <!-- Message Text -->
                <p class="text-sm">{{ $msg->body }}</p>

                {{-- Attachments --}}
                @if($msg->attachments)
                    <div class="mt-2 space-y-2">
                        @foreach($msg->attachments as $file)
                            @php
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            @endphp

                            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                <!-- Show image preview -->
                                <a href="{{ asset('storage/'.$file) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$file) }}" 
                                         class="w-32 h-32 object-cover rounded-lg border shadow-sm hover:opacity-90 transition"
                                         alt="attachment">
                                </a>
                            @else
                                <!-- Show file link -->
                                <a href="{{ asset('storage/'.$file) }}" target="_blank" 
                                   class="block text-xs underline hover:text-blue-300">
                                    📎 {{ basename($file) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Meta Info -->
                <p class="text-[10px] mt-1 opacity-75">
                    {{ $msg->sender->name }} • {{ $msg->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    @endforeach
</div>



        <!-- Message Form -->
        <form method="POST" action="{{ route('messages.store',$inquiry) }}" enctype="multipart/form-data"
              class="bg-white shadow rounded-2xl p-4 border border-gray-100">
            @csrf
            <div class="flex items-center space-x-2">
                <textarea name="body" rows="2" required
                          class="flex-1 resize-none rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                          placeholder="Type your message..."></textarea>
                
                <label class="cursor-pointer px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    📎Attach file
                    <input type="file" name="attachments[]" multiple class="hidden">
                </label>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    Send
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
