<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Theme Loader -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50/50 dark:bg-slate-950 transition-colors duration-300">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div id="sidebar" class="w-64 bg-white dark:bg-slate-900 text-slate-550 dark:text-slate-400 p-6 fixed -left-full md:left-0 top-16 bottom-0 overflow-y-auto shadow-[2px_0_12px_rgba(0,0,0,0.01)] transition-all duration-300 z-30 border-r border-slate-100 dark:border-slate-800/80">
                <div class="flex justify-between items-center mb-6 md:hidden">
                    <h2 class="text-xs font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Navigation</h2>
                    <button id="sidebarCloseMobile" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-1 text-sm font-medium">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                        {{ request()->routeIs('dashboard') 
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                            : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    @can('invoice-create')
                        <a href="{{ route('invoices.create') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('invoices.create') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Create Invoice</span>
                        </a>
                    @endcan

                    @can('invoice-list')
                        <a href="{{ route('invoices.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('invoices.index') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Invoices</span>
                        </a>
                    @endcan

                    @can('category-list')
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('categories.index') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span>Categories</span>
                        </a>
                    @endcan

                    @can('product-list')
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('products.index') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Products</span>
                        </a>
                    @endcan

                    @can('client-list')
                        <a href="{{ route('clients.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('clients.index') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Clients</span>
                        </a>
                    @endcan

                    @can('settings')
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
                            {{ request()->routeIs('settings.index') 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-semibold' 
                                : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Settings</span>
                        </a>
                    @endcan
                </nav>
            </div>


            <!-- Main Content -->
            <div class="flex-1 bg-slate-50/50 dark:bg-slate-950 md:ml-64 flex flex-col min-h-screen transition-colors duration-300">
                <!-- Navigation Bar -->
                <div class="fixed top-0 left-0 right-0 md:left-64 z-20">
                    @include('layouts.navigation')
                </div>

                <!-- Content Area -->
                <div class="pt-16 px-4 md:px-6 flex-1 flex flex-col">
                    @isset($header)
                        <header class="bg-transparent py-6 px-4">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="flex-1 p-2 sm:p-4">
                        @yield('content')
                        @stack('scripts')
                    </main>
                </div>
            </div>
        </div>

        <script>
            //Toggle sidebar on mobile
            document.getElementById('sidebarToggleMobile').addEventListener('click', function() {
                document.getElementById('sidebar').style.left = '0';
            });

            //Close sidebar on mobile
            document.getElementById('sidebarCloseMobile').addEventListener('click', function() {
                document.getElementById('sidebar').style.left = '-100%';
            });

            //Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                const sidebarToggle = document.getElementById('sidebarToggleMobile');
                
                if (window.innerWidth < 768 && 
                    !sidebar.contains(e.target) && 
                    e.target !== sidebarToggle && 
                    !sidebarToggle.contains(e.target)) {
                    sidebar.style.left = '-100%';
                }
            });
        </script>
    </body>
</html>