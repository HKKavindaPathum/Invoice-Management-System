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
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-900 text-white p-6 h-screen fixed left-0 top-0 overflow-y-auto shadow-lg">
                <nav class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('dashboard') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        📈 Dashboard
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('categories.index') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        📂 Categories
                    </a>

                    <!-- Products -->
                    <a href="{{ route('products.index') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('products.index') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        📦 Products
                    </a>

                    <!-- Clients -->
                    <a href="{{ route('clients.index') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('clients.index') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        👥 Clients
                    </a>

                    <!-- Invoices -->
                    <a href="{{ route('invoices.index') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('invoices.index') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        🧾 Invoices
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center px-4 py-3 rounded-md hover:bg-gray-700 transition 
                        {{ request()->routeIs('settings.index') ? 'bg-gray-800 font-semibold border-l-4 border-blue-400' : '' }}">
                        ⚙️ Settings
                    </a>
                </nav>
            </div>

            

            <!-- Main Content -->
            <div class="flex-1 bg-white ml-[16.666667%]">
                <!-- Navigation Bar -->
                <div class="fixed top-0 right-0 w-[83.333333%] bg-white shadow z-10">
                    @include('layouts.navigation')
                </div>

                <!-- Content Area -->
                <div class="pt-16"> <!-- Add padding-top to account for the fixed navigation bar -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="overflow-y-auto h-[calc(100vh-4rem)]"> <!-- Make the content area scrollable -->
                        @yield('content')
                        @stack('scripts')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>