@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-slate-50/50 p-6">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 max-w-4xl w-full">

            <h1 class="text-3xl font-bold text-center text-slate-800 mb-8 uppercase tracking-wider">
                Invoice Details
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-6">
                <!-- Left Column -->
                <div>
                    <p class="mb-2 text-slate-600"><strong class="text-slate-800">Invoice ID:</strong> #{{ $invoice->id }}</p>
                    <p class="mb-2 text-slate-600"><strong class="text-slate-800">Client Name:</strong> {{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                    @if($invoice->client->company_name)
                        <p class="mb-2 text-slate-600"><strong class="text-slate-800">Company:</strong> {{ $invoice->client->company_name }}</p>
                    @endif
                </div>

                <!-- Right Column -->
                <div>
                    <div class="grid grid-cols-2 gap-4">
                        <p class="text-slate-600"><strong class="text-slate-800">Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                        <p class="text-slate-600"><strong class="text-slate-800">Due Date:</strong> {{ $invoice->due_date }}</p>
                    </div>
                    <p class="mt-4 text-slate-600">
                        <strong class="text-slate-800">Status:</strong>
                        @switch(strtolower($invoice->status))
                            @case('paid')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200/50">Paid</span>
                                @break
                            @case('unpaid')
                            @case('partially_paid')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200/50">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
                                @break
                            @case('overdue')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200/50">Overdue</span>
                                @break
                            @default
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200/50">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Services Listing -->
            @if($invoice->serviceInvoices->count() > 0)
            <div class="mt-6">
                <h3 class="text-md font-bold text-slate-700 uppercase tracking-wider">Services</h3>
                <div class="overflow-x-auto border border-slate-100 rounded-xl mt-2">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Service</th>
                                <th class="py-3 px-4">Rate</th>
                                <th class="py-3 px-4">Qty</th>
                                <th class="py-3 px-4">Days</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($invoice->serviceInvoices as $svc)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-medium text-slate-900">{{ $svc->service->name }}</td>
                                <td class="py-3.5 px-4 text-slate-600">RS: {{ number_format($svc->service->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $svc->quantity }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $svc->days }}</td>
                                <td class="py-3.5 px-4 text-right font-semibold text-slate-800">RS: {{ number_format($svc->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Products Listing -->
            @if($invoice->productInvoices->count() > 0)
            <div class="mt-6">
                <h3 class="text-md font-bold text-slate-700 uppercase tracking-wider">Products</h3>
                <div class="overflow-x-auto border border-slate-100 rounded-xl mt-2">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Product</th>
                                <th class="py-3 px-4">Unit Price</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4">Days</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($invoice->productInvoices as $prod)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-medium text-slate-900">{{ $prod->product->name }}</td>
                                <td class="py-3.5 px-4 text-slate-600">RS: {{ number_format($prod->product->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $prod->quantity }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $prod->days }}</td>
                                <td class="py-3.5 px-4 text-right font-semibold text-slate-800">RS: {{ number_format($prod->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="mt-8 border-t border-slate-100 pt-6 flex flex-col items-end space-y-2">
                <p class="text-slate-600"><strong class="text-slate-800">Total Amount:</strong> RS: {{ number_format($invoice->total_amount, 2) }}</p>
                <p class="text-slate-600"><strong class="text-slate-800">Discount:</strong> 
                    @if($invoice->discount_type === 'percentage')
                        {{ number_format($invoice->discount) }}%
                    @else
                        RS: {{ number_format($invoice->discount, 2) }}
                    @endif
                </p>
                <p class="text-lg text-slate-800 font-bold"><strong class="text-blue-600">Final Amount:</strong> RS: {{ number_format($invoice->final_amount, 2) }}</p>
            </div>

            @if($invoice->note)
            <div class="mt-6 bg-slate-50 border border-slate-100 p-4 rounded-xl">
                <strong class="text-slate-700 block mb-1">Invoice Notes</strong>
                <p class="text-slate-600 text-sm break-words">{{ $invoice->note }}</p>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                <a href="{{ route('invoices.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 px-6 rounded-xl font-bold transition">Back</a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-6 rounded-xl font-bold transition">Edit</a>
            </div>

        </div>
    </div>
@endsection
