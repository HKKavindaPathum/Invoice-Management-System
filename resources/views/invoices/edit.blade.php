@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mx-auto bg-white shadow-lg rounded-lg p-6 mt-6">
    <h2 class="text-xl font-semibold mb-4">Edit Invoice</h2>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client</label>
            <select name="client_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->title }} {{ $client->first_name }} {{ $client->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold">Invoice Date</label>
                <input type="date" name="invoice_date" value="{{ $invoice->invoice_date }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold">Due Date</label>
                <input type="date" name="due_date" value="{{ $invoice->due_date }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full px-4 py-2 border rounded-lg">
                <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="partially_paid" {{ $invoice->status == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="processing" {{ $invoice->status == 'processing' ? 'selected' : '' }}>Processing</option>
            </select>
        </div>

        <!-- Products Section -->
        <div id="products-container" class="space-y-4">
            @foreach($invoice->productInvoices as $index => $productInvoice)
                <div class="product-item border p-4 rounded-lg">
                    <div class="grid grid-cols-6 gap-2">
                        <div>
                            <label class="block text-gray-700 font-semibold">Product</label>
                            <select name="products[{{ $index }}][product_id]" class="product-select w-full px-2 py-1 border rounded-lg">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" {{ $productInvoice->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold">Unit Price</label>
                            <input type="number" name="products[{{ $index }}][unit_price]" value="{{ $productInvoice->product->unit_price }}" class="unit-price w-full px-2 py-1 border rounded-lg" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Quantity</label>
                            <input type="number" name="products[{{ $index }}][quantity]" value="{{ $productInvoice->quantity }}" class="quantity w-full px-2 py-1 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">No of Days</label>
                            <input type="number" name="products[{{ $index }}][days]" value="{{ $productInvoice->days }}" class="days w-full px-2 py-1 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Amount</label>
                            <input type="number" name="products[{{ $index }}][amount]" value="{{ $productInvoice->amount }}" class="amount w-full px-2 py-1 border rounded-lg" readonly>
                        </div>
                    </div>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg mt-2 remove-product">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-product" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg mt-4">
            Add Product
        </button>

        <div class="mt-4">
            <label class="block text-gray-700 font-semibold">Total Amount</label>
            <input type="number" name="total_amount" value="{{ $invoice->total_amount }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div class="discount-section">
            <label for="discount-type" class="block text-gray-700 font-semibold">Discount Type:</label>
            <select id="discount-type" name="discount_type" class="w-full px-4 py-2 border rounded-lg">
                <option value="percentage" {{ $invoice->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                <option value="fixed" {{ $invoice->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
            </select>
        
            <label for="discount-value" class="block text-gray-700 font-semibold">Discount Value:</label>
            <input type="number" id="discount-value" name="discount" value="{{ $invoice->discount }}" placeholder="Enter Discount" class="w-full px-4 py-2 border rounded-lg" />
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 font-semibold">Final Amount</label>
            <input type="number" name="final_amount" value="{{ $invoice->final_amount }}" class="w-full px-4 py-2 border rounded-lg" readonly>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 font-semibold">Note</label>
            <textarea name="note" class="w-full px-4 py-2 border rounded-lg">{{ $invoice->note }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg mt-4">
            Update Invoice
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
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
                        updateAmount(row);
                    }
                });
            } else {
                unitPriceField.val("0");
                updateAmount(row);
            }
        });

        // Add new product row
        $("#add-product").click(function() {
            let newProduct = $(".product-item:first").clone();
            let index = $(".product-item").length;

            // Clear input values
            newProduct.find("input").val("");
            newProduct.find("select").prop("selectedIndex", 0);
            newProduct.find(".unit-price, .amount").val("0");
            newProduct.find(".quantity, .days").val("1");

            // Update the name attributes with the correct index
            newProduct.find('[name^="products[0]"]').each(function() {
                let name = $(this).attr("name");
                name = name.replace("products[0]", `products[${index}]`);
                $(this).attr("name", name);
            });

            // Append the new product row to the container
            $("#products-container").append(newProduct);
        });

        // Remove product row
        $(document).on("click", ".remove-product", function() {
            if ($(".product-item").length > 1) {
                $(this).closest(".product-item").remove();
                updateTotalAmount();
            }
        });

        // Update amount dynamically when quantity or days change
        $(document).on("input", ".quantity, .days", function() {
            updateAmount($(this).closest(".product-item"));
        });

        // Calculate amount for each product row
        function updateAmount(row) {
            let unitPrice = parseFloat(row.find(".unit-price").val()) || 0;
            let quantity = parseFloat(row.find(".quantity").val()) || 1;
            let days = parseFloat(row.find(".days").val()) || 1;
            let amount = unitPrice * quantity * days;

            // console.log("Unit Price:", unitPrice); // Debugging
            // console.log("Quantity:", quantity); // Debugging
            // console.log("Days:", days); // Debugging
            // console.log("Amount:", amount); // Debugging

            row.find(".amount").val(amount.toFixed(2));
            updateTotalAmount();
        }

        // Calculate total amount of all products
        function updateTotalAmount() {
            let totalAmount = 0;
            $(".amount").each(function() {
                totalAmount += parseFloat($(this).val()) || 0;
            });

            // console.log("Total Amount:", totalAmount); // Debugging
            $("input[name='total_amount']").val(totalAmount.toFixed(2));
            calculateFinalAmount(totalAmount);
        }

        // Calculate final amount after applying discount
        function calculateFinalAmount(totalAmount) {
            let discountType = $("#discount-type").val();
            let discountValue = parseFloat($("#discount-value").val()) || 0;
            let finalAmount = totalAmount;

            if (discountType === "percentage") {
                finalAmount -= (totalAmount * discountValue / 100);
            } else if (discountType === "fixed") {
                finalAmount -= discountValue;
            }

            finalAmount = finalAmount < 0 ? 0 : finalAmount;
            // console.log("Final Amount:", finalAmount); // Debugging
            $("input[name='final_amount']").val(finalAmount.toFixed(2));
        }

        // Trigger discount calculation when discount type or value changes
        $("#discount-type, #discount-value").on("change input", function() {
            let totalAmount = parseFloat($("input[name='total_amount']").val()) || 0;
            calculateFinalAmount(totalAmount);
        });
    });
</script>
@endpush