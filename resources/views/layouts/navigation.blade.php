<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <!-- Dashboard Link -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <!-- Admin Links -->
                        @if(Auth::user()->hasRole('Admin'))
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')">
                                {{ __('Admin Panel') }}
                            </x-nav-link>
                             <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.index')">
                                {{ __('Properties') }}
                            </x-nav-link>
                               <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                                {{ __('Roles') }}
                            </x-nav-link>
                             <x-nav-link :href="route('roles.assign.form')" :active="request()->routeIs('roles.assign.form')">
                                {{ __('Assign Roles') }}
                            </x-nav-link>
                             <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
                                {{ __('Permissions') }}
                            </x-nav-link>
                             <x-nav-link :href="route('roles.assign.permissions.form')" :active="request()->routeIs('roles.assign.permissions.form')">
                                {{ __('Assign Permissions') }}
                            </x-nav-link>
                              <x-nav-link :href="route('admin.documents.pending')" :active="request()->routeIs('admin.documents.pending')">
                                {{ __('Dcs verification') }}
                            </x-nav-link>
                             <x-nav-link :href="route('ownerships.index')" :active="request()->routeIs('ownerships.index')">
                                {{ __('Ownerships') }}
                            </x-nav-link>
                        @endif

                        <!-- Agent Links -->
                        @if(Auth::user()->hasRole('Agent'))
                            <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.index')">
                                {{ __('My Properties') }}
                            </x-nav-link>
                           
                        @endif

                          <!-- Staff Links -->
                        @if(Auth::user()->hasRole('Staff'))
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')">
                                {{ __('Admin Panel') }}
                            </x-nav-link>
                               <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                                {{ __('Roles') }}
                            </x-nav-link>
                             <x-nav-link :href="route('roles.assign.form')" :active="request()->routeIs('roles.assign.form')">
                                {{ __('Assign Roles') }}

                            </x-nav-link>

                        @endif

                        <!-- Landlord Links -->
                        @if(Auth::user()->hasRole('Landlord'))
                             <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.index')">
                                {{ __('My Properties') }}
                            </x-nav-link>
                            <x-nav-link :href="route('landlord.report')" :active="request()->routeIs('landlord.report')">
                                {{ __('Reports') }}
                            </x-nav-link>
                              <x-nav-link :href="route('ownerships.index')" :active="request()->routeIs('ownerships.index')">
                                {{ __('Ownerships') }}
                            </x-nav-link>
                            
                        @endif

                        <!-- Tenant Links -->
                        @if(Auth::user()->hasRole('Tenant'))
                            <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.index')">
                                {{ __('Properties') }}
                            </x-nav-link>
                           
                            <x-nav-link :href="route('amenities.index')" :active="request()->routeIs('amenities.index')">
                                {{ __('Amenities') }}
                            </x-nav-link>
                            <x-nav-link :href="route('inquiries.index')" :active="request()->routeIs('inquiries.index')">
                                {{ __('Inquiries') }}
                            </x-nav-link>
                            <x-nav-link :href="route('bookings.user')" :active="request()->routeIs('bookings.user')">
                                {{ __('My Bookings') }}
                            </x-nav-link>
                             <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.index')">
                                {{ __('Invoices') }}
                            </x-nav-link>
                            
                            
                             <x-nav-link :href="route('leases.index')" :active="request()->routeIs('leases.index')">
                                {{ __('Leases') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 transition">
                           <img class="h-20 w-15 rounded-full object-cover" 
                           src="{{ optional(Auth::user()->tenant)->avatar 
                           ? asset(optional(Auth::user()->tenant)->avatar) 
                           : asset('default-avatar.png') }}" 
                            alt="{{ Auth::user()->name }}" />

                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
