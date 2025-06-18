 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inquiry Report') }}
        </h2>
    </x-slot>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Inquiry Report</h1>
                <p class="text-gray-600">Generate comprehensive reports on inquiry data and statistics</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <button onclick="printReport()" 
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
                <a href="{{ route('inquiries.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Inquiries
                </a>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>Report Filters
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('inquiries.report') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-2">Date From</label>
                            <input type="date" id="date_from" name="date_from" 
                                   value="{{ request('date_from', Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-2">Date To</label>
                            <input type="date" id="date_to" name="date_to" 
                                   value="{{ request('date_to', Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-chart-bar mr-2"></i>Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
            <!-- Total -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wide mb-1">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-spinner text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">Resolved</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['resolved'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Closed -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Closed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['closed'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-xl text-gray-600"></i>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-1">Rejected</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Statistics -->
        @if(isset($categoryStats) && $categoryStats->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>Inquiries by Category
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="categoryChart" class="w-full h-64"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Status Distribution
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="statusChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detailed Report Table -->
        <div class="bg-white rounded-2xl shadow-lg" id="reportTable">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-table mr-2 text-blue-600"></i>Detailed Inquiry Report
                </h3>
            </div>
            <div class="p-6">
                @if(isset($inquiries) && $inquiries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inquiry->I_ID }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($inquiry->I_Title, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                                    'Resolved' => 'bg-green-100 text-green-800',
                                                    'Closed' => 'bg-gray-100 text-gray-800',
                                                    'Rejected' => 'bg-red-100 text-red-800'
                                                ];
                                                $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                                {{ $inquiry->I_Status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('Y-m-d') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inquiry->I_Source ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary -->
                    <div class="mt-6 p-6 bg-gray-50 rounded-xl">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Report Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Period:</p>
                                <p class="text-gray-900">
                                    {{ request('date_from', Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }} 
                                    to 
                                    {{ request('date_to', Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Total Inquiries:</p>
                                <p class="text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Resolution Rate:</p>
                                <p class="text-gray-900">
                                    {{ isset($stats['total']) && $stats['total'] > 0 ? round(($stats['resolved'] / $stats['total']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Generated on:</p>
                                <p class="text-gray-900">{{ Carbon\Carbon::now()->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-bar text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Data Available</h3>
                        <p class="text-gray-600">No inquiries found for the selected date range.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Print function - hanya maklumat penting
    function printReport() {
        const printContent = `
            <div style="font-family: Arial, sans-serif; padding: 20px;">
                <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px;">
                    <h1 style="margin: 0; font-size: 24pt;">INQUIRY REPORT</h1>
                    <p style="margin: 5px 0; font-size: 12pt;">System SDD - Generated on {{ now()->format('d M Y, H:i') }}</p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h2 style="font-size: 16pt; margin-bottom: 10px;">Report Summary:</h2>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Total Inquiries:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $inquiries->count() }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Report Period:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ request('date_from') ? request('date_from').' to '.request('date_to') : 'All Time' }}</td>
                        </tr>
                        @if(request('status'))
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Filter by Status:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ request('status') }}</td>
                        </tr>
                        @endif
                        @if(request('category'))
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Filter by Category:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ request('category') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h2 style="font-size: 16pt; margin-bottom: 10px;">Inquiry List:</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">ID</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Title</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Category</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Status</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $inquiry->I_ID }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ Str::limit($inquiry->I_Title, 30) }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $inquiry->I_Category }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $inquiry->I_Status }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(isset($stats))
                <div style="margin-bottom: 20px;">
                    <h2 style="font-size: 16pt; margin-bottom: 10px;">Statistics:</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Pending:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $stats['pending'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">In Progress:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $stats['in_progress'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Resolved:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $stats['resolved'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Closed:</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $stats['closed'] ?? 0 }}</td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
        `;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Inquiry Report - {{ now()->format('d M Y') }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                        table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                        th { background-color: #f5f5f5; font-weight: bold; }
                        h1, h2 { margin: 0; }
                        .text-center { text-align: center; }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }

    // Category Chart
    @if(isset($categoryStats) && $categoryStats->count() > 0)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->keys()) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->values()) !!},
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#06B6D4', '#8B5CF6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'In Progress', 'Resolved', 'Closed', 'Rejected'],
                datasets: [{
                    label: 'Number of Inquiries',
                    data: [
                        {{ $stats['pending'] }},
                        {{ $stats['in_progress'] }},
                        {{ $stats['resolved'] }},
                        {{ $stats['closed'] }},
                        {{ $stats['rejected'] }}
                    ],
                    backgroundColor: [
                        '#ffc107', '#17a2b8', '#28a745', '#6c757d', '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    @endif

    function printReport() {
        window.print();
    }
</script>

<style>
    @media print {
        .btn, .card-header, nav, footer {
            display: none !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
        }
    }
</style>
</x-app-layout>