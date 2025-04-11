@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4"> 
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"> 
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('clients.index') }}">
                <h3 class="text-lg font-semibold">Total Clients</h3>
                <p class="text-2xl font-bold">{{ $totalClients }}</p>
            </a>
        </div>

        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('categories.index') }}">
                <h3 class="text-lg font-semibold">Total Categories</h3>
                <p class="text-2xl font-bold">{{ $totalCategories }}</p>
            </a>
        </div>

        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('products.index') }}">
                <h3 class="text-lg font-semibold">Total Products</h3>
                <p class="text-2xl font-bold">{{ $totalProducts }}</p>
            </a>
        </div>

        <div class="bg-blue-700 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('invoices.index') }}">
                <h3 class="text-lg font-semibold">Total Invoices</h3>
                <p class="text-2xl font-bold">{{ $totalInvoices }}</p>
            </a>
        </div>

        <div class="bg-green-700 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('invoices.index', ['status' => 'paid']) }}">
                <h3 class="text-lg font-semibold">Total Paid Invoices</h3>
                <p class="text-2xl font-bold">{{ $totalPaidInvoices }}</p>
            </a>
        </div>

        <div class="bg-yellow-700 text-white p-4 rounded-lg shadow-md">
            <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}">
                <h3 class="text-lg font-semibold">Total Unpaid Invoices</h3>
                <p class="text-2xl font-bold">{{ $totalUnpaidInvoices }}</p>
            </a>
        </div>

        <div class="bg-indigo-500 text-white p-4 rounded-lg shadow-md sm:col-span-2 lg:col-span-1">
            <h3 class="text-lg font-semibold">Total Income</h3>
            <p class="text-2xl font-bold">${{ number_format($totalIncome, 2) }}</p>
        </div>
    </div>

    <!-- Invoice Status Pie Chart -->
    <div class="mt-4"> 
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">Invoice Status</h3>
            <div class="relative w-full" style="height: 300px;"> 
                <canvas id="invoiceStatusChart" width="400" height="300"></canvas> 
        </div>
    </div>               

    <!-- Income Overview Chart -->
    <div class="bg-white p-4 mt-4 rounded-lg shadow-md relative"> 
        <h3 class="text-xl font-semibold mb-4 flex justify-between items-center">
            Income Overview 
            <button id="filter_icon" class="p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        </h3>

        <!-- Filter Popup -->
        <div id="filterPopup" class="absolute right-0 top-12 bg-white p-4 shadow-lg rounded-lg hidden w-64 z-10">
            <label class="block text-sm font-medium">Filter Type:</label>
            <select id="filterType" class="w-full px-4 py-2 border rounded-lg mt-1">
                <option value="single">Single Date</option>
                <option value="range">Date Range</option>
            </select>

            <!-- Single Date Field -->
            <div id="singleDateField" class="mt-2">
                <label class="block text-sm font-medium">Select Date:</label>
                <input type="date" id="single_date" class="w-full px-4 py-2 border rounded-lg mt-1">
            </div>

            <!-- Date Range Fields -->
            <div id="rangeDateFields" class="mt-2 hidden">
                <label class="block text-sm font-medium">Start Date:</label>
                <input type="date" id="start_date" class="w-full px-4 py-2 border rounded-lg mt-1">
                <label class="block text-sm font-medium mt-2">End Date:</label>
                <input type="date" id="end_date" class="w-full px-4 py-2 border rounded-lg mt-1">
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

        <div class="bg-white p-4 mt-4 rounded-lg shadow-md"> 
            <h3 class="text-xl font-semibold mb-2">Total Income</h3> 
            <p class="text-2xl font-bold" id="totalIncome">RS:{{ number_format($totalIncome, 2) }}</p>
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
            e.stopPropagation(); //Prevent event bubbling
            filterPopup.classList.toggle('hidden');
        });

        //Close Filter Popup when clicking outside
        document.addEventListener('click', function (e) {
            if (!filterPopup.contains(e.target) && !document.getElementById('openFilterPopup').contains(e.target)) {
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
                    if (incomeChart) {
                        incomeChart.destroy();
                    }
                    incomeChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Income',
                                data: data.data,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,                    
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });

                    //Update Total Income
                    document.getElementById('totalIncome').textContent = `$${data.totalIncome.toFixed(2)}`;
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

            //Close the filter popup
            filterPopup.classList.add('hidden');
        });

        //Initial Load with Default Data (Last 30 Days)
        const endDate = new Date().toISOString().split('T')[0];
        const startDate = new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0];
        fetchIncomeData(startDate, endDate);
    });

    //Pie Chart
    var ctx = document.getElementById('invoiceStatusChart').getContext('2d');

    //Invoice data
    var invoiceCounts = [
        {{ $paid }},
        {{ $unpaid }},
        {{ $partiallyPaid }},
        {{ $overdue }},
        {{ $processing }}
    ];

    var invoicePercentages = [
        {{ $paidPercent }},
        {{ $unpaidPercent }},
        {{ $partiallyPaidPercent }},
        {{ $overduePercent }},
        {{ $processingPercent }}
    ];

    var invoiceLabels = ['Paid', 'Unpaid', 'Partially Paid', 'Overdue', 'Processing'];
    var backgroundColors = ['#28a745', '#dc3545', '#ffc107', '#ff5733', '#17a2b8'];

    //Create the pie chart
    var invoiceStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: invoiceLabels,
            datasets: [{
                data: invoicePercentages,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 } }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    backgroundColor: function(context) {
                        //Remove background for 0% values
                        return context.dataset.data[context.dataIndex] > 0 ? '#000' : null;
                    },
                    borderRadius: 4, //Rounded corners for the background box
                    padding: 6, //Padding around the text
                    formatter: (value, context) => {
                        var index = context.dataIndex;
                        var count = invoiceCounts[index];
                        var name = invoiceLabels[index]; 

                        //Hide label if value is 0%
                        return value > 0 ? `${name}: ${value}% (${count})` : '';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
@endpush