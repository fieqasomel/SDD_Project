@extends('layouts.app')

@section('title', 'Inquiry Reports - MCMC')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Inquiry Reports</h1>
            <a href="{{ route('mcmc.inquiries.new') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Inquiries
            </a>
        </div>

        <!-- Report Generation Form -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Generate New Report</h3>
            <form method="GET" action="{{ route('mcmc.inquiry-reports.generate') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                        <select name="report_type" id="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Report Type</option>
                            <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                            <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Yearly Report</option>
                            <option value="custom" {{ request('report_type') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                        </select>
                    </div>

                    <div id="month_selector" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                        <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            @for($year = 2020; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}" {{ (request('year', date('Y')) == $year) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div id="custom_date_range" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        @if(isset($reportData))
            <!-- Report Results -->
            <div class="space-y-8">
                <!-- Report Header -->
                <div class="text-center border-b pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Inquiry Report</h2>
                    <p class="text-gray-600">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
                </div>

                <!-- Export Options -->
                <div class="flex justify-center space-x-4 mb-6">
                    <form method="POST" action="{{ route('mcmc.inquiry-reports.pdf') }}" class="inline">
                        @csrf
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="year" value="{{ request('year') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Export as PDF
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('mcmc.inquiry-reports.excel') }}" class="inline">
                        @csrf
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="year" value="{{ request('year') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Export as Excel
                        </button>
                    </form>
                </div>

                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="bg-blue-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $reportData['stats']['total_inquiries'] }}</div>
                        <div class="text-sm text-blue-600">Total Inquiries</div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $reportData['stats']['processed_inquiries'] }}</div>
                        <div class="text-sm text-green-600">Processed</div>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $reportData['stats']['pending_inquiries'] }}</div>
                        <div class="text-sm text-yellow-600">Pending</div>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $reportData['stats']['validated_inquiries'] }}</div>
                        <div class="text-sm text-purple-600">Validated</div>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $reportData['stats']['rejected_inquiries'] }}</div>
                        <div class="text-sm text-red-600">Rejected</div>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $reportData['stats']['non_serious_inquiries'] }}</div>
                        <div class="text-sm text-orange-600">Non-Serious</div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Category Breakdown -->
                    <div class="bg-white p-6 border rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Breakdown</h3>
                        <div class="space-y-3">
                            @foreach($reportData['category_stats'] as $category => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $category }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($count / $reportData['stats']['total_inquiries']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Breakdown -->
                    <div class="bg-white p-6 border rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h3>
                        <div class="space-y-3">
                            @foreach($reportData['status_stats'] as $status => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($count / $reportData['stats']['total_inquiries']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Priority Breakdown -->
                @if($reportData['priority_stats']->count() > 0)
                    <div class="bg-white p-6 border rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Priority Level Breakdown</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @foreach($reportData['priority_stats'] as $priority => $count)
                                <div class="text-center">
                                    <div class="text-2xl font-bold 
                                        {{ $priority == 'Critical' ? 'text-red-600' : 
                                           ($priority == 'High' ? 'text-orange-600' : 
                                           ($priority == 'Medium' ? 'text-yellow-600' : 'text-green-600')) }}">
                                        {{ $count }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $priority }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Monthly Trend (for yearly reports) -->
                @if(!empty($reportData['monthly_trend']))
                    <div class="bg-white p-6 border rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend</h3>
                        <div class="space-y-3">
                            @foreach($reportData['monthly_trend'] as $month => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                                    <div class="flex items-center">
                                        <div class="w-48 bg-gray-200 rounded-full h-3 mr-3">
                                            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $count > 0 ? (($count / max($reportData['monthly_trend'])) * 100) : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Detailed Inquiry List -->
                <div class="bg-white p-6 border rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Inquiry List</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData['inquiries']->take(50) as $inquiry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $inquiry->I_ID }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Str::limit($inquiry->I_Title, 40) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inquiry->I_Category }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $inquiry->I_Status == 'Validated' ? 'bg-green-100 text-green-800' : 
                                                   ($inquiry->I_Status == 'Rejected' ? 'bg-red-100 text-red-800' : 
                                                   ($inquiry->I_Status == 'Non-Serious' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $inquiry->I_Status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inquiry->priority_level ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inquiry->I_Date ? $inquiry->I_Date->format('M d, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($reportData['inquiries']->count() > 50)
                        <p class="mt-4 text-sm text-gray-500 text-center">
                            Showing first 50 inquiries. Total: {{ $reportData['inquiries']->count() }} inquiries.
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('report_type').addEventListener('change', function() {
        const reportType = this.value;
        const monthSelector = document.getElementById('month_selector');
        const customDateRange = document.getElementById('custom_date_range');
        
        if (reportType === 'monthly') {
            monthSelector.style.display = 'block';
            customDateRange.style.display = 'none';
        } else if (reportType === 'custom') {
            monthSelector.style.display = 'none';
            customDateRange.style.display = 'block';
        } else {
            monthSelector.style.display = 'none';
            customDateRange.style.display = 'none';
        }
    });
    
    // Initialize on page load
    document.getElementById('report_type').dispatchEvent(new Event('change'));
</script>
@endsection