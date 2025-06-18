@extends('layouts.app')

@section('title', 'Public User Dashboard - MySebenarnya System')

@section('content')
<!-- Hero Section -->
<div class="bg-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="text-center lg:text-left mb-6 lg:mb-0">
                <div class="flex items-center justify-center lg:justify-start mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold">Welcome, {{ $user->PU_Name ?? 'Public User' }}!</h1>
                        <p class="text-lg opacity-90 mt-1">Manage your inquiries and track progress from your dashboard</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Panel Below Navbar -->
<div class="w-full bg-white shadow-md mb-6">
    <div class="max-w-7xl mx-auto px-4 py-4">
                <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Inquiries -->
            <div class="bg-blue-50 rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Inquiries</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-yellow-50 rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Pending</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-purple-50 rounded-2xl shadow-lg p-6 border-l-4 border-purple-400 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide mb-1">In Progress</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-spinner text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-green-50 rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Resolved</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['resolved'] }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
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
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Search Inquiries -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Search Inquiries</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Quick search through all inquiries by ID, title, or category</p>
                        <a href="{{ route('inquiries.search') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>Search Now
                        </a>
                    </div>
                </div>

                <!-- Public Inquiries Directory -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-eye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Public Inquiries</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Browse all public inquiries for transparency and reference</p>
                        <a href="{{ route('inquiries.history') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-eye mr-2"></i>Browse Public
                        </a>
                    </div>
                </div>

                <!-- My Inquiries -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-folder-open text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">My Inquiries</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">View and manage all your submitted inquiries</p>
                        <a href="{{ route('inquiries.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-folder-open mr-2"></i>View Mine
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Quick Links</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">User Guide</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2">
                        <i class="fas fa-question-circle text-green-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">FAQ</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-2">
                        <i class="fas fa-headset text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Contact Support</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Report Issue</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection