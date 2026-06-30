<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .text-gray {
            color: #64748b;
            font-size: 14px;
            margin: 5px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-summary {
            margin-top: 30px;
            text-align: right;
            border-top: 2px solid #f1f5f9;
            padding-top: 15px;
        }
        .total-summary p {
            margin: 5px 0;
            font-size: 14px;
            color: #475569;
        }
        .total-summary .final {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        .note {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 25px;
            border: 1px solid #f1f5f9;
        }
        .note strong {
            font-size: 13px;
            color: #1e293b;
        }
        .note p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #64748b;
        }
        .btn-print {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
        .client-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        .detail-item {
            font-size: 13px;
        }
        .detail-item strong {
            display: block;
            color: #334155;
            margin-bottom: 4px;
        }
        .detail-item p {
            margin: 0;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div>
                @if(isset($settings) && $settings->app_logo)
                    @php
                        $logoPath = public_path($settings->app_logo);
                        $logoData = '';
                        if (file_exists($logoPath)) {
                            $logoData = base64_encode(@file_get_contents($logoPath));
                        }
                    @endphp
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" style="height: 60px; margin-bottom: 10px; display: block;">
                    @else
                        <img src="{{ asset($settings->app_logo) }}" alt="Logo" style="height: 60px; margin-bottom: 10px; display: block;">
                    @endif
                @endif
                @if(isset($settings) && $settings->company_name)
                    <h2 style="margin: 0; font-size: 20px; color: #1e293b;">{{ $settings->company_name }}</h2>
                    <p style="margin: 2px 0; font-size: 12px; color: #64748b;">{{ $settings->company_address }}</p>
                    <p style="margin: 2px 0; font-size: 12px; color: #64748b;">Phone: {{ $settings->company_phone }}@if($settings->fax_number) | Fax: {{ $settings->fax_number }}@endif</p>
                @else
                    <h2 style="margin: 0; font-size: 20px; color: #1e293b;">CellHub Store</h2>
                @endif
            </div>
            <div class="text-right">
                <h1 style="margin: 0 0 5px 0;">Invoice</h1>
                <p class="text-gray"><strong>Invoice ID:</strong> #{{ $invoice->id }}</p>
                <p class="text-gray"><strong>Date:</strong> {{ $invoice->invoice_date }}</p>
                <p class="text-gray"><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
            </div>
        </div>

        <!-- Client Details -->
        <div>
            <div class="section-title">Client Information</div>
            <div class="client-details">
                <div class="detail-item">
                    <strong>Full Name:</strong>
                    <p>{{ $invoice->client->title }} {{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                </div>
                <div class="detail-item">
                    <strong>Address:</strong>
                    <p>{{ $invoice->client->address ?? 'N/A' }}</p>
                </div>
                <div class="detail-item">
                    <strong>Mobile No:</strong>
                    <p>{{ $invoice->client->mobile_no ?? 'N/A' }}</p>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong>
                    <p>{{ $invoice->client->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        @if($invoice->productInvoices->count() > 0)
        <div>
            <div class="section-title">Products</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Days</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->productInvoices as $product)
                    <tr>
                        <td>{{ $product->product->name }}</td>
                        <td>RS: {{ number_format($product->product->unit_price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->days }}</td>
                        <td class="text-right">RS: {{ number_format($product->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Summary -->
        <div class="total-summary">
            <p>Subtotal: RS: {{ number_format($invoice->total_amount, 2) }}</p>
            <p>Discount: 
                @if($invoice->discount_type === 'percentage')
                    {{ number_format($invoice->discount) }}%
                @else
                    RS: {{ number_format($invoice->discount, 2) }}
                @endif
            </p>
            <p class="final">Final Total: RS: {{ number_format($invoice->final_amount, 2) }}</p>
        </div>

        <!-- Notes -->
        @if($invoice->note)
        <div class="note">
            <strong>Invoice Notes:</strong>
            <p>{{ $invoice->note }}</p>
        </div>
        @endif

        <!-- Print Button -->
        <div class="text-right">
            <button onclick="window.print()" class="btn-print">Print Invoice</button>
        </div>
    </div>

</body>
</html>
