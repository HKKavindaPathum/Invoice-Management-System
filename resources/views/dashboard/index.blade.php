@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-gray-50 min-h-screen">
    
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('clients.index') }}">
                <h3 class="text-lg font-semibold">Clients</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalClients }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white  p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('categories.index') }}">
                <h3 class="text-lg font-semibold">Categories</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalCategories }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 text-white  p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('products.index') }}">
                <h3 class="text-lg font-semibold">Products</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white  p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('invoices.index') }}">
                <h3 class="text-lg font-semibold">Total Invoices</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalInvoices }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white  p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('invoices.index', ['status' => 'paid']) }}">
                <h3 class="text-lg font-semibold">Paid Invoices</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalPaidInvoices }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-rose-500 to-rose-600 text-white  p-4 sm:p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
            <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}">
                <h3 class="text-lg font-semibold">Unpaid Invoices</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalUnpaidInvoices }}</p>
            </a>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white  p-4 sm:p-6 rounded-xl shadow-lg sm:col-span-2 lg:col-span-1 transform hover:scale-105 transition duration-300">
            <h3 class="text-lg font-semibold">Total Income</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format($totalIncome, 2) }}</p>
        </div>
    </div>

    <!-- Invoice Status Pie Chart -->
    <div class="mt-6">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Invoice Status</h3>
            <div class="relative w-full" style="height: 300px;">
                <canvas id="invoiceStatusChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Income Overview Chart -->
    <div class="bg-white p-6 mt-6 rounded-xl shadow-lg relative">
        <h3 class="text-xl font-semibold mb-4 flex justify-between items-center text-gray-700">
            Income Overview
            <button id="filter_icon" class="p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        </h3>

        <!-- Filter Popup -->
        <div id="filterPopup" class="absolute right-0 top-12 bg-white p-4 shadow-lg rounded-lg hidden w-64 z-10">
            <label class="block text-sm font-medium text-gray-700">Filter Type:</label>
            <select id="filterType" class="w-full px-4 py-2 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-500">
                <option value="single">Single Date</option>
                <option value="range">Date Range</option>
            </select>

            <!-- Single Date Field -->
            <div id="singleDateField" class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Select Date:</label>
                <input type="date" id="single_date" class="w-full px-4 py-2 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date Range Fields -->
            <div id="rangeDateFields" class="mt-2 hidden">
                <label class="block text-sm font-medium text-gray-700">Start Date:</label>
                <input type="date" id="start_date" class="w-full px-4 py-2 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-500">
                <label class="block text-sm font-medium text-gray-700 mt-2">End Date:</label>
                <input type="date" id="end_date" class="w-full px-4 py-2 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <button id="filter_income" class="w-full bg-blue-600 text-white py-2 mt-3 rounded-lg hover:bg-blue-700">Filter</button>
            <button id="clear_filter" class="w-full bg-gray-600 text-white py-2 mt-2 rounded-lg hover:bg-gray-700">Clear</button>
        </div>

        <!-- Chart -->
        <div class="w-full overflow-x-auto">
            <div class="min-w-[300px]">
                <canvas id="incomeChart" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 mt-4 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Income</h3>
            <p class="text-2xl font-bold text-blue-600" id="totalIncome">RS:{{ number_format($totalIncome, 2) }}</p>
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
                                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                                borderColor: 'rgba(37, 99, 235, 1)',
                                borderWidth: 2,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                    
                    //Update Total Income
                    document.getElementById('totalIncome').textContent = `RS:${data.totalIncome.toFixed(2)}`;
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

            //Fetch default data (e.g., last 30 days)
            const endDate = new Date().toISOString().split('T')[0];
            const startDate = new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0];
            fetchIncomeData(startDate, endDate);
            
            filterPopup.classList.add('hidden'); //Close the filter popup
        });

        //Initial Load with Default Data (Last 30 Days)
        const endDate = new Date().toISOString().split('T')[0];
        const startDate = new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0];
        fetchIncomeData(startDate, endDate);
    });
    
    //Pie Chart
    var ctxPie = document.getElementById('invoiceStatusChart').getContext('2d');
    
    //Invoice data
    var invoiceCounts = [{{ $paid }}, {{ $unpaid }}, {{ $partiallyPaid }}, {{ $overdue }}, {{ $processing }}];
    var invoicePercentages = [{{ $paidPercent }}, {{ $unpaidPercent }}, {{ $partiallyPaidPercent }}, {{ $overduePercent }}, {{ $processingPercent }}];
    var invoiceLabels = ['Paid', 'Unpaid', 'Partially Paid', 'Overdue', 'Processing'];
    var backgroundColors = ['#22c55e', '#ef4444', '#facc15', '#f97316', '#06b6d4'];
    
    //Create the pie chart
    var invoiceStatusChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: invoiceLabels,
            datasets: [{
                data: invoicePercentages,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 12 },
                    backgroundColor: (context) => context.dataset.data[context.dataIndex] > 0 ? '#000' : null, //Remove background for 0% values
                    borderRadius: 4, //Rounded corners for the background box
                    padding: 6, //Padding around the text
                    formatter: (value, context) => {
                        var index = context.dataIndex;
                        var count = invoiceCounts[index];
                        var name = invoiceLabels[index];
                        return value > 0 ? `${name}: ${value}% (${count})` : ''; //Hide label if value is 0%
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
@endpush
