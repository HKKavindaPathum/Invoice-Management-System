@extends('layouts.app')

@section('content')
    <div class="mx-auto bg-white shadow-lg rounded-lg p-6 mt-1">
        
        <!-- Flex Container for Search, Filter, and Add Invoice Button -->
        <div class="flex justify-between items-center mb-4">
            <!-- Search Bar on the Left -->
            <form action="{{ route('invoices.search') }}" method="GET" class="relative w-1/3">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                    placeholder="Search by Client Name" 
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

                <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Search
                </button>
            </form>

            <!-- Filter Icon and Add Invoice Button on the Right -->
            <div class="flex items-center space-x-4 mt-12">
                <!-- Filter Icon (Small) -->
                <button 
                    onclick="toggleFilter()" 
                    class="p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>

                <!-- Add Invoice Button -->
                <a href="{{ route('invoices.create') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded-lg transition duration-200 ">
                    Add Invoices
                </a>
            </div>

        </div>
        
        <div id="filterForm" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
            <form action="{{ route('invoices.filter') }}" method="GET" class="p-4">
                <!-- Date Range Filter -->
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="start_date" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                        value="{{ request()->get('start_date') }}">
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none"
                        value="{{ request()->get('end_date') }}">
                </div>
    
                <!-- Status Filter -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select 
                        name="status" 
                        id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 outline-none">
                        <option value="">Select Status</option>
                        <option value="unpaid" {{ request()->get('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ request()->get('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partially_paid" {{ request()->get('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="overdue" {{ request()->get('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="processing" {{ request()->get('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    </select>
                </div>
    
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Apply Filters
                </button>

                <!-- Clear Filters Button -->
                <a href="{{ route('invoices.index') }}" 
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-center block mt-2">
                    Clear Filters
                </a>

            </form>
        </div>

        <!-- Display Success Message Using Alert (JavaScript) -->
        @if(session('success'))
            <script>
                alert('{{ session('success') }}');
            </script>
        @endif

        <!-- Table to Display Invoices -->
        <div class="mt-6">
            <table class="w-4/5 border-collapse border border-gray-300 mx-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">INVOICE ID</th>
                        <th class="border border-gray-300 px-4 py-2">CLIENT</th>
                        <th class="border border-gray-300 px-4 py-2">INVOICE DATE</th>
                        <th class="border border-gray-300 px-4 py-2">AMOUNT</th>
                        <th class="border border-gray-300 px-4 py-2">STATUS</th>
                        <th class="border border-gray-300 px-4 py-2">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $invoice->id }}
                                </a>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $invoice->invoice_date }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $invoice->final_amount }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $invoice->status }}</td>
                            <td class="border border-gray-300 px-4 py-4 flex justify-center items-center space-x-4">
                                <!-- Edit Button -->
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <x-far-edit class="h-5 w-5 text-blue-500 hover:text-blue-700" />
                                </a>                               
                                
                                <!-- Delete Button -->
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 mt-2">
                                        <x-gmdi-delete class="h-5 w-5 text-red-500 hover:text-red-700" />
                                    </button>
                                </form> 
                                
                                <!-- Download Button -->
                                <a href="{{ route('invoices.download', $invoice->id) }}" class="text-green-600 hover:text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>

                                <!-- Print Button -->
                                <button onclick="printInvoice('{{ $invoice->id }}')" class="text-purple-600 hover:text-purple-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Clear the search field
    function clearSearch() {
        window.location.href = '{{ route('invoices.index') }}';
    }

    // Toggle filter form visibility
    function toggleFilter() {
        const filterForm = document.getElementById('filterForm');
        filterForm.classList.toggle('hidden');
    }

    // Print Invoice
    function printInvoice(invoiceId) {
        // Open a new window with the invoice details
        const printWindow = window.open(`/invoices/${invoiceId}/print`, '_blank');
        
        // Wait for the window to load and trigger print
        printWindow.onload = function () {
            printWindow.print();
        };
    }
</script>
@endpush