@extends('layouts.app')

@section('title', 'Public User Dashboard - SDD System')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
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

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- User Info Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-user-circle text-white text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Your Profile</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-user text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Name:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_Name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Email:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_Email ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Phone:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_PhoneNum ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Age:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_Age ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-venus-mars text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Gender:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_Gender ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-id-card text-blue-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">IC Number:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->PU_IC ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Submit Inquiry -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-plus-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Submit Inquiry</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Submit new inquiries and complaints to relevant agencies</p>
                <a href="{{ route('inquiries.create') }}" class="btn-primary inline-block">Submit Now</a>
            </div>
        </div>

        <!-- Track Progress -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Track Progress</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Monitor the status and progress of your submitted inquiries</p>
                <a href="{{ route('inquiries.index') }}" class="btn-primary inline-block">View Progress</a>
            </div>
        </div>

        <!-- Inquiry History -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-history text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Inquiry History</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View your complete history of submitted inquiries</p>
                <a href="{{ route('inquiries.index') }}" class="btn-primary inline-block">View History</a>
            </div>
        </div>

        <!-- Notifications -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bell text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Notifications</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Check updates and notifications about your inquiries</p>
                <a href="#" class="btn-primary inline-block">View Notifications</a>
            </div>
        </div>

        <!-- Manage Profile -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-edit text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Manage Profile</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Update your personal information and account settings</p>
                <a href="#" class="btn-primary inline-block">Edit Profile</a>
            </div>
        </div>

        <!-- Generate Reports -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Generate Reports</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Generate reports of your inquiry activities</p>
                <a href="#" class="btn-primary inline-block">Generate Report</a>
            </div>
        </div>
    </div>
</div>
@endsection