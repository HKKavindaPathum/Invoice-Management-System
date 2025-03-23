@extends('layouts.app')

@section('content')
    <div class="mx-auto  bg-white shadow-lg rounded-lg p-6 mt-1">
        
        <!-- Flex Container for Search and Add Category Button -->
        <div class="flex justify-between items-center mb-4">
            <!-- Search Form -->
            <form action="{{ route('categories.search') }}" method="GET" class="relative w-1/4">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                    placeholder="Search categories..." 
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

            <!-- Button to Open Modal (Add Category) -->
            <button onclick="openModal()" 
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded-lg transition duration-200 mt-12">
                Add Category
            </button>
        </div>
        
        <!-- Display Success Message Using Alert (JavaScript) -->
        @if(session('success'))
            <script>
                alert('{{ session('success') }}');
            </script>
        @endif

        <!-- Table to Display Categories -->
        <div class="mt-6">
            <table class="w-3/4 border-collapse border border-gray-300 mx-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Category Name</th>
                        <th class="border border-gray-300 px-4 py-2">Product</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('categories.show', $category->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $category->products->count() }}</td>
                            <td class="border border-gray-300 px-4 py-2 flex justify-center items-center space-x-4">

                                <!-- Edit Button -->
                                <a href="#" onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" class="text-blue-600 hover:text-blue-800">
                                    <x-far-edit class="h-5 w-5 text-blue-500 hover:text-blue-700" />
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline-block">
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
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Category</h2>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="category" class="block text-gray-700 font-medium">Category Name:</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Save Category
                    </button>
                    <button type="button" onclick="closeModal()" class="bg-gray-400 hover:bg-gray-500 text-black font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Category</h2>

            <form id="editCategoryForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="category" class="block text-gray-700 font-medium">Category Name:</label>
                    <input type="text" name="name" id="editCategoryName" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Open the Add Category modal
    function openModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    // Close the Add Category modal
    function closeModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    // Clear the search field
    function clearSearch() {
        window.location.href = '{{ route('categories.index') }}';
    }

    // Open the Edit Category modal with prefilled data
    function openEditModal(id, name) {
        document.getElementById('editCategoryName').value = name;
        document.getElementById('editCategoryForm').action = '/categories/' + id;
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    // Close the Edit Category modal
    function closeEditModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
</script>
@endpush
