@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center bg-gradient-to-r from-blue-50 to-purple-50 p-6">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Service Details
            </h1>

            <!-- Service Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Service Name</label>
                        <p class="text-lg font-medium text-gray-800">{{ $service->name }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Category</label>
                        <p class="text-lg font-medium text-gray-800">{{ $service->category->name }}</p>
                    </div>


                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Price</label>
                        <p class="text-lg font-medium text-gray-800">RS: {{ number_format($service->unit_price, 2) }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Description</label>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $service->description ?? 'No description available' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Button Group -->
            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('services.edit', $service->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Edit Service
                </a>
                
                <a href="{{ route('services.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Back to Services List
                </a>
            </div>
        </div>
    </div>
@endsection
