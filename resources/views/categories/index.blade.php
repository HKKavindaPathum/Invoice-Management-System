@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">

        <!-- Search + Add Category -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
            <!-- Search Form -->
            <form action="{{ route('categories.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-l-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition duration-200 text-sm"
                    placeholder="Search categories..." 
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

            <!-- Add Category Button -->
            @can('category-create')
            <button onclick="openModal()" 
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-blue-500/10 transition duration-200 text-sm w-full md:w-auto mt-2 md:mt-0 outline-none">
                Add Category
            </button> 
            @endcan
        </div>

        <!-- Success Toast -->
        @if(session('success'))
        <div id="successToast" class="fixed top-20 right-6 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-500 flex items-center gap-2 text-sm border border-slate-800">
            <span class="text-emerald-400">✓</span>
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Toast -->
        @if(session('error'))
        <div id="errorToast" class="fixed top-20 right-6 bg-red-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-500 flex items-center gap-2 text-sm border border-red-800">
            <span class="text-white font-bold">⚠</span>
            {{ session('error') }}
        </div>
        @endif

        @can('category-list')
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Category Name</th>
                        <th class="py-3.5 px-4 font-semibold">Products</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($categories as $category)
                    <tr class="hover:bg-slate-50/30 transition duration-150">
                        <td class="py-4 px-4 font-semibold text-slate-900">
                            <a href="{{ route('categories.show', $category->id) }}" class="hover:text-slate-700 hover:underline transition">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">{{ $category->products->count() }}</td>
                        <td class="py-4 px-4 flex justify-center items-center gap-3">
                            @can('category-edit')
                            <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" class="text-slate-400 hover:text-slate-900 transition-colors duration-200">
                                <x-far-edit class="h-4.5 w-4.5" />
                            </button>
                            @endcan
                            @can('category-delete')
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline-flex items-center">
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
                <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-sm" required>
            </div>
            <div class="flex justify-between mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-xl shadow-md shadow-blue-500/10 transition duration-200">Save</button>
                <button type="button" onclick="closeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-xl transition duration-200">Cancel</button>
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
                <input type="text" name="name" id="editCategoryName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-sm" required>
            </div>
            <div class="flex justify-between mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-xl shadow-md shadow-blue-500/10 transition duration-200">Save Changes</button>
                <button type="button" onclick="closeEditModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-xl transition duration-200">Cancel</button>
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

        const errorToast = document.getElementById('errorToast');
        if (errorToast) {
            errorToast.classList.remove('opacity-0', 'translate-y-4');
            errorToast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                errorToast.classList.add('opacity-0', 'translate-y-4');
                errorToast.classList.remove('opacity-100', 'translate-y-0');
            }, 5000);
        }
    });
</script>
@endpush
