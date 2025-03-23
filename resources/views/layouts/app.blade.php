<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Invoice Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <div class="w-1/6 bg-gray-800 text-white p-6 h-screen fixed left-0 top-0 overflow-y-auto">
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('dashboard') ? 'underline font-bold' : '' }}">
                           Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('categories.index') ? 'underline font-bold' : '' }}">
                           Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('products.index') ? 'underline font-bold' : '' }}">
                           Products
                        </a> 
                    </li>
                    <li>
                        <a href="{{ route('clients.index') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('clients.index') ? 'underline font-bold' : '' }}">
                           Clients
                        </a> 
                    </li>
                    <li>
                        <a href="{{ route('invoices.index') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('invoices.index') ? 'underline font-bold' : '' }}">
                           Invoices
                        </a> 
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" 
                           class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition 
                           {{ request()->routeIs('settings.index') ? 'underline font-bold' : '' }}">
                           Settings
                        </a> 
                    </li>
                </ul>
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