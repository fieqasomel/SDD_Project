@extends('layouts.app')

@section('title', 'MCMC Dashboard - SDD System')

@section('content')

<!-- Hero Section -->
<div class="bg-red-600 text-white">
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

        <!-- Recent Activity & Announcements -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                </div>
                <div class="p-4">
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-plus-circle text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">You submitted a new inquiry</p>
                                <p class="text-xs text-gray-500">Today at 10:30 AM</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-comment-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Agency responded to your inquiry #12345</p>
                                <p class="text-xs text-gray-500">Yesterday at 2:15 PM</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Status updated for inquiry #12340</p>
                                <p class="text-xs text-gray-500">2 days ago</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Announcements -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Announcements</h2>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <h3 class="font-medium text-blue-800">System Maintenance</h3>
                            <p class="text-sm text-gray-600 mt-1">The system will be undergoing maintenance on June 15, 2023 from 2:00 AM to 5:00 AM.</p>
                            <p class="text-xs text-gray-500 mt-2">Posted on June 10, 2023</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                            <h3 class="font-medium text-green-800">New Features Added</h3>
                            <p class="text-sm text-gray-600 mt-1">We've added new features to help you track your inquiries more efficiently.</p>
                            <p class="text-xs text-gray-500 mt-2">Posted on June 5, 2023</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <h3 class="font-medium text-yellow-800">Holiday Notice</h3>
                            <p class="text-sm text-gray-600 mt-1">Our offices will be closed on June 20, 2023 for the national holiday.</p>
                            <p class="text-xs text-gray-500 mt-2">Posted on June 1, 2023</p>
                        </div>
                    </div>
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