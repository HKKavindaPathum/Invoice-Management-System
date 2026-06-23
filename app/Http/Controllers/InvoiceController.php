<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Models\Service;
use App\Models\ServiceInvoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller {
    public function index(Request $request)
    {
        $status = $request->query('status');
        $clientId = $request->query('client_id');
    
        $invoices = Invoice::with('client')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($clientId, function ($query, $clientId) {
                return $query->where('client_id', $clientId);
            })
            ->get();
    
        return view('invoices.index', compact('invoices'));
    }

    public function create() {
        $clients = Client::all();
        $products = Product::all();
        $services = Service::all();
        return view('invoices.create', compact('clients', 'products', 'services'));
    }

    public function getProductPrice($product_id) {
        $product = Product::findOrFail($product_id);
        return response()->json(['unit_price' => $product->unit_price]);
    }

    public function getServicePrice($service_id) {
        $service = Service::findOrFail($service_id);
        return response()->json(['unit_price' => $service->unit_price]);
    }
    public function store(Request $request) {
        // Filter out empty rows from the request inputs first
        $products = array_values(array_filter($request->input('products', []), function($item) {
            return !empty($item['product_id']);
        }));
        $services = array_values(array_filter($request->input('services', []), function($item) {
            return !empty($item['service_id']);
        }));

        $request->merge([
            'products' => $products,
            'services' => $services,
        ]);

        // Validate the request
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:unpaid,paid,partially_paid,overdue,processing',
            'total_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'discount_type' => 'required|in:percentage,fixed',
            'discount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.days' => 'required|numeric|min:1',
            'products.*.amount' => 'required|numeric',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.days' => 'required|numeric|min:1',
            'services.*.amount' => 'required|numeric',
        ]);

        if (empty($products) && empty($services)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['items' => 'You must add at least one product or service to the invoice.']);
        }

        // Check stock availability
        if (!empty($products)) {
            foreach ($products as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->quantity < $item['quantity']) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['products' => "The requested quantity for '{$product->name}' exceeds the available stock ({$product->quantity})."]);
                }
            }
        }
 
        // Create the invoice
        $invoice = Invoice::create([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            'final_amount' => $request->final_amount,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount ?? 0, // Default to 0 if null
            'note' => $request->note,
        ]);
 
        // Save product invoices and update stock
        if (!empty($products)) {
            foreach ($products as $productData) {
                ProductInvoice::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'days' => $productData['days'],
                    'amount' => $productData['amount'],
                ]);

                // Deduct stock
                $product = Product::find($productData['product_id']);
                if ($product) {
                    $product->decrement('quantity', $productData['quantity']);
                }
            }
        }

        // Save service invoices
        if (!empty($services)) {
            foreach ($services as $serviceData) {
                ServiceInvoice::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $serviceData['service_id'],
                    'quantity' => $serviceData['quantity'],
                    'days' => $serviceData['days'],
                    'amount' => $serviceData['amount'],
                ]);
            }
        }
    
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }
    

    public function edit(Invoice $invoice) {
        //Fetch the invoice with its associated client, products and services
        $invoice->load(['client', 'productInvoices.product', 'serviceInvoices.service']);
        $clients = Client::all();
        $products = Product::all();
        $services = Service::all();
    
        return view('invoices.edit', compact('invoice', 'clients', 'products', 'services'));
    }
    
    public function update(Request $request, Invoice $invoice) {
        // Filter out empty rows from the request inputs first
        $products = array_values(array_filter($request->input('products', []), function($item) {
            return !empty($item['product_id']);
        }));
        $services = array_values(array_filter($request->input('services', []), function($item) {
            return !empty($item['service_id']);
        }));

        $request->merge([
            'products' => $products,
            'services' => $services,
        ]);

        // Validate the request
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:unpaid,paid,partially_paid,overdue,processing',
            'total_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'discount_type' => 'required|in:percentage,fixed',
            'discount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.days' => 'required|numeric|min:1',
            'products.*.amount' => 'required|numeric',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.days' => 'required|numeric|min:1',
            'services.*.amount' => 'required|numeric',
        ]);

        if (empty($products) && empty($services)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['items' => 'You must add at least one product or service to the invoice.']);
        }

        // Temporarily restore the stock of all current products in this invoice for validation
        foreach ($invoice->productInvoices as $oldProductInvoice) {
            $product = Product::find($oldProductInvoice->product_id);
            if ($product) {
                $product->increment('quantity', $oldProductInvoice->quantity);
            }
        }

        // Check if the new requested stock quantities are available
        if (!empty($products)) {
            foreach ($products as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->quantity < $item['quantity']) {
                    // Rollback the temporary stock restoration before redirecting back
                    foreach ($invoice->productInvoices as $oldProductInvoice) {
                        $productToRevert = Product::find($oldProductInvoice->product_id);
                        if ($productToRevert) {
                            $productToRevert->decrement('quantity', $oldProductInvoice->quantity);
                        }
                    }

                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['products' => "The requested quantity for '{$product->name}' exceeds the available stock ({$product->quantity})."]);
                }
            }
        }
     
        // Update the invoice
        $invoice->update([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            'final_amount' => $request->final_amount,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount ?? 0,
            'note' => $request->note,
        ]);
     
        // Delete existing product invoices (since stock was already refunded, we don't need to do it again)
        $invoice->productInvoices()->delete();
        $invoice->serviceInvoices()->delete();
     
        // Save updated product invoices and deduct new stock quantities
        if (!empty($products)) {
            foreach ($products as $productData) {
                ProductInvoice::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'days' => $productData['days'],
                    'amount' => $productData['amount'],
                ]);

                $product = Product::find($productData['product_id']);
                if ($product) {
                    $product->decrement('quantity', $productData['quantity']);
                }
            }
        }

        // Save updated service invoices
        if (!empty($services)) {
            foreach ($services as $serviceData) {
                ServiceInvoice::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $serviceData['service_id'],
                    'quantity' => $serviceData['quantity'],
                    'days' => $serviceData['days'],
                    'amount' => $serviceData['amount'],
                ]);
            }
        }
    
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['client', 'productInvoices.product', 'serviceInvoices.service'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }


    public function destroy($id) {
        $invoice = Invoice::findOrFail($id);

        // Restore stock
        foreach ($invoice->productInvoices as $productInvoice) {
            $product = Product::find($productInvoice->product_id);
            if ($product) {
                $product->increment('quantity', $productInvoice->quantity);
            }
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }


    public function search(Request $request) {
        $query = $request->input('search');
    
        $invoices = Invoice::whereHas('client', function ($q) use ($query) {
            $q->where('first_name', 'like', "%$query%")
              ->orWhere('last_name', 'like', "%$query%");
        })->get();
    
        return view('invoices.index', compact('invoices'));
    }

    public function filterInvoices(Request $request)
    {
        $query = Invoice::query();
    
        //Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('invoice_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('invoice_date', '<=', $request->end_date);
        }
    
        //Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
    
        //Fetch the filtered invoices
        $invoices = $query->with('client')->get();
    
        //Return the view with filtered invoices
        return view('invoices.index', compact('invoices'));
    }

    public function print($id)
    {
        //Fetch the invoice
        $invoice = Invoice::with(['client', 'productInvoices.product', 'serviceInvoices.service'])->findOrFail($id);
        $settings = Setting::first();

        //Return a printable view
        return view('invoices.print', compact('invoice', 'settings'));
    }

    public function download($id)
    {
        //Fetch the invoice
        $invoice = Invoice::with(['client', 'productInvoices.product', 'serviceInvoices.service'])->findOrFail($id);
        $settings = Setting::first();

        //Generate PDF
        $pdf = Pdf::loadView('invoices.print', compact('invoice', 'settings'));

        //Download the PDF
        return $pdf->download("invoice-{$invoice->id}.pdf");
    }

}
