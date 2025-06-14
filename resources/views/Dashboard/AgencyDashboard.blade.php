<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agency Dashboard') }}
        </h2>
    </x-slot>
<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 text-white">
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
    <!-- Agency Info Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Agency Information</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-building text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Agency Name:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_Name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Email:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_Email ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Phone:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_PhoneNum ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-tags text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Category:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_Category ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-user text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Username:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_userName ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-map-marker-alt text-green-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Address:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->A_Address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Features Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- View Inquiries -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-inbox text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">View Inquiries</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">View and manage all inquiries assigned to your agency</p>
                <a href="#" class="btn-success inline-block">View Inquiries</a>
            </div>
        </div>

        <!-- Update Progress -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-edit text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Update Progress</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Update the status and progress of ongoing inquiries</p>
                <a href="#" class="btn-success inline-block">Update Progress</a>
            </div>
        </div>

        <!-- Provide Feedback -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-comments text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Provide Feedback</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Provide responses and feedback to public users</p>
                <a href="#" class="btn-success inline-block">Provide Feedback</a>
            </div>
        </div>

        <!-- Search Inquiries -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Search Inquiries</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Search and filter inquiries by various criteria</p>
                <a href="#" class="btn-success inline-block">Search</a>
            </div>
        </div>

        <!-- Generate Reports -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Generate Reports</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Generate performance and activity reports</p>
                <a href="#" class="btn-success inline-block">Generate Reports</a>
            </div>
        </div>

        <!-- Manage Profile -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-edit text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Manage Profile</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Update agency information and account settings</p>
                <a href="#" class="btn-success inline-block">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>