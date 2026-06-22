@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">

        <!-- Search + Add Invoice -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative">
            <!-- Search Form -->
            <form action="{{ route('invoices.search') }}" method="GET" class="flex w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    name="search" 
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-l-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition duration-200 text-sm"
                    placeholder="Search by Client Name" 
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

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-r-xl shadow-sm transition duration-200 text-sm outline-none">
                    Search
                </button>
            </form>

            <!-- Add Invoice Button -->
            @can('invoice-create')
            <a href="{{ route('invoices.create') }}" 
                class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-indigo-600/10 transition duration-200 text-sm w-full md:w-auto mt-2 md:mt-0">
                Add Invoice
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

        @can('invoice-list')
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">INVOICE ID</th>
                        <th class="py-3.5 px-4 font-semibold">CLIENT</th>
                        <th class="py-3.5 px-4 font-semibold">INVOICE DATE</th>
                        <th class="py-3.5 px-4 font-semibold">AMOUNT</th>
                        <th class="py-3.5 px-4 font-semibold">STATUS</th>
                        <th class="py-3.5 px-4 font-semibold text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-slate-50/30 transition duration-150">
                        <td class="py-4 px-4 font-semibold text-indigo-600">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="hover:text-indigo-855 transition">
                                #{{ $invoice->id }}
                            </a>
                        </td>
                        <td class="py-4 px-4 text-slate-700 font-medium">
                            {{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}
                        </td>
                        <td class="py-4 px-4 text-slate-500">{{ $invoice->invoice_date }}</td>
                        <td class="py-4 px-4 font-semibold text-slate-800">{{ $invoice->final_amount }}</td>
                        <td class="py-4 px-4">
                            @switch(strtolower($invoice->status))
                                @case('paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100/50">{{ ucfirst($invoice->status) }}</span>
                                    @break
                                @case('unpaid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100/50">{{ ucfirst($invoice->status) }}</span>
                                    @break
                                @case('partially paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100/50">{{ ucfirst($invoice->status) }}</span>
                                    @break
                                @case('overdue')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-100/50">{{ ucfirst($invoice->status) }}</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-100/50">{{ ucfirst($invoice->status) }}</span>
                            @endswitch
                        </td>
                        <td class="py-4 px-4 flex justify-center items-center gap-3">
                            @can('invoice-edit')
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors duration-200" title="Edit">
                                <x-far-edit class="h-4.5 w-4.5" />
                            </a>
                            @endcan

                            @can('invoice-delete')
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?')" class="inline-flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors duration-200" title="Delete">
                                    <x-gmdi-delete class="h-5 w-5" />
                                </button>
                            </form>
                            @endcan

                            @can('invoice-download')
                            <a href="{{ route('invoices.download', $invoice->id) }}" class="text-slate-400 hover:text-emerald-600 transition-colors duration-200" title="Download">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                            @endcan

                            @can('invoice-print')
                            <button onclick="printInvoice('{{ $invoice->id }}')" class="text-slate-400 hover:text-purple-600 transition-colors duration-200" title="Print">
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
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-[0_2px_8px_rgba(0,0,0,0.01)] flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="font-bold text-indigo-600 hover:text-indigo-850 transition">
                            Invoice #{{ $invoice->id }}
                        </a>
                        <div class="text-slate-700 font-medium text-sm mt-1">
                            {{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}
                        </div>
                        <div class="text-slate-400 text-xs mt-1">Date: {{ $invoice->invoice_date }}</div>
                    </div>
                    <div>
                        @switch(strtolower($invoice->status))
                            @case('paid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ ucfirst($invoice->status) }}</span>
                                @break
                            @case('unpaid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">{{ ucfirst($invoice->status) }}</span>
                                @break
                            @case('partially paid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">{{ ucfirst($invoice->status) }}</span>
                                @break
                            @case('overdue')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-100">{{ ucfirst($invoice->status) }}</span>
                                @break
                            @default
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-100">{{ ucfirst($invoice->status) }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-50">
                    <div class="text-slate-900 font-extrabold text-base">{{ $invoice->final_amount }}</div>
                    <div class="flex gap-3">
                        @can('invoice-edit')
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-slate-400 hover:text-indigo-600 transition duration-200">
                            <x-far-edit class="h-5 w-5" />
                        </a>
                        @endcan

                        @can('invoice-delete')
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition duration-200">
                                <x-gmdi-delete class="h-5 w-5" />
                            </button>
                        </form>
                        @endcan

                        @can('invoice-download')
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="text-slate-400 hover:text-emerald-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                        @endcan

                        @can('invoice-print')
                        <button onclick="printInvoice('{{ $invoice->id }}')" class="text-slate-400 hover:text-purple-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endcan
    </div>
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
