<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

         <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="name" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $tenant->phone)" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
    <div class="mt-4">
    <x-input-label for="gender" :value="__('Gender')" />

    <select id="gender" 
            name="gender" 
            required 
            class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
        <option value="">{{ __('Select gender') }}</option>
        <option value="male" {{ old('gender', $tenant->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ old('gender', $tenant->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
        
    </select>

    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="avatar" :value="__('Profile Picture')" />

    <input id="avatar" 
           type="file" 
           name="avatar" 
           accept="image/*"
           class="block mt-1 w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:border-blue-500 focus:ring-blue-500" />

    @if(isset($user) && $tenant->avatar)
        <div class="mt-2">
            <img src="{{ asset($tenant->avatar) }}" 
                 alt="Profile Picture" 
                 class="h-16 w-16 rounded-full object-cover">
        </div>
    @endif

    <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
</div>
 
<div class="mt-4">
    <x-input-label for="bio" :value="__('About You')" />

    <textarea id="bio" 
          name="bio" 
          rows="4"
          class="block mt-1 w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
          required autofocus autocomplete="bio">{{ old('bio', $tenant->bio ?? '') }}</textarea>


    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
</div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
       
    </form>
</section>
