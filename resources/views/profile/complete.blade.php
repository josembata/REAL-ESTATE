<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Complete Your Profile</h2>
                <p class="text-blue-100 text-sm mt-1">Please provide some additional information to continue</p>
            </div>

            <form method="POST" action="{{ route('complete-profile.submit') }}" enctype="multipart/form-data" class="px-6 py-6">
                @csrf

                @if(session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Phone Number Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="phone">
                        Phone Number
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone"
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    
                           required>
                    
                </div>

                <!-- Gender Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="gender">
                        Gender
                    </label>
                    <select name="gender" 
                            id="gender"
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        
                        
                    </select>
                </div>

                <!-- Avatar Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="avatar">
                        Profile Picture
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="file" 
                                   name="avatar" 
                                   id="avatar"
                                   accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <label for="avatar" class="cursor-pointer bg-gray-100 hover:bg-gray-200 px-4 py-3 rounded-lg border border-gray-300 transition duration-200">
                                <span class="text-gray-600">Choose file</span>
                            </label>
                        </div>
                        <span id="file-name" class="text-sm text-gray-500">No file chosen</span>
                    </div>
                    
                </div>

                <!-- Bio Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="bio">
                        About You
                    </label>
                    <textarea name="bio" 
                              id="bio"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                              placeholder="Tell us  about yourself...">{{ old('bio') }}</textarea>
                    
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center mt-8">
                  
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-black font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-200 transform hover:scale-105">
                        Complete Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show selected file name
        document.getElementById('avatar').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });

    
        document.getElementById('phone').addEventListener('input', function(e) {
            const input = e.target.value.replace(/\D/g, '').substring(0, 15);
            const formatted = formatPhoneNumber(input);
            e.target.value = formatted;
        });

  
    </script>

    <style>
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .transition {
            transition: all 0.2s ease-in-out;
        }
        
        .transform {
            transform: translateZ(0);
        }
    </style>
</x-app-layout>