@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h2 class="text-2xl font-bold mb-6">Add Service</h2>

        <!-- Error Popup -->
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Grid Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" required 
                               class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" required 
                                class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <div class="relative w-full max-w-md">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">RS:</span>
                            <input type="number" name="unit_price" step="0.01" required 
                                   class="w-full pl-10 border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full max-w-md border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none transition"></textarea>
                    </div>


                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('services.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Save Service
                </button>
            </div>
        </form>
    </div>
@endsection
