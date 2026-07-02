@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="container mx-auto mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl" role="alert">
            <h3 class="font-bold">Please fix these errors:</h3>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="container mx-auto bg-white shadow-lg rounded-lg p-4 md:p-6 mt-4 md:mt-6 max-w-7xl">
    <h2 class="text-lg md:text-xl font-bold mb-4 md:mb-6 text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">Edit Invoice #{{ $invoice->id }}</h2>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Header Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
            <div class="space-y-2 md:col-span-1">
                <label class="block text-sm font-semibold text-slate-600">Invoice Date</label>
                <input type="date" name="invoice_date" value="{{ $invoice->invoice_date }}"
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
            </div>
            <div class="space-y-2 md:col-span-1">
                <label class="block text-sm font-semibold text-slate-600">Due Date</label>
                <input type="date" name="due_date" value="{{ $invoice->due_date }}"
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-600">Customer (Client)</label>
                <div class="flex items-center gap-2">
                    <select id="client_select" name="client_id" required
                            class="flex-1 max-w-md px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                        <option value="">Select Customer</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }} @if($client->company_name) ({{ $client->company_name }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="openClientModalBtn" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow-sm transition duration-200 text-sm outline-none shrink-0">
                        Add Client
                    </button>
                </div>
            </div>

            <div class="space-y-2 md:col-span-1">
                <label class="block text-sm font-semibold text-slate-600">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partially_paid" {{ $invoice->status == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="processing" {{ $invoice->status == 'processing' ? 'selected' : '' }}>Processing</option>
                </select>
            </div>
        </div>



        <!-- Products Section -->
        <div class="space-y-4 pt-6 border-t border-slate-100">
            <h3 class="text-md font-bold text-slate-700 uppercase tracking-wider">Products</h3>
            <div id="products-container" class="space-y-3">
                @if($invoice->productInvoices->count() > 0)
                    @foreach($invoice->productInvoices as $index => $productInvoice)
                    <div class="product-item border border-slate-150 p-4 rounded-2xl bg-slate-50/50">
                        <div class="flex flex-col md:flex-row gap-3 items-end w-full">
                            <div class="w-full md:w-52 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Category</label>
                                <select class="category-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $productInvoice->product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:flex-1 min-w-[200px] space-y-1">
                                <label class="block text-xs font-semibold text-slate-500">Product Item</label>
                                <select name="products[{{ $index }}][product_id]" 
                                        class="product-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" data-category="{{ $product->category_id }}" {{ $productInvoice->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-32 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Unit Price</label>
                                <input type="number" name="products[{{ $index }}][unit_price]" 
                                       value="{{ $productInvoice->product->unit_price }}"
                                       class="unit-price w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                       readonly>
                            </div>
                            
                            <div class="w-full md:w-16 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Quantity</label>
                                <input type="number" name="products[{{ $index }}][quantity]" 
                                       value="{{ $productInvoice->quantity }}"
                                       class="quantity w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                       min="1">
                            </div>
                            
                            <div class="w-full md:w-16 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Days</label>
                                <input type="number" name="products[{{ $index }}][days]" 
                                       value="{{ $productInvoice->days }}"
                                       class="days w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                       min="1">
                            </div>
                            
                            <div class="w-full md:w-32 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Amount</label>
                                <input type="number" name="products[{{ $index }}][amount]" 
                                       value="{{ $productInvoice->amount }}"
                                       class="amount w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                       readonly>
                            </div>
                            
                            <div class="w-full md:w-20 flex-shrink-0">
                                <button type="button" 
                                        class="remove-product w-full py-1.5 px-2 border border-transparent text-xs font-semibold rounded-xl text-white bg-red-500 hover:bg-red-600 shadow-md shadow-red-500/10 transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Fallback row for cloning -->
                    <div class="product-item border border-slate-150 p-4 rounded-2xl bg-slate-50/50">
                        <div class="flex flex-col md:flex-row gap-3 items-end w-full">
                            <div class="w-full md:w-52 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Category</label>
                                <select class="category-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:flex-1 min-w-[200px] space-y-1">
                                <label class="block text-xs font-semibold text-slate-500">Product Item</label>
                                <select name="products[0][product_id]" 
                                        class="product-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" data-category="{{ $product->category_id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-32 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Unit Price</label>
                                <input type="number" name="products[0][unit_price]" 
                                       class="unit-price w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                       readonly>
                            </div>
                            
                            <div class="w-full md:w-16 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Quantity</label>
                                <input type="number" name="products[0][quantity]" 
                                       class="quantity w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                       value="1" min="1">
                            </div>
                            
                            <div class="w-full md:w-16 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Days</label>
                                <input type="number" name="products[0][days]" 
                                       class="days w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                       value="1" min="1">
                            </div>
                            
                            <div class="w-full md:w-32 space-y-1 flex-shrink-0">
                                <label class="block text-xs font-semibold text-slate-500">Amount</label>
                                <input type="number" name="products[0][amount]" 
                                       class="amount w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                       readonly>
                            </div>
                            
                            <div class="w-full md:w-20 flex-shrink-0">
                                <button type="button" 
                                        class="remove-product w-full py-1.5 px-2 border border-transparent text-xs font-semibold rounded-xl text-white bg-red-500 hover:bg-red-600 shadow-md shadow-red-500/10 transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <button type="button" id="add-product" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/10 transition outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Product
            </button>
        </div>

        <!-- Totals Section -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 pt-6 border-t border-slate-100">
            <!-- Left 1/2: Subtotal, Discount Type, Discount Value, Final Amount -->
            <div class="md:col-span-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Subtotal -->
                    <div class="space-y-1 sm:col-span-1">
                        <label class="block text-sm font-semibold text-slate-600">Subtotal</label>
                        <input type="number" name="total_amount" value="{{ $invoice->total_amount }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 font-medium outline-none" 
                               readonly>
                    </div>
                    <!-- Spacer to push next elements to new row -->
                    <div class="hidden sm:block"></div>

                    <!-- Discount Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-600">Discount Type</label>
                        <select id="discount-type" name="discount_type" 
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white">
                            <option value="percentage" {{ $invoice->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ $invoice->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount (RS)</option>
                        </select>
                    </div>
                    
                    <!-- Discount Value -->
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-600">Discount Value</label>
                        <input type="number" id="discount-value" name="discount" value="{{ $invoice->discount }}" placeholder="0" 
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white">
                    </div>

                    <!-- Final Amount -->
                    <div class="space-y-1 sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-600">Final Amount</label>
                        <input type="number" name="final_amount" value="{{ $invoice->final_amount }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 font-bold text-blue-600 text-lg outline-none" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Right 1/2: Invoice Notes -->
            <div class="md:col-span-6 flex flex-col justify-between">
                <div class="space-y-1 h-full flex flex-col">
                    <label class="block text-sm font-semibold text-slate-600">Invoice Notes</label>
                    <textarea name="note" placeholder="Enter customer notes or warranty details..."
                              class="w-full flex-grow px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition min-h-[120px] md:min-h-0">{{ $invoice->note }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/20 transition outline-none">
                Update Invoice
            </button>
        </div>
    </form>
</div>

<!-- Add Client Modal -->
<div id="addClientModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300"></div>

    <!-- Modal Content Wrapper -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-2xl w-full text-slate-800 dark:text-slate-200 transform transition-all duration-300 scale-95 opacity-0 modal-box">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Add New Customer</h3>
                <button type="button" class="closeClientModalBtn text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Error Banner -->
            <div id="modalErrors" class="hidden mb-4 p-4 bg-red-100 dark:bg-red-950/40 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1"></ul>
            </div>

            <!-- Client Form -->
            <form id="ajaxClientForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Title *</label>
                        <select name="title" required class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                            <option value="">Select Title</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Dr.">Dr.</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Country</label>
                        <input type="text" name="country" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Passport Number</label>
                        <input type="text" name="passport_no" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Company Name</label>
                        <input type="text" name="company_name" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Mobile Number</label>
                        <input type="text" name="mobile_no" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Email</label>
                        <input type="email" name="email" placeholder="Leave empty for auto-generated" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Address</label>
                        <textarea name="address" rows="2" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition"></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Note</label>
                        <textarea name="note" rows="2" class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button type="button" class="closeClientModalBtn px-5 py-2.5 border border-slate-200 dark:border-slate-700 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 transition outline-none">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-sm font-semibold rounded-xl text-white shadow-md shadow-blue-500/10 transition outline-none">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Open Client Modal
        $("#openClientModalBtn").click(function() {
            $("#modalErrors").addClass("hidden").find("ul").empty();
            $("#ajaxClientForm")[0].reset();
            $("#addClientModal").removeClass("hidden");
            setTimeout(function() {
                $(".modal-box").removeClass("scale-95 opacity-0").addClass("scale-100 opacity-100");
            }, 50);
        });

        // Close Client Modal
        $(".closeClientModalBtn").click(function() {
            $(".modal-box").removeClass("scale-100 opacity-100").addClass("scale-95 opacity-0");
            setTimeout(function() {
                $("#addClientModal").addClass("hidden");
            }, 300);
        });

        // Handle AJAX Client Submission
        $("#ajaxClientForm").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let saveBtn = $(this).find('button[type="submit"]');
            saveBtn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('clients.store.ajax') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    saveBtn.prop('disabled', false).text('Save Customer');
                    if (response.success) {
                        // Add new client to select dropdown
                        let client = response.client;
                        let displayName = `${client.title} ${client.first_name} ${client.last_name}`;
                        if (client.company_name) {
                            displayName += ` (${client.company_name})`;
                        }
                        
                        let newOption = new Option(displayName, client.id, true, true);
                        $("#client_select").append(newOption).trigger('change');

                        // Close modal
                        $(".closeClientModalBtn").click();

                        // Show success alert/toast
                        let toastHtml = `
                            <div id="successToast" class="fixed top-20 right-6 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 flex items-center gap-2 text-sm border border-slate-800">
                                <span class="text-emerald-400">✓</span>
                                Customer added successfully!
                            </div>
                        `;
                        $("body").append(toastHtml);
                        setTimeout(function() {
                            $("#successToast").fadeOut(500, function() { $(this).remove(); });
                        }, 4000);
                    }
                },
                error: function(xhr) {
                    saveBtn.prop('disabled', false).text('Save Customer');
                    let errorsDiv = $("#modalErrors");
                    let errorsUl = errorsDiv.find("ul");
                    errorsUl.empty();
                    
                    if (xhr.status === 422) {
                        let responseErrors = xhr.responseJSON.errors;
                        $.each(responseErrors, function(key, messages) {
                            $.each(messages, function(index, message) {
                                errorsUl.append(`<li>${message}</li>`);
                            });
                        });
                    } else {
                        errorsUl.append(`<li>Something went wrong. Please check inputs.</li>`);
                    }
                    errorsDiv.removeClass("hidden");
                }
            });
        });

        // Store all product options as a master copy
        let productOptionsMaster = [];
        $(".product-select:first option").each(function() {
            if ($(this).val()) {
                productOptionsMaster.push({
                    value: $(this).val(),
                    text: $(this).text().trim(),
                    price: $(this).data("price"),
                    category: $(this).data("category")
                });
            }
        });

        // Filter products when category changes
        $(document).on("change", ".category-select", function() {
            let row = $(this).closest(".product-item");
            let catId = $(this).val();
            let productSelect = row.find(".product-select");
            
            let currentProductId = productSelect.val();
            
            productSelect.empty().append('<option value="">Select Product</option>');
            
            productOptionsMaster.forEach(function(opt) {
                if (!catId || opt.category == catId) {
                    let isSelected = (opt.value == currentProductId);
                    let newOpt = $('<option></option>')
                        .val(opt.value)
                        .text(opt.text)
                        .attr('data-price', opt.price)
                        .attr('data-category', opt.category);
                    if (isSelected) {
                        newOpt.prop('selected', true);
                    }
                    productSelect.append(newOpt);
                }
            });
            
            if (productSelect.val() !== currentProductId) {
                productSelect.trigger("change");
            }
        });

        // Add new product row
        $("#add-product").click(function() {
            let newProduct = $(".product-item:first").clone();
            let index = $(".product-item").length;

            newProduct.find("input").val("");
            newProduct.find("select").val("");
            newProduct.find(".unit-price, .amount").val("0");
            newProduct.find(".quantity, .days").val("1");

            // Re-populate the product dropdown for the new row with all options
            let productSelect = newProduct.find(".product-select");
            productSelect.empty().append('<option value="">Select Product</option>');
            productOptionsMaster.forEach(function(opt) {
                productSelect.append($('<option></option>')
                    .val(opt.value)
                    .text(opt.text)
                    .attr('data-price', opt.price)
                    .attr('data-category', opt.category));
            });

            newProduct.find('input, select').each(function() {
                let name = $(this).attr("name");
                if (name) {
                    name = name.replace(/products\[\d+\]/, `products[${index}]`);
                    $(this).attr("name", name);
                }
            });

            newProduct.hide().appendTo("#products-container").fadeIn(200);
        });

        // Update unit price on product selection
        $(document).on("change", ".product-select", function() {
            let row = $(this).closest(".product-item");
            let price = $(this).find("option:selected").data("price") || 0;
            row.find(".unit-price").val(price);
            updateRowAmount(row);
        });

        // Remove product row
        $(document).on("click", ".remove-product", function() {
            let row = $(this).closest(".product-item");
            if ($(".product-item").length > 1) {
                row.fadeOut(200, function() {
                    $(this).remove();
                    updateTotalAmount();
                });
            } else {
                row.find("input").val("");
                row.find("select").val("");
                row.find(".unit-price, .amount").val("0");
                row.find(".quantity, .days").val("1");
                updateTotalAmount();
            }
        });

        // Update amount dynamically when quantity or days change
        $(document).on("input", ".quantity, .days", function() {
            let val = parseFloat($(this).val());
            if (isNaN(val) || val < 1) {
                $(this).val("1");
            }
            updateRowAmount($(this).closest(".product-item"));
        });

        function updateRowAmount(row) {
            let unitPrice = parseFloat(row.find(".unit-price").val()) || 0;
            let quantity = parseFloat(row.find(".quantity").val()) || 1;
            let days = parseFloat(row.find(".days").val()) || 1;
            let amount = unitPrice * quantity * days;

            row.find(".amount").val(amount.toFixed(2));
            updateTotalAmount();
        }

        // Calculate total amount of all items
        function updateTotalAmount() {
            let totalAmount = 0;
            $(".amount").each(function() {
                totalAmount += parseFloat($(this).val()) || 0;
            });

            $("input[name='total_amount']").val(totalAmount.toFixed(2));
            calculateFinalAmount(totalAmount);
        }

        // Calculate final amount after applying discount
        function calculateFinalAmount(totalAmount) {
            let discountType = $("#discount-type").val();
            let discountValue = parseFloat($("#discount-value").val()) || 0;
            let finalAmount = totalAmount;

            if (discountType === "percentage") {
                discountValue = Math.min(100, Math.max(0, discountValue));
                $("#discount-value").val(discountValue);
                finalAmount -= (totalAmount * discountValue / 100);
            } else if (discountType === "fixed") {
                discountValue = Math.min(totalAmount, Math.max(0, discountValue));
                $("#discount-value").val(discountValue);
                finalAmount -= discountValue;
            }

            finalAmount = Math.max(0, finalAmount).toFixed(2);
            $("input[name='final_amount']").val(finalAmount);
        }

        // Trigger discount calculation
        $("#discount-type, #discount-value").on("change input", function() {
            let totalAmount = parseFloat($("input[name='total_amount']").val()) || 0;
            calculateFinalAmount(totalAmount);
        });
    });
</script>
@endpush