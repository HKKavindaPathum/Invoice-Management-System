<nav x-data="{ open: false, sidebarOpen: false }" 
    class="fixed top-0 left-0 right-0 z-20 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 border-b shadow-[0_4px_6px_rgba(0,0,0,0.15)]">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 w-full">
            <div class="flex items-center">
                <!-- Mobile Sidebar Toggle -->
                <div class="mr-2 flex md:hidden">
                    <button id="sidebarToggleMobile" @click="sidebarOpen = true" 
                        class="text-gray-200 hover:text-white focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}">
                        @php $setting = \App\Models\Setting::first(); @endphp
                        @if($setting && $setting->app_logo)
                            <img src="{{ asset($setting->app_logo) }}" alt="App Logo" class="block h-9 w-auto">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        @endif
                    </a>
                </div>

                <!-- App Name (Desktop) -->
                <div class="hidden sm:flex sm:ms-10">
                    <h1 id="app-name" class="text-xl font-semibold text-white tracking-wide drop-shadow">
                        {{ $setting->app_name ?? 'Invoice Management System' }}
                    </h1>
                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 transition duration-150">
                            <div class="text-lg font-medium">{{ Auth::user()->name }}</div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                    111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 
                                    010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link class="hover:bg-blue-50 text-gray-800" :href="route('profile.edit')">Profile</x-dropdown-link>
                        <x-dropdown-link class="hover:bg-blue-50 text-gray-800" :href="route('users.create')">Create new user</x-dropdown-link>
                        <x-dropdown-link class="hover:bg-blue-50 text-gray-800" :href="route('users.index')">Manage users</x-dropdown-link>
                        <x-dropdown-link class="hover:bg-blue-50 text-gray-800" :href="route('roles.index')">Manage Roles</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link class="hover:bg-blue-50 text-gray-800" :href="route('logout')" 
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile Menu) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-blue-800 transition">
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

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gradient-to-b from-blue-600 to-blue-800">
        <div class="pt-4 pb-1 border-t border-blue-400/50">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link class="text-white hover:bg-blue-500/40" :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <x-responsive-nav-link class="text-white hover:bg-blue-500/40" :href="route('users.create')">Create new user</x-responsive-nav-link>
                <x-responsive-nav-link class="text-white hover:bg-blue-500/40" :href="route('users.index')">Manage users</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link class="text-white hover:bg-blue-500/40" :href="route('logout')" 
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
