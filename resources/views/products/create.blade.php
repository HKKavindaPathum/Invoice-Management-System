@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">Add Product</h2>

        <!-- Error Popup -->
        @if (session('error'))
            <script>
                window.onload = function() {
                    alert("{{ session('error') }}");
                };
            </script>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Grid Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" required 
                                class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Unit Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <input type="number" name="unit_price" step="0.01" required 
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description"
                                  class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Image</label>
                        <input type="file" name="image"
                               class="w-full border p-2 rounded-lg mt-1 focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Buttons Container -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Save
                </button>
                <a href="{{ route('products.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg ml-2 hover:bg-gray-600 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection