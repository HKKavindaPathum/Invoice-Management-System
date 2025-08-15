@extends('layouts.app')

@section('content')

<div class="container mx-auto bg-white shadow-lg rounded-lg p-4 md:p-6 mt-4 md:mt-6 max-w-6xl">
    <h2 class="text-lg md:text-xl font-semibold mb-4 md:mb-6 text-gray-800">Create Invoice</h2>

    <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Header Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice-date" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->title }}{{ $client->first_name }} {{ $client->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="partially_paid">Partially Paid</option>
                    <option value="overdue">Overdue</option>
                    <option value="processing">Processing</option>
                </select>
            </div>
        </div>

        <!-- Products Section -->
        <div class="space-y-4">
            <h3 class="text-md font-medium text-gray-700">Products/Services</h3>
            <div id="products-container" class="space-y-3">
                <div class="product-item border border-gray-200 p-3 rounded-lg bg-gray-50">
                    <div class="grid grid-cols-1  md:grid-cols-12 gap-3">

                        <div class="sm:col-span-3 md:col-span-4 space-y-1">
                            <label class="block text-xs font-medium text-gray-600">Product</label>
                            <select name="products[0][product_id]" 
                                    class="product-select w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-gray-600">Unit Price</label>
                            <input type="number" name="products[0][unit_price]" 
                                   class="unit-price w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   readonly>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-gray-600">Qty</label>
                            <input type="number" name="products[0][quantity]" 
                                   class="quantity w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-gray-600">Days</label>
                            <input type="number" name="products[0][days]" 
                                   class="days w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   value="1" min="1">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-gray-600">Amount</label>
                            <input type="number" name="products[0][amount]" 
                                   class="amount w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded bg-gray-100" 
                                   readonly>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" 
                                    class="remove-product w-full py-1 px-2 border border-transparent text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" id="add-product" 
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Product
            </button>
        </div>

        <!-- Totals Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                <input type="number" name="total_amount" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100" 
                       readonly>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                <select id="discount-type" name="discount_type" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                <input type="number" id="discount-value" name="discount" placeholder="0" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                <input type="number" name="final_amount" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 font-semibold" 
                       readonly>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="note" rows="2" 
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
        //Set today's date as default for the invoice date field
        let today = new Date().toISOString().split('T')[0];
        $("#invoice-date").val(today);

        //Calculate due date (7 days from today by default)
        let dueDate = new Date();
        dueDate.setDate(dueDate.getDate() + 7);
        let dueDateFormatted = dueDate.toISOString().split('T')[0];
        $("input[name='due_date']").val(dueDateFormatted);

        //Fetch unit price on product selection
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
                        updateAmount(row);
                    },
                    error: function() {
                        unitPriceField.val("0");
                        updateAmount(row);
                    }
                });
            } else {
                unitPriceField.val("0");
                updateAmount(row);
            }
        });

        //Add new product row
        $("#add-product").click(function() {
            let newProduct = $(".product-item:first").clone();
            let index = $(".product-item").length;

            //Clear input values
            newProduct.find("input").val("");
            newProduct.find("select").val("");
            newProduct.find(".unit-price, .amount").val("0");
            newProduct.find(".quantity, .days").val("1");

            //Update the name attributes with the correct index
            newProduct.find('[name^="products[0]"]').each(function() {
                let name = $(this).attr("name");
                name = name.replace("products[0]", `products[${index}]`);
                $(this).attr("name", name);
            });

            //Add to container with animation
            newProduct.hide().appendTo("#products-container").fadeIn(200);
            
            //Scroll to the new product
            $('html, body').animate({
                scrollTop: newProduct.offset().top - 100
            }, 300);
        });

        //Remove product row with confirmation if there's data
        $(document).on("click", ".remove-product", function() {
            let row = $(this).closest(".product-item");
            let hasData = false;
            
            //Check if any fields have values
            row.find('input, select').each(function() {
                if ($(this).val() && $(this).val() !== "0" && $(this).val() !== "1") {
                    hasData = true;
                    return false; //break out of the loop
                }
            });
            
            if (hasData) {
                if (!confirm('Are you sure you want to remove this product? Any entered data will be lost.')) {
                    return false;
                }
            }
            
            if ($(".product-item").length > 1) {
                row.fadeOut(200, function() {
                    $(this).remove();
                    updateTotalAmount();
                });
            }
        });

        //Update amount dynamically when quantity or days change
        $(document).on("input", ".quantity, .days", function() {
            let val = parseFloat($(this).val());
            if (isNaN(val) || val < 1) {
                $(this).val("1");
            }
            updateAmount($(this).closest(".product-item"));
        });

        //Calculate amount for each product row
        function updateAmount(row) {
            let unitPrice = parseFloat(row.find(".unit-price").val()) || 0;
            let quantity = parseFloat(row.find(".quantity").val()) || 1;
            let days = parseFloat(row.find(".days").val()) || 1;
            let amount = unitPrice * quantity * days;

            row.find(".amount").val(amount.toFixed(2));
            updateTotalAmount();
        }

        //Calculate total amount of all products
        function updateTotalAmount() {
            let totalAmount = 0;
            $(".amount").each(function() {
                totalAmount += parseFloat($(this).val()) || 0;
            });

            $("input[name='total_amount']").val(totalAmount.toFixed(2));
            calculateFinalAmount(totalAmount);
        }

        //Calculate final amount after applying discount
        function calculateFinalAmount(totalAmount) {
            let discountType = $("#discount-type").val();
            let discountValue = parseFloat($("#discount-value").val()) || 0;
            let finalAmount = totalAmount;

            if (discountType === "percentage") {
                discountValue = Math.min(100, Math.max(0, discountValue));
                $("#discount-value").val(discountValue);
                finalAmount -= (totalAmount * discountValue / 100);
            } 
            
            else if (discountType === "fixed") {
                discountValue = Math.min(totalAmount, Math.max(0, discountValue));
                $("#discount-value").val(discountValue);
                finalAmount -= discountValue;
            }

            finalAmount = Math.max(0, finalAmount).toFixed(2);
            $("input[name='final_amount']").val(finalAmount);
        }

        //Trigger discount calculation when discount type or value changes
        $("#discount-type, #discount-value").on("change input", function() {
            let totalAmount = parseFloat($("input[name='total_amount']").val()) || 0;
            calculateFinalAmount(totalAmount);
        });
    });
</script>
@endpush