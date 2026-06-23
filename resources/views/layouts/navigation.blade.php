<nav x-data="{ open: false, sidebarOpen: false, isDark: localStorage.getItem('theme') === 'dark' }" 
     class="fixed top-0 left-0 right-0 z-20 bg-white/70 dark:bg-slate-900/75 backdrop-blur-lg border-b border-slate-100/85 dark:border-slate-800/85 shadow-[0_1px_8px_rgba(0,0,0,0.02)] transition-all duration-300">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 w-full">
            <div class="flex items-center gap-4">
                <!-- Mobile Sidebar Toggle -->
                <div class="mr-1 flex md:hidden">
                    <button id="sidebarToggleMobile" @click="sidebarOpen = true" 
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        @php $setting = \App\Models\Setting::first(); @endphp
                        @if($setting && $setting->app_logo)
                            <img src="{{ asset($setting->app_logo) }}" alt="App Logo" class="block h-9 w-auto transform group-hover:scale-105 transition duration-300">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-blue-600 dark:text-blue-500 transform group-hover:scale-105 transition duration-300" />
                        @endif
                        <span class="hidden sm:block text-md font-bold text-slate-800 dark:text-slate-100 tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-500 transition duration-300">
                            {{ $setting->app_name ?? 'Invoice Management' }}
                        </span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button @click="isDark = !isDark; 
                                localStorage.setItem('theme', isDark ? 'dark' : 'light'); 
                                document.documentElement.classList.toggle('dark', isDark)"
                        type="button"
                        class="p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-200 outline-none"
                        title="Toggle Theme">
                    <!-- Sun Icon (visible in Dark Mode) -->
                    <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <!-- Moon Icon (visible in Light Mode) -->
                    <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Settings Dropdown (Desktop) -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-800/80 transition duration-150">
                                <div class="h-7 w-7 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <div class="text-xs text-slate-450 dark:text-slate-500 font-semibold">Signed in as</div>
                                <div class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            
                            <x-dropdown-link class="hover:bg-slate-55/40 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 font-semibold flex items-center gap-2" :href="route('profile.edit')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profile Details</span>
                            </x-dropdown-link>
                            
                            <x-dropdown-link class="hover:bg-slate-55/40 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 font-semibold flex items-center gap-2" :href="route('users.create')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Create User</span>
                            </x-dropdown-link>
                            
                            <x-dropdown-link class="hover:bg-slate-55/40 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 font-semibold flex items-center gap-2" :href="route('users.index')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Manage Users</span>
                            </x-dropdown-link>
                            
                            <x-dropdown-link class="hover:bg-slate-55/40 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 font-semibold flex items-center gap-2" :href="route('roles.index')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Manage Roles</span>
                            </x-dropdown-link>

                            <hr class="border-slate-100 dark:border-slate-800 my-1.5">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link class="hover:bg-red-50 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 font-bold flex items-center gap-2" :href="route('logout')" 
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Log Out</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger (Mobile Menu Toggle) -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" 
                        class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 transition outline-none">
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
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-b border-slate-100 dark:border-slate-800 shadow-lg transition-all duration-300">
        <div class="pt-4 pb-3 border-t border-slate-100 dark:border-slate-800 px-4 space-y-3">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-slate-500 dark:text-slate-450">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-1 pt-2">
                <x-responsive-nav-link class="text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-slate-100 font-semibold flex items-center gap-2 rounded-lg" :href="route('profile.edit')">
                    Profile Details
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-slate-100 font-semibold flex items-center gap-2 rounded-lg" :href="route('users.create')">
                    Create User
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-950 dark:hover:text-slate-100 font-semibold flex items-center gap-2 rounded-lg" :href="route('users.index')">
                    Manage Users
                </x-responsive-nav-link>
                
                <hr class="border-slate-100 dark:border-slate-800 my-2">
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 hover:text-red-800 dark:hover:text-red-300 font-bold flex items-center gap-2 rounded-lg" :href="route('logout')" 
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
