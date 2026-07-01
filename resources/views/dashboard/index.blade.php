@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 space-y-6">
    
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Clients Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('clients.index') }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Clients</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalClients }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('categories.index') }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Categories</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalCategories }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-slate-50 text-slate-500 group-hover:bg-slate-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('products.index') }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Products</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalProducts }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600 group-hover:bg-amber-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>

        <!-- Total Invoices Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('invoices.index') }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Invoices</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalInvoices }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <!-- Paid Invoices Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('invoices.index', ['status' => 'paid']) }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Paid Invoices</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalPaidInvoices }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-green-50 text-green-600 group-hover:bg-green-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Unpaid Invoices Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group">
            <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unpaid Invoices</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ $totalUnpaidInvoices }}</p>
            </a>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-orange-50 text-orange-600 group-hover:bg-orange-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <!-- Total Income Card -->
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex justify-between items-center group sm:col-span-2 lg:col-span-3">
            <div class="flex-1">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Income</h3>
                <p class="text-3xl font-extrabold text-slate-950 mt-2 tracking-tight">RS: {{ number_format($totalIncome, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-green-50 text-green-600 group-hover:bg-green-100 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Invoice Status Pie Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.01)] lg:col-span-1 flex flex-col">
            <h3 class="text-sm font-bold text-slate-800 mb-4 tracking-tight uppercase tracking-wider">Invoice Status</h3>
            <div class="relative flex-1 flex items-center justify-center min-h-[300px]">
                <canvas id="invoiceStatusChart"></canvas>
            </div>
        </div>

        <!-- Income Overview Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.01)] lg:col-span-2 relative flex flex-col">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex justify-between items-center tracking-tight uppercase tracking-wider">
                Income Overview
                <button id="filter_icon" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-50 rounded-xl transition duration-200 outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </h3>

            <!-- Filter Popup -->
            <div id="filterPopup" class="absolute right-6 top-16 bg-white border border-slate-100 p-5 shadow-2xl rounded-2xl hidden w-72 z-10 transition-all duration-300">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Filter Type</label>
                <select id="filterType" class="w-full px-4 py-2 border border-slate-200 rounded-xl mt-1 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none text-sm transition-all">
                    <option value="single">Single Date</option>
                    <option value="range">Date Range</option>
                </select>

                <!-- Single Date Field -->
                <div id="singleDateField" class="mt-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Select Date</label>
                    <input type="date" id="single_date" class="w-full px-4 py-2 border border-slate-200 rounded-xl mt-1 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none text-sm transition-all">
                </div>

                <!-- Date Range Fields -->
                <div id="rangeDateFields" class="mt-3 hidden">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Start Date</label>
                    <input type="date" id="start_date" class="w-full px-4 py-2 border border-slate-200 rounded-xl mt-1 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none text-sm transition-all">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">End Date</label>
                    <input type="date" id="end_date" class="w-full px-4 py-2 border border-slate-200 rounded-xl mt-1 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none text-sm transition-all">
                </div>

                <!-- Buttons -->
                <div class="mt-4 space-y-2">
                    <button id="filter_income" class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/10 transition-all outline-none">Filter</button>
                    <button id="clear_filter" class="w-full bg-slate-100 text-slate-700 font-semibold py-2.5 rounded-xl hover:bg-slate-200 transition-all outline-none">Clear</button>
                </div>
            </div>

            <!-- Chart -->
            <div class="w-full flex-1 min-h-[300px]">
                <canvas id="incomeChart"></canvas>
            </div>

            <div class="bg-slate-50 border border-slate-100/50 p-4 mt-4 rounded-xl flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Filtered Total</span>
                <span class="text-lg font-extrabold text-slate-900" id="totalIncome">RS: {{ number_format($totalIncome, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('incomeChart').getContext('2d');
        let incomeChart;
        const filterPopup = document.getElementById('filterPopup');

        //Toggle Filter Popup
        document.getElementById('filter_icon').addEventListener('click', function (e) {
            e.stopPropagation();//Prevent event bubbling
            filterPopup.classList.toggle('hidden');
        });

        //Close Filter Popup when clicking outside
        document.addEventListener('click', function (e) {
            if (!filterPopup.contains(e.target) && !document.getElementById('filter_icon').contains(e.target)) {
                filterPopup.classList.add('hidden');
            }
        });

        //Switch between Single Date and Date Range fields
        document.getElementById('filterType').addEventListener('change', function () {
            if (this.value === 'single') {
                document.getElementById('singleDateField').classList.remove('hidden');
                document.getElementById('rangeDateFields').classList.add('hidden');
            } else {
                document.getElementById('singleDateField').classList.add('hidden');
                document.getElementById('rangeDateFields').classList.remove('hidden');
            }
        });

        //Fetch and Render Income Data
        function fetchIncomeData(startDate, endDate) {
            fetch(`/dashboard/income-data?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (incomeChart) incomeChart.destroy();

                    incomeChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Income',
                                data: data.data,
                                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                                borderColor: '#2563EB',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointBackgroundColor: '#2563EB',
                                pointHoverRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: { 
                                y: { 
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0, 0, 0, 0.02)' },
                                    ticks: { font: { family: 'Outfit', size: 11 }, color: '#64748b' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { family: 'Outfit', size: 11 }, color: '#64748b' }
                                }
                            }
                        }
                    });
                    
                    //Update Total Income
                    document.getElementById('totalIncome').textContent = `RS: ${data.totalIncome.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                });
        }

        //Filter Button Click Event
        document.getElementById('filter_income').addEventListener('click', function () {
            const filterType = document.getElementById('filterType').value;
            let startDate, endDate;

            if (filterType === 'single') {
                startDate = endDate = document.getElementById('single_date').value;
            } else {
                startDate = document.getElementById('start_date').value;
                endDate = document.getElementById('end_date').value;
            }

            if (!startDate || !endDate) {
                alert('Please select a valid date or date range.');
                return;
            }

            fetchIncomeData(startDate, endDate);
            filterPopup.classList.add('hidden');
        });

        //Clear Button Click Event
        document.getElementById('clear_filter').addEventListener('click', function () {
            //Clear the date fields
            document.getElementById('single_date').value = '';
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';

            //Fetch default data (e.g., last 365 days)
            const endDate = new Date().toISOString().split('T')[0];
            const startDate = new Date(new Date().setDate(new Date().getDate() - 365)).toISOString().split('T')[0];
            fetchIncomeData(startDate, endDate);
            
            filterPopup.classList.add('hidden'); //Close the filter popup
        });

        //Initial Load with Default Data (Last 365 Days)
        const endDate = new Date().toISOString().split('T')[0];
        const startDate = new Date(new Date().setDate(new Date().getDate() - 365)).toISOString().split('T')[0];
        fetchIncomeData(startDate, endDate);
    });
    
    //Pie Chart
    var ctxPie = document.getElementById('invoiceStatusChart').getContext('2d');
    
    //Invoice data
    var invoiceCounts = [{{ $paid }}, {{ $unpaid }}, {{ $partiallyPaid }}, {{ $overdue }}, {{ $processing }}];
    var invoicePercentages = [{{ $paidPercent }}, {{ $unpaidPercent }}, {{ $partiallyPaidPercent }}, {{ $overduePercent }}, {{ $processingPercent }}];
    var invoiceLabels = ['Paid', 'Unpaid', 'Partially Paid', 'Overdue', 'Processing'];
    var backgroundColors = ['#10B981', '#F59E0B', '#F59E0B', '#EF4444', '#64748B'];
    
    //Create the pie chart
    var invoiceStatusChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: invoiceLabels,
            datasets: [{
                data: invoicePercentages,
                backgroundColor: backgroundColors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { font: { family: 'Outfit', size: 12 }, padding: 15, boxWidth: 12, usePointStyle: true }
                },
                datalabels: {
                    color: '#ffffff',
                    font: { weight: 'bold', family: 'Outfit', size: 10 },
                    backgroundColor: 'rgba(15, 23, 42, 0.85)',
                    borderRadius: 6, //Rounded corners for the background box
                    padding: { top: 4, bottom: 4, left: 6, right: 6 }, //Padding around the text
                    formatter: (value, context) => {
                        var index = context.dataIndex;
                        var count = invoiceCounts[index];
                        return value > 0 ? `${value}% (${count})` : ''; //Hide label if value is 0%
                    }
                }
            },
            cutout: '75%'
        },
        plugins: [ChartDataLabels]
    });
</script>
@endpush

