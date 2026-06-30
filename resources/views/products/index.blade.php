@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">

        <!-- Search + Add Product -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <!-- Search Form -->
            <form action="{{ route('products.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-l-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition duration-200 text-sm"
                    placeholder="Search products..." 
                    value="{{ request()->get('search') }}">

                <!-- Clear Icon -->
                @if(request()->get('search'))
                    <button 
                        type="button" 
                        onclick="clearSearch()" 
                        class="absolute right-28 top-3 text-slate-400 hover:text-slate-600 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif  

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-r-xl shadow-sm transition duration-200 text-sm outline-none">
                    Search
                </button>
            </form>

            <!-- Add Product Button -->
            @can('product-create')
            <a href="{{ route('products.create') }}" 
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-blue-500/10 transition duration-200 text-sm w-full md:w-auto mt-2 md:mt-0">
                Add Product
            </a> 
            @endcan
        </div>

        <!-- Success Toast -->
        @if(session('success'))
        <div id="successToast" class="fixed top-20 right-6 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-500 flex items-center gap-2 text-sm border border-slate-800">
            <span class="text-emerald-400">✓</span>
            {{ session('success') }}
        </div>
        @endif

        <!-- Desktop Table -->
        @can('product-list')
        <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold text-center w-24">Image</th>
                        <th class="py-3.5 px-4 font-semibold">Product Name</th>
                        <th class="py-3.5 px-4 font-semibold">Category</th>
                        <th class="py-3.5 px-4 font-semibold">Price</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50/30 transition duration-150">
                        <td class="py-3 px-4 text-center">
                            <img src="{{ asset($product->image) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-100 mx-auto" alt="Product Image">
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-900">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-slate-700 hover:underline transition">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">{{ $product->category->name }}</td>
                        <td class="py-4 px-4 font-semibold text-slate-800">RS: {{ $product->unit_price }}</td>
                        <td class="py-4 px-4 flex justify-center items-center gap-3">
                            @can('product-edit')
                            <a href="{{ route('products.edit', $product->id) }}" class="text-slate-400 hover:text-slate-900 transition-colors duration-200">
                                <x-far-edit class="h-4.5 w-4.5" />
                            </a>
                            @endcan
                            @can('product-delete')
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors duration-200">
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
                    <div class="mt-1">
                        @if($product->quantity >= 5)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200/50">
                                In Stock ({{ $product->quantity }})
                            </span>
                        @elseif($product->quantity > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200/50">
                                Low Stock ({{ $product->quantity }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200/50">
                                Out of Stock
                            </span>
                        @endif
                    </div>
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

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-6 px-2">
            {{ $products->links() }}
        </div>
    @endif
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
