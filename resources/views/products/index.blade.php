@extends('layouts.app')

@section('content')
    <div class="mx-auto  bg-white shadow-lg rounded-lg p-6 mt-1">
        
        <div class="flex justify-between items-center mb-4">
            <!-- Search Form -->
            <form action="{{ route('products.search') }}" method="GET" class="relative w-1/4">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                    placeholder="Search products..." 
                    value="{{ request()->get('search') }}">

                <!-- Clear Icon -->
                @if(request()->get('search'))
                    <button 
                        type="button" 
                        onclick="clearSearch()" 
                        class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif  

                <button type="submit" class="mt-2  bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 relative w-2/4">
                    Search
                </button>
            </form>

            @can('product-create')
                <a href="{{ route('products.create') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded-lg transition duration-200 mt-12">
                    Add Product
                </a> 
            @endcan
            
        </div>
        
        <!-- Display Success Message -->
        @if(session('success'))
            <script>
                alert('{{ session('success') }}');
            </script>
        @endif
        
        @can('product-list')
        <div class="mt-6">
            <table class="w-3/4 border-collapse border border-gray-300 mx-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Image</th>
                        <th class="border border-gray-300 px-4 py-2">Product Name</th>
                        <th class="border border-gray-300 px-4 py-2">Category</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">
                                <img src="{{ asset($product->image) }}" style="width: 50px; height: 35px;" alt="Product Image">
                            </td>

                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $product->category->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">RS:{{ $product->unit_price }}</td>
                            <td class="border border-gray-300 px-4 py-2 flex justify-center items-center space-x-4">
                                <!-- Edit Button -->
                                @can('product-edit')
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-far-edit class="h-5 w-5 text-blue-500 hover:text-blue-700" />
                                    </a> 
                                @endcan
                              

                                <!-- Delete Button -->
                                @can('product-delete')
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 mt-2">
                                            <x-gmdi-delete class="h-5 w-5 text-red-500 hover:text-red-700" />
                                        </button>
                                    </form>
                                @endcan

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcan
        
    </div>
@endsection

@push('scripts')
<script>
    // Clear the search field
    function clearSearch() {
        window.location.href = '{{ route('products.index') }}';
    }
</script>
@endpush
