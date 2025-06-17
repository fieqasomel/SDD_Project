<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Staff Info Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-orange-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-user-tie text-white text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Staff Information</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-user text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Name:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_Name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Email:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_Email ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Phone:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_PhoneNum ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-briefcase text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Position:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_Position ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-user-circle text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Username:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_userName ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-map-marker-alt text-red-600 w-5 mr-3"></i>
                    <span class="text-gray-600 font-medium mr-2">Address:</span>
                    <span class="text-gray-800 font-semibold">{{ $user->M_Address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>