@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center bg-gradient-to-r from-blue-50 to-purple-50 p-6">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full ">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Product Details
            </h1>

            <!-- Product Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Product Name</label>
                        <p class="text-lg font-medium text-gray-800">{{ $product->name }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Category</label>
                        <p class="text-lg font-medium text-gray-800">{{ $product->category->name }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Product Image</label>
                        @if($product->image)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm flex justify-center">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-64 h-64 object-cover rounded-lg shadow-lg">
                            </div>
                        @else
                            <div>
                                <p class="text-lg font-medium text-gray-800">No image available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Price</label>
                        <p class="text-lg font-medium text-gray-800">RS: {{ number_format($product->unit_price, 2) }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Description</label>
                        <p class="text-lg font-medium text-gray-800 break-words">
                            {{ $product->description ?? 'No description available' }}
                        </p>
                    </div>
                </div>
            </div>

            

            <!-- Button Group -->
            <div class="mt-8 flex justify-center space-x-4">

                <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Edit Product
                </a>
                
                <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Back to Products List
                </a>
            </div>
        </div>
    </div>
@endsection