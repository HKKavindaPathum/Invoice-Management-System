@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white shadow-xl rounded-xl p-6 mt-6 max-w-7xl">

    <!-- Search + Add Product -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- Search Form -->
        <form action="{{ route('products.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
            <input 
                type="text" 
                name="search" 
                class="w-full px-4 py-2 border border-gray-300 rounded-l-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200"
                placeholder="Search products..." 
                value="{{ request()->get('search') }}">

            <!-- Clear Icon -->
            @if(request()->get('search'))
                <button 
                    type="button" 
                    onclick="clearSearch()" 
                    class="absolute right-28 top-2 text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif  

            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-r-full shadow-md transition duration-200">
                Search
            </button>
        </form>

        <!-- Add Product Button -->
        @can('product-create')
        <a href="{{ route('products.create') }}" 
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition duration-300">
            Add Product
        </a> 
        @endcan
    </div>

    <!-- Success Toast -->
    @if(session('success'))
    <div id="successToast" class="fixed top-16 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-4 transition-all duration-500">
        {{ session('success') }}
    </div>
    @endif

    <!-- Desktop Table -->
    @can('product-list')
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-left border-collapse shadow-md rounded-xl overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4">Image</th>
                    <th class="py-3 px-4">Product Name</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Price</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b hover:bg-gray-50 transition duration-150 text-center">
                    <td class="py-3 px-4">
                        <img src="{{ asset($product->image) }}" class="w-12 h-12 object-cover rounded-md mx-auto" alt="Product Image">
                    </td>
                    <td class="py-3 px-4 font-medium text-blue-600 hover:text-blue-800">
                        <a href="{{ route('products.show', $product->id) }}">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td class="py-3 px-4">{{ $product->category->name }}</td>
                    <td class="py-3 px-4">RS: {{ $product->unit_price }}</td>
                    <td class="py-3 px-4 flex justify-center items-center gap-2 mt-3">
                        @can('product-edit')
                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-200">
                            <x-far-edit class="h-5 w-5" />
                        </a>
                        @endcan
                        @can('product-delete')
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition duration-200">
                                <x-gmdi-delete class="h-5 w-5" />
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @foreach($products as $product)
        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset($product->image) }}" class="w-12 h-12 object-cover rounded-md" alt="Product Image">
                <div class="flex flex-col">
                    <a href="{{ route('products.show', $product->id) }}" class="font-medium text-blue-600 hover:text-blue-800">
                        {{ $product->name }}
                    </a>
                    <span class="text-gray-500 text-sm">{{ $product->category->name }}</span>
                    <span class="text-gray-700 font-semibold">RS: {{ $product->unit_price }}</span>
                </div>
            </div>
            <div class="flex gap-2 mt-2 sm:mt-0">
                @can('product-edit')
                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700">
                    <x-far-edit class="h-5 w-5" />
                </a>
                @endcan
                @can('product-delete')
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <x-gmdi-delete class="h-5 w-5" />
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @endforeach
    </div>
    @endcan
</div>
@endsection

@push('scripts')
<script>
    function clearSearch() {
        window.location.href = '{{ route('products.index') }}';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 5000);
        }
    });
</script>
@endpush
