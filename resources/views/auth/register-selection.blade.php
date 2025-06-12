<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Register for SDD System</h2>
                    <p class="text-lg opacity-90">Choose your account type to get started</p>
                </div>
            </div>
            
            <!-- Registration Options -->
            <div class="p-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Public User Registration -->
                    <a href="{{ route('register.publicuser') }}" class="group block bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-2 text-center">
                        <div class="text-4xl text-blue-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Public User</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">Register as a general user to submit inquiries and complaints</p>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Submit inquiries
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Track progress
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>View notifications
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Manage profile
                            </li>
                        </ul>
                        <div class="inline-flex items-center text-blue-600 font-semibold group-hover:text-blue-700">
                            Register Now <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </a>

                    <!-- Agency Registration -->
                    <a href="{{ route('register.agency') }}" class="group block bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-green-500 hover:bg-green-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-2 text-center">
                        <div class="text-4xl text-green-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Agency</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">Register as a government agency to handle inquiries</p>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Receive inquiries
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Update progress
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Generate reports
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Manage cases
                            </li>
                        </ul>
                        <div class="inline-flex items-center text-green-600 font-semibold group-hover:text-green-700">
                            Register Now <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </a>

                    <!-- MCMC Registration -->
                    <a href="{{ route('register.mcmc') }}" class="group block bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-red-500 hover:bg-red-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-2 text-center">
                        <div class="text-4xl text-red-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">MCMC Staff</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">Register as MCMC staff for administrative access</p>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Monitor all activities
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Generate system reports
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>Manage users
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-4"></i>System oversight
                            </li>
                        </ul>
                        <div class="inline-flex items-center text-red-600 font-semibold group-hover:text-red-700">
                            Register Now <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </a>
                </div>

                <!-- Back to Login -->
                <div class="text-center mt-8">
                    <p class="text-gray-600 mb-4">Already have an account?</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="text-white hover:text-blue-200 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
        </div>
    </div>
</body>
</html>