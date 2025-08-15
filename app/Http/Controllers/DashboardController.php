<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        //Fetch totals for the dashboard
        $totalClients = Client::count();
        $totalCategories = Category::count();
        $totalInvoices = Invoice::count();
        $totalPaidInvoices = Invoice::where('status', 'paid')->count();
        $totalUnpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $totalIncome = Invoice::where('status', 'paid')->sum('final_amount');
        $totalProducts = Product::count();

        //Pie chart
        $paid = Invoice::where('status', 'paid')->count();
        $unpaid = Invoice::where('status', 'unpaid')->count();
        $partiallyPaid = Invoice::where('status', 'partially_paid')->count();
        $overdue = Invoice::where('status', 'overdue')->count();
        $processing = Invoice::where('status', 'processing')->count();

        //Calculate percentages
        $paidPercent = ($totalInvoices > 0) ? number_format(($paid / $totalInvoices) * 100, 2) : 0;
        $unpaidPercent = ($totalInvoices > 0) ? number_format(($unpaid / $totalInvoices) * 100, 2) : 0;
        $partiallyPaidPercent = ($totalInvoices > 0) ? number_format(($partiallyPaid / $totalInvoices) * 100, 2) : 0;
        $overduePercent = ($totalInvoices > 0) ? number_format(($overdue / $totalInvoices) * 100, 2) : 0;
        $processingPercent = ($totalInvoices > 0) ? number_format(($processing / $totalInvoices) * 100, 2) : 0;

        return view('dashboard.index', compact(
  'totalClients',
 'totalCategories',
            'totalInvoices',
            'totalPaidInvoices',
            'totalUnpaidInvoices',
            'totalIncome',
            'totalProducts',
            'paid', 
            'unpaid', 
            'partiallyPaid', 
            'overdue', 
            'processing',
            'paidPercent', 
            'unpaidPercent', 
            'partiallyPaidPercent', 
            'overduePercent', 
            'processingPercent'
        ));
    }

    public function getIncomeData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        //Fetch paid invoices within the date range
        $incomeData = Invoice::where('status', 'paid')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->orderBy('invoice_date')
            ->get(['invoice_date', 'final_amount']);

        //Calculate total income for the filtered date range
        $totalIncome = $incomeData->sum('final_amount');

        //Format data for the chart
        $labels = $incomeData->pluck('invoice_date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        });

        $data = $incomeData->pluck('final_amount');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'totalIncome' => $totalIncome,
        ]);
    }
}