@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-50 to-purple-50 p-6">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Invoice Details
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div>
                    <p class="mb-4"><strong>Invoice ID:</strong> {{ $invoice->id }}</p>
                    <p class="mb-4"><strong>Client:</strong> {{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="grid grid-cols-2 gap-4">
                        <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                        <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                    </div>
                    <p class="mt-4">
                        <strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-white 
                            {{ $invoice->status === 'paid' ? 'bg-green-500' : ($invoice->status === 'unpaid' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold">Products</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 mt-2">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="border p-2">Product</th>
                                <th class="border p-2">Unit Price</th>
                                <th class="border p-2">Quantity</th>
                                <th class="border p-2">Days</th>
                                <th class="border p-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->productInvoices as $product)
                            <tr class="odd:bg-gray-100">
                                <td class="border p-2">{{ $product->product->name }}</td>
                                <td class="border p-2">RS: {{ number_format($product->product->unit_price, 2) }}</td>
                                <td class="border p-2">{{ $product->quantity }}</td>
                                <td class="border p-2">{{ $product->days }}</td>
                                <td class="border p-2">RS: {{ number_format($product->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <p><strong>Total Amount:</strong> RS: {{ number_format($invoice->total_amount, 2) }}</p>
                
                <p><strong>Discount:</strong> 
                    @if($invoice->discount_type === 'percentage')
                        {{ number_format($invoice->discount) }}%
                    @else
                        RS: {{ number_format($invoice->discount, 2) }}
                    @endif
                </p>

                <p><strong>Final Amount:</strong> RS: {{ number_format($invoice->final_amount, 2) }}</p>
            </div>

            <div class="mt-6">
                <strong>Note:</strong>
                <p class="border p-2 rounded bg-gray-50">{{ $invoice->note ?? 'No additional notes' }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex space-x-2">
                <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg">Back</a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Edit</a>
                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg">Delete</button>
                </form>
            </div>

        </div>
    </div>
@endsection
