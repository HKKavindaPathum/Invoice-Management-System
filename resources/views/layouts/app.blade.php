<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div id="sidebar" class="w-64 bg-gradient-to-b from-blue-600 via-blue-700 to-blue-900 text-white p-6 fixed -left-full md:left-0 top-16 bottom-0 overflow-y-auto shadow-xl transition-all duration-300 z-30 border-r border-blue-800">
                <div class="flex justify-between items-center mb-6 md:hidden">
                    <h2 class="text-xl font-bold tracking-wide">Menu</h2>
                    <button id="sidebarCloseMobile" class="text-gray-300 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-2 text-sm font-medium">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                        {{ request()->routeIs('dashboard') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                        📈 <span>Dashboard</span>
                    </a>

                    @can('category-list')
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                            {{ request()->routeIs('categories.index') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                            📂 <span>Categories</span>
                        </a>
                    @endcan

                    @can('product-list')
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                            {{ request()->routeIs('products.index') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                            📦 <span>Products</span>
                        </a>
                    @endcan

                    @can('client-list')
                        <a href="{{ route('clients.index') }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                            {{ request()->routeIs('clients.index') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                            👥 <span>Clients</span>
                        </a>
                    @endcan

                    @can('invoice-list')
                        <a href="{{ route('invoices.index') }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                            {{ request()->routeIs('invoices.index') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                            🧾 <span>Invoices</span>
                        </a>
                    @endcan

                    @can('settings')
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-500/40 
                            {{ request()->routeIs('settings.index') ? 'bg-blue-500/60 border-l-4 border-cyan-300' : '' }}">
                            ⚙️ <span>Settings</span>
                        </a>
                    @endcan
                </nav>
            </div>


            <!-- Main Content -->
            <div class="flex-1 bg-white md:ml-64">
                <!-- Navigation Bar -->
                <div class="fixed top-0 left-0 right-0 md:left-64 bg-white shadow z-20">
                    @include('layouts.navigation')
                </div>

                <!-- Content Area -->
                <div class="pt-16 px-4 md:px-6">
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="overflow-y-auto min-h-[calc(100vh-4rem)] p-4">
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