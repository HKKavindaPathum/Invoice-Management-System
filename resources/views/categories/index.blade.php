@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white shadow-xl rounded-xl p-6 mt-6 max-w-7xl">

    <!-- Search + Add Category -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
        <!-- Search Form -->
        <form action="{{ route('categories.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
            <input 
                type="text" 
                name="search" 
                class="w-full px-4 py-2 border border-gray-300 rounded-l-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200"
                placeholder="Search categories..." 
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

        <!-- Add Category Button -->
        @can('category-create')
        <button onclick="openModal()" 
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition duration-300">
            Add Category
        </button> 
        @endcan
    </div>

    <!-- Success Toast -->
    @if(session('success'))
    <div id="successToast" class="fixed top-16 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-4 transition-all duration-500">
        {{ session('success') }}
    </div>
    @endif

    @can('category-list')
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-left border-collapse shadow-md rounded-xl overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4">Category Name</th>
                    <th class="py-3 px-4">Products</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b hover:bg-gray-50 transition duration-150 text-center">
                    <td class="py-3 px-4">
                        <a href="{{ route('categories.show', $category->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $category->name }}
                        </a>
                    </td>
                    <td class="py-3 px-4">{{ $category->products->count() }}</td>
                    <td class="py-3 px-4 flex justify-center items-center gap-2">
                        @can('category-edit')
                        <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" class="text-blue-500 hover:text-blue-700 transition duration-200">
                            <x-far-edit class="h-5 w-5" />
                        </button>
                        @endcan
                        @can('category-delete')
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline-flex items-center">
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
        @foreach($categories as $category)
        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <a href="{{ route('categories.show', $category->id) }}" class="font-medium text-blue-600 hover:text-blue-800 text-lg">
                    {{ $category->name }}
                </a>
                <div class="text-gray-500 text-sm">Products: {{ $category->products->count() }}</div>
            </div>
            <div class="flex gap-2 mt-2 sm:mt-0">
                @can('category-edit')
                <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" class="text-blue-500 hover:text-blue-700">
                    <x-far-edit class="h-5 w-5" />
                </button>
                @endcan
                @can('category-delete')
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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

<!-- Add Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden transition duration-200">
    <div class="bg-white rounded-xl p-6 w-96 shadow-lg transform scale-95 transition-transform duration-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Add New Category</h2>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-medium">Category Name:</label>
                <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none shadow-sm" required>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 px-4 rounded-full shadow-md transition duration-200">Save</button>
                <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-full transition duration-200">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden transition duration-200">
    <div class="bg-white rounded-xl p-6 w-96 shadow-lg transform scale-95 transition-transform duration-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Category</h2>
        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-700 font-medium">Category Name:</label>
                <input type="text" name="name" id="editCategoryName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none shadow-sm" required>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 px-4 rounded-full shadow-md transition duration-200">Save Changes</button>
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-full transition duration-200">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal() { document.getElementById('categoryModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('categoryModal').classList.add('hidden'); }
    function clearSearch() { window.location.href = '{{ route('categories.index') }}'; }
    function openEditModal(id, name) {
        document.getElementById('editCategoryName').value = name;
        document.getElementById('editCategoryForm').action = '/categories/' + id;
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('editCategoryModal').classList.add('hidden'); }

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
