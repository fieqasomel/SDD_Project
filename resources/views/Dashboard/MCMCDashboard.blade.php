@extends('layouts.app')

@section('title', 'MCMC Dashboard - SDD System')

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-r from-red-600 to-orange-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="text-center lg:text-left mb-6 lg:mb-0">
                <div class="flex items-center justify-center lg:justify-start mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold">Welcome, {{ $user->M_Name ?? 'MCMC Staff' }}!</h1>
                        <p class="text-lg opacity-90 mt-1">Administrative oversight and system management</p>
                        <span class="inline-block bg-white bg-opacity-20 text-white px-4 py-1 rounded-full text-sm font-bold mt-2 backdrop-blur-sm">ADMINISTRATOR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- System Statistics -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Registered Agencies</p>
                    <p class="text-3xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Inquiries</p>
                    <p class="text-3xl font-bold">{{ $stats['total_inquiries'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-500 to-rose-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Assignments</p>
                    <p class="text-3xl font-bold">{{ $stats['total_assignments'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Inquiries</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_inquiries'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress_inquiries'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-spinner text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Resolved</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['resolved_inquiries'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Month</p>
                    <p class="text-3xl font-bold text-cyan-600">{{ $stats['this_month_inquiries'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Features -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Inquiry Management -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Inquiry Management</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View, manage, and track all system inquiries</p>
                <a href="{{ route('inquiries.index') }}" class="btn-danger inline-block">Manage Inquiries</a>
            </div>
        </div>

        <!-- Assignment Management -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Assignment Management</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Assign inquiries to agencies and track progress</p>
                <a href="{{ route('assignments.index') }}" class="btn-danger inline-block">Manage Assignments</a>
            </div>
        </div>

        <!-- System Reports -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">System Reports</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Generate comprehensive inquiry and assignment reports</p>
                <a href="{{ route('inquiries.report') }}" class="btn-danger inline-block">Generate Reports</a>
            </div>
        </div>

        <!-- Agency Overview -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Agency Overview</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View all registered agencies and their categories</p>
                <a href="{{ route('inquiries.index', ['category' => '']) }}" class="btn-danger inline-block">View Agencies</a>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Search & Filter</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Advanced search and filtering of inquiries</p>
                <a href="{{ route('inquiries.search') }}" class="btn-danger inline-block">Search Inquiries</a>
            </div>
        </div>

        <!-- Pending Inquiries -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pending Inquiries</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View all pending inquiries awaiting assignment</p>
                <a href="{{ route('mcmc.inquiries.new') }}" class="btn-danger inline-block">View New Inquiries</a>
            </div>
        </div>

        <!-- Rejected Assignments -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-gray-500 to-slate-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-times-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Rejected Assignments</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View assignments rejected by agencies for reassignment</p>
                <a href="{{ route('assignments.rejected') }}" class="btn-danger inline-block">View Rejected</a>
            </div>
        </div>

        <!-- Verification Status -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clipboard-check text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Verification Status</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Monitor agency verification of assigned inquiries</p>
                <a href="{{ route('assignments.index', ['verification' => 'pending']) }}" class="btn-danger inline-block">Monitor Verification</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-bolt text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('mcmc.inquiries.new') }}" class="flex items-center justify-center px-4 py-3 border-2 border-red-200 text-red-600 font-semibold rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                <i class="fas fa-inbox mr-2"></i>New Inquiries
            </a>
            <a href="{{ route('assignments.index') }}" class="flex items-center justify-center px-4 py-3 border-2 border-red-200 text-red-600 font-semibold rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                <i class="fas fa-tasks mr-2"></i>Assignments
            </a>
            <a href="{{ route('assignments.report') }}" class="flex items-center justify-center px-4 py-3 border-2 border-red-200 text-red-600 font-semibold rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                <i class="fas fa-chart-bar mr-2"></i>Reports
            </a>
            <a href="{{ route('inquiries.search') }}" class="flex items-center justify-center px-4 py-3 border-2 border-red-200 text-red-600 font-semibold rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Search
            </a>
        </div>
    </div>
</div>
@endsection