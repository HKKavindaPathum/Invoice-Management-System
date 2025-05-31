<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        .text-gray {
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .note {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-print {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
        }
        .btn-print:hover {
            background: #0056b3;
        }

        /* Hide print button when printing */
        @media print {
            .btn-print {
                display: none;
            }
        }

        /* Client Details Layout */
        .client-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-item strong {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .detail-item p {
            margin: 0;
        }

        /* Make it responsive for smaller screens */
        @media (max-width: 768px) {
            .client-details {
                grid-template-columns: 1fr;
            }
        }

        /* Override specific styles for the invoice summary */
        .text-right p {
            margin: 5px 0;
        }

        .text-right .total {
            font-size: 20px;
            font-weight: bold;
        }

        .text-right div {
            display: inline-block;
            width: 90px;
            border-bottom: 4px double #000;
            margin-top: -10px;
        }

        .text-right .total {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div>
                <h1>Invoice</h1>
                <p class="text-gray">Invoice ID: #{{ $invoice->id }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray">Invoice Date: {{ $invoice->invoice_date }}</p>
                <p class="text-gray">Due Date: {{ $invoice->due_date }}</p>
            </div>
        </div>

        <!-- Client Details -->
        <div>
            <h2 class="text-gray">Client Information</h2>
            <div class="client-details">
                <div class="detail-item">
                    <strong>Full Name:</strong>
                    <p class="text-gray">{{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                </div>
                <div class="detail-item">
                    <strong>Address:</strong>
                    <p class="text-gray">{{ $invoice->client->address ?? 'No Address' }}</p>
                </div>
                <div class="detail-item">
                    <strong>Mobile No:</strong>
                    <p class="text-gray">{{ $invoice->client->mobile_no ?? 'No Mobile No'}}</p>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong>
                    <p class="text-gray">{{ $invoice->client->email ?? 'No Email'}}</p>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Days</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->productInvoices as $product)
                <tr>
                    <td>{{ $product->product->name }}</td>
                    <td>RS: {{ number_format($product->product->unit_price, 2) }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->days }}</td>
                    <td>RS: {{ number_format($product->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="text-right" style="margin-top: 50px;">
            <p class="total">Total Amount: RS: {{ number_format($invoice->total_amount, 2) }}</p>
            <p class="total">Discount: 
                @if($invoice->discount_type === 'percentage')
                    {{ number_format($invoice->discount) }}%
                @else
                    RS: {{ number_format($invoice->discount, 2) }}
                @endif
            </p>
            <p class="total">Final Amount: RS: {{ number_format($invoice->final_amount, 2) }}</p>
            <div style="display: inline-block; width: 90px; border-bottom: 4px double #000; margin-top: -5px"></div>
        </div>

        <!-- Notes -->
        <div class="note">
            <strong>Note:</strong>
            <p>{{ $invoice->note ?? 'No additional notes' }}</p>
        </div>

        <!-- Print Button -->
        <div class="text-right">
            <button onclick="window.print()" class="btn-print">Print Invoice</button>
        </div>
    </div>

</body>
</html>
