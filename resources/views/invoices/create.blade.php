@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="container mx-auto mb-4 max-w-6xl">
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

<div class="container mx-auto bg-white shadow-lg rounded-lg p-4 md:p-6 mt-4 md:mt-6 max-w-6xl">
    <h2 class="text-lg md:text-xl font-bold mb-4 md:mb-6 text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">Create Invoice</h2>

    <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Header Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-600">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice-date" 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-600">Due Date</label>
                <input type="date" name="due_date" 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-600">Customer (Client)</label>
                <select name="client_id" required
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    <option value="">Select Customer</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->title }} {{ $client->first_name }} {{ $client->last_name }} @if($client->company_name) ({{ $client->company_name }}) @endif</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-600">Status</label>
                <select name="status" 
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition">
                    <option value="unpaid">Unpaid (Pending)</option>
                    <option value="paid">Paid</option>
                    <option value="partially_paid">Partially Paid</option>
                    <option value="overdue">Overdue</option>
                    <option value="processing">Processing</option>
                </select>
            </div>
        </div>

        <!-- Services Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-md font-bold text-slate-700 uppercase tracking-wider">Services</h3>
            <div id="services-container" class="space-y-3">
                <!-- Service row template / first row -->
                <div class="service-item border border-slate-150 p-4 rounded-2xl bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <div class="md:col-span-4 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Service Description</label>
                            <select name="services[0][service_id]" 
                                    class="service-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->unit_price }}">{{ $service->name }} (RS: {{ number_format($service->unit_price, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Rate</label>
                            <input type="number" name="services[0][unit_price]" 
                                   class="unit-price w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                   readonly>
                        </div>
                        
                        <div class="md:col-span-1.5 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Qty/Units</label>
                            <input type="number" name="services[0][quantity]" 
                                   class="quantity w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="md:col-span-1.5 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Days/Nights</label>
                            <input type="number" name="services[0][days]" 
                                   class="days w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Amount</label>
                            <input type="number" name="services[0][amount]" 
                                   class="amount w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                   readonly>
                        </div>
                        
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" 
                                    class="remove-service w-full py-1.5 px-2 border border-transparent text-xs font-semibold rounded-xl text-white bg-red-500 hover:bg-red-600 shadow-md shadow-red-500/10 transition">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" id="add-service" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/10 transition outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Service
            </button>
        </div>

        <!-- Products Section -->
        <div class="space-y-4 pt-6 border-t border-slate-100">
            <h3 class="text-md font-bold text-slate-700 uppercase tracking-wider">Products</h3>
            <div id="products-container" class="space-y-3">
                <!-- Product row template / first row -->
                <div class="product-item border border-slate-150 p-4 rounded-2xl bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <div class="md:col-span-4 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Product Item</label>
                            <select name="products[0][product_id]" 
                                    class="product-select w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none bg-white transition">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" data-stock="{{ $product->quantity }}">
                                        {{ $product->name }} (Available: {{ $product->quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Unit Price</label>
                            <input type="number" name="products[0][unit_price]" 
                                   class="unit-price w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                   readonly>
                        </div>
                        
                        <div class="md:col-span-1.5 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Quantity</label>
                            <input type="number" name="products[0][quantity]" 
                                   class="quantity w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="md:col-span-1.5 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Days</label>
                            <input type="number" name="products[0][days]" 
                                   class="days w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-semibold text-slate-500">Amount</label>
                            <input type="number" name="products[0][amount]" 
                                   class="amount w-full px-3 py-1.5 text-xs sm:text-sm border border-slate-200 rounded-xl bg-slate-100 outline-none" 
                                   readonly>
                        </div>
                        
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" 
                                    class="remove-product w-full py-1.5 px-2 border border-transparent text-xs font-semibold rounded-xl text-white bg-red-500 hover:bg-red-600 shadow-md shadow-red-500/10 transition">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 pt-6 border-t border-slate-100">
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-600">Subtotal</label>
                <input type="number" name="total_amount" 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 font-medium outline-none" 
                       readonly>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-600">Discount Type</label>
                <select id="discount-type" name="discount_type" 
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white">
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (RS)</option>
                </select>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-600">Discount Value</label>
                <input type="number" id="discount-value" name="discount" placeholder="0" 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-600">Final Amount</label>
                <input type="number" name="final_amount" 
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 font-bold text-blue-600 text-lg outline-none" 
                       readonly>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-600">Invoice Notes</label>
                <textarea name="note" rows="2" placeholder="Enter customer notes or warranty details..."
                          class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition"></textarea>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/20 transition outline-none">
                Save Invoice
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Default today's date
        let today = new Date().toISOString().split('T')[0];
        $("#invoice-date").val(today);

        // Default due date (7 days from today)
        let dueDate = new Date();
        dueDate.setDate(dueDate.getDate() + 7);
        let dueDateFormatted = dueDate.toISOString().split('T')[0];
        $("input[name='due_date']").val(dueDateFormatted);

        // Fetch unit price on service selection
        $(document).on("change", ".service-select", function() {
            let row = $(this).closest(".service-item");
            let serviceId = $(this).val();
            let unitPriceField = row.find(".unit-price");

            if (serviceId) {
                $.ajax({
                    url: "/service-price/" + serviceId,
                    type: "GET",
                    success: function(response) {
                        unitPriceField.val(response.unit_price);
                        updateRowAmount(row);
                    },
                    error: function() {
                        unitPriceField.val("0");
                        updateRowAmount(row);
                    }
                });
            } else {
                unitPriceField.val("0");
                updateRowAmount(row);
            }
        });

        // Fetch unit price on product selection
        $(document).on("change", ".product-select", function() {
            let row = $(this).closest(".product-item");
            let productId = $(this).val();
            let unitPriceField = row.find(".unit-price");

            if (productId) {
                $.ajax({
                    url: "/product-price/" + productId,
                    type: "GET",
                    success: function(response) {
                        unitPriceField.val(response.unit_price);
                        updateRowAmount(row);
                    },
                    error: function() {
                        unitPriceField.val("0");
                        updateRowAmount(row);
                    }
                });
            } else {
                unitPriceField.val("0");
                updateRowAmount(row);
            }
        });

        // Add new service row
        $("#add-service").click(function() {
            let newService = $(".service-item:first").clone();
            let index = $(".service-item").length;

            newService.find("input").val("");
            newService.find("select").val("");
            newService.find(".unit-price, .amount").val("0");
            newService.find(".quantity, .days").val("1");

            newService.find('[name^="services[0]"]').each(function() {
                let name = $(this).attr("name");
                name = name.replace("services[0]", `services[${index}]`);
                $(this).attr("name", name);
            });

            newService.hide().appendTo("#services-container").fadeIn(200);
        });

        // Add new product row
        $("#add-product").click(function() {
            let newProduct = $(".product-item:first").clone();
            let index = $(".product-item").length;

            newProduct.find("input").val("");
            newProduct.find("select").val("");
            newProduct.find(".unit-price, .amount").val("0");
            newProduct.find(".quantity, .days").val("1");

            newProduct.find('[name^="products[0]"]').each(function() {
                let name = $(this).attr("name");
                name = name.replace("products[0]", `products[${index}]`);
                $(this).attr("name", name);
            });

            newProduct.hide().appendTo("#products-container").fadeIn(200);
        });

        // Remove service row
        $(document).on("click", ".remove-service", function() {
            let row = $(this).closest(".service-item");
            if ($(".service-item").length > 1) {
                row.fadeOut(200, function() {
                    $(this).remove();
                    updateTotalAmount();
                });
            } else {
                // Just clear the row
                row.find("input").val("");
                row.find("select").val("");
                row.find(".unit-price, .amount").val("0");
                row.find(".quantity, .days").val("1");
                updateTotalAmount();
            }
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
                // Just clear the row
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
            updateRowAmount($(this).closest(".product-item, .service-item"));
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