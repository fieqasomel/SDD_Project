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
                    <span class="text-gray-800 font-semibold">{{ is_array($user->A_Category) ? implode(', ', $user->A_Category) : ($user->A_Category ?? 'N/A') }}</span>
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
</div>