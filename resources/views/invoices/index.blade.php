@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white shadow-xl rounded-xl p-6 mt-6 max-w-7xl">

    <!-- Search + Add Invoice -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
        <!-- Search Form -->
        <form action="{{ route('invoices.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
            <input 
                type="text" 
                name="search" 
                class="w-full px-4 py-2 border border-gray-300 rounded-l-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition duration-200"
                placeholder="Search by Client Name" 
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

        <!-- Add Invoice Button -->
        @can('invoice-create')
        <a href="{{ route('invoices.create') }}" 
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition duration-300 mt-4 md:mt-0">
            Add Invoice
        </a>
        @endcan
    </div>

    <!-- Success Toast -->
    @if(session('success'))
    <div id="successToast" class="fixed top-16 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-4 transition-all duration-500">
        {{ session('success') }}
    </div>
    @endif

    @can('invoice-list')
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-left border-collapse shadow-md rounded-xl overflow-hidden">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4">INVOICE ID</th>
                    <th class="py-3 px-4">CLIENT</th>
                    <th class="py-3 px-4">INVOICE DATE</th>
                    <th class="py-3 px-4">AMOUNT</th>
                    <th class="py-3 px-4">STATUS</th>
                    <th class="py-3 px-4 text-center">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr class="border-b hover:bg-gray-50 transition duration-150 text-center">
                    <td class="py-3 px-4">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $invoice->id }}
                        </a>
                    </td>
                    <td class="py-3 px-4">{{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</td>
                    <td class="py-3 px-4">{{ $invoice->invoice_date }}</td>
                    <td class="py-3 px-4">{{ $invoice->final_amount }}</td>
                    <td class="py-3 px-4">{{ ucfirst($invoice->status) }}</td>
                    <td class="py-3 px-4 flex justify-center items-center gap-2">
                        @can('invoice-edit')
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-200">
                            <x-far-edit class="h-5 w-5" />
                        </a>
                        @endcan

                        @can('invoice-delete')
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?')" class="inline-flex items-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition duration-200">
                                <x-gmdi-delete class="h-5 w-5" />
                            </button>
                        </form>
                        @endcan

                        @can('invoice-download')
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="text-green-500 hover:text-green-700 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                        @endcan

                        @can('invoice-print')
                        <button onclick="printInvoice('{{ $invoice->id }}')" class="text-purple-500 hover:text-purple-700 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @foreach($invoices as $invoice)
        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <a href="{{ route('invoices.show', $invoice->id) }}" class="font-medium text-blue-600 hover:text-blue-800 text-lg">
                    Invoice #{{ $invoice->id }}
                </a>
                <div class="text-gray-500 text-sm">
                    Client: {{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}
                </div>
                <div class="text-gray-500 text-sm">Date: {{ $invoice->invoice_date }}</div>
                <div class="text-gray-500 text-sm">Amount: {{ $invoice->final_amount }}</div>
                <div class="text-gray-500 text-sm">Status: {{ ucfirst($invoice->status) }}</div>
            </div>
            <div class="flex gap-2 mt-2 sm:mt-0">
                @can('invoice-edit')
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-blue-500 hover:text-blue-700">
                    <x-far-edit class="h-5 w-5" />
                </a>
                @endcan

                @can('invoice-delete')
                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <x-gmdi-delete class="h-5 w-5" />
                    </button>
                </form>
                @endcan

                @can('invoice-download')
                <a href="{{ route('invoices.download', $invoice->id) }}" class="text-green-500 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
                @endcan

                @can('invoice-print')
                <button onclick="printInvoice('{{ $invoice->id }}')" class="text-purple-500 hover:text-purple-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </button>
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
        window.location.href = '{{ route('invoices.index') }}';
    }

    function printInvoice(invoiceId) {
        const printWindow = window.open(`/invoices/${invoiceId}/print`, '_blank');
        printWindow.onload = function () {
            printWindow.print();
        };
    }

    // Success Toast
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
