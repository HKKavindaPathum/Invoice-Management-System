@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center bg-gradient-to-r from-blue-50 to-purple-50 p-6 mt-0">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full ">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Category: {{ $category->name }}
            </h1>
    
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Products in this Category:</h2>
    
                @if($category->products->isEmpty())
                    <p class="text-gray-600">No products found in this category.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">Product Name</th>
                                <th class="border border-gray-300 px-4 py-2">Price</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products as $product)
                                <tr class="text-center">
                                    <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">RS: {{ number_format($product->unit_price, 2) }}</td>
                                    <td class="border border-gray-300 px-4 py-2 flex justify-center items-center space-x-4">
                                        <!-- Edit Button -->
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <x-far-edit class="h-5 w-5 text-blue-500 hover:text-blue-700" />
                                        </a>                               
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 mt-2">
                                                <x-gmdi-delete class="h-5 w-5 text-red-500 hover:text-red-700" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
    
            <div class="mt-8 flex justify-center">
                <a href="{{ route('categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Back to Categories List
                </a>
            </div>
        </div>
    </div>
    
@endsection
