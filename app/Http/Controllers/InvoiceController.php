<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductInvoice;
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
        return view('invoices.create', compact('clients', 'products'));
    }

    public function getProductPrice($product_id) {
        $product = Product::findOrFail($product_id);
        return response()->json(['unit_price' => $product->unit_price]);
    }
    public function store(Request $request) {

        //Validate the request
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:unpaid,paid,partially_paid,overdue,processing',
            'total_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'discount_type' => 'required|in:percentage,fixed',
            'discount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric',
            'products.*.days' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
        ]);
    
        //Create the invoice
        $invoice = Invoice::create([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            'final_amount' => $request->final_amount,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount ?? 0, //Default to 0 if null
            'note' => $request->note,
        ]);
    
    
        //Save product invoices
        foreach ($request->products as $product) {
            ProductInvoice::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'days' => $product['days'],
                'amount' => $product['amount'],
            ]);
        }
    
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }
    

    public function edit(Invoice $invoice) {
        //Fetch the invoice with its associated client and products
        $invoice->load('client', 'productInvoices.product');
        $clients = Client::all();
        $products = Product::all();
    
        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }
    
    public function update(Request $request, Invoice $invoice) {
        //Validate the request
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
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric',
            'products.*.days' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
        ]);
    
        //Update the invoice
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
    
        //Delete existing product invoices
        $invoice->productInvoices()->delete();
    
        //Save updated product invoices
        foreach ($request->products as $product) {
            ProductInvoice::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'days' => $product['days'],
                'amount' => $product['amount'],
            ]);
        }
    
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['client', 'productInvoices.product'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }


    public function destroy($id) {
        Invoice::findOrFail($id)->delete();
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
        $invoice = Invoice::with(['client', 'productInvoices.product'])->findOrFail($id);

        //Return a printable view
        return view('invoices.print', compact('invoice'));
    }

    public function download($id)
    {
        //Fetch the invoice
        $invoice = Invoice::with(['client', 'productInvoices.product'])->findOrFail($id);

        //Generate PDF
        $pdf = Pdf::loadView('invoices.print', compact('invoice'));

        //Download the PDF
        return $pdf->download("invoice-{$invoice->id}.pdf");
    }

}
