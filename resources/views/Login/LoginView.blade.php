@extends('layouts.app')

@section('title', 'Login - SDD System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl mx-auto" x-data="{ selectedUserType: '{{ old('user_type') }}' }">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-sign-in-alt text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Login to SDD System</h2>
                    <p class="text-lg opacity-90">Select your user type and enter your credentials</p>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="p-8">
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <h6 class="text-red-800 font-semibold">Please correct the following errors:</h6>
                        </div>
                        <ul class="text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- User Type Selection -->
                    <div>
                        <label class="block text-lg font-bold text-gray-800 mb-4">Select User Type</label>
                        <div class="grid md:grid-cols-3 gap-4">
                            <!-- Public User -->
                            <div class="relative">
                                <input type="radio" name="user_type" value="public_user" id="public_user" 
                                       x-model="selectedUserType" class="sr-only">
                                <label for="public_user" 
                                       class="block p-6 border-2 rounded-2xl cursor-pointer transition-all duration-300 text-center"
                                       :class="selectedUserType === 'public_user' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50'">
                                    <div class="text-4xl text-blue-600 mb-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Public User</h3>
                                    <p class="text-sm text-gray-600">General Users</p>
                                </label>
                            </div>

                            <!-- Agency -->
                            <div class="relative">
                                <input type="radio" name="user_type" value="agency" id="agency" 
                                       x-model="selectedUserType" class="sr-only">
                                <label for="agency" 
                                       class="block p-6 border-2 rounded-2xl cursor-pointer transition-all duration-300 text-center"
                                       :class="selectedUserType === 'agency' ? 'border-green-500 bg-green-50 shadow-lg' : 'border-gray-200 hover:border-green-300 hover:bg-green-50'">
                                    <div class="text-4xl text-green-600 mb-3">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Agency</h3>
                                    <p class="text-sm text-gray-600">Government Agencies</p>
                                </label>
                            </div>

                            <!-- MCMC -->
                            <div class="relative">
                                <input type="radio" name="user_type" value="mcmc" id="mcmc" 
                                       x-model="selectedUserType" class="sr-only">
                                <label for="mcmc" 
                                       class="block p-6 border-2 rounded-2xl cursor-pointer transition-all duration-300 text-center"
                                       :class="selectedUserType === 'mcmc' ? 'border-red-500 bg-red-50 shadow-lg' : 'border-gray-200 hover:border-red-300 hover:bg-red-50'">
                                    <div class="text-4xl text-red-600 mb-3">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">MCMC</h3>
                                    <p class="text-sm text-gray-600">MCMC Staff</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-600">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Register here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="text-white hover:text-blue-200 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>
@endsection