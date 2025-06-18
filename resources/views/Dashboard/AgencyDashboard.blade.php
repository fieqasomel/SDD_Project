@extends('layouts.app')

@section('title', 'Agency Dashboard - SDD System')

@section('content')

<!-- Hero Section -->
<div class="bg-teal-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="text-center lg:text-left mb-6 lg:mb-0">
                <div class="flex items-center justify-center lg:justify-start mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold">Welcome, {{ $user->A_Name ?? 'Agency' }}!</h1>
                        <p class="text-lg opacity-90 mt-1">Manage inquiries and provide responses to public users</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Statistics Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Pending Inquiries</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-inbox text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Inquiries</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
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
</div>
@endsection