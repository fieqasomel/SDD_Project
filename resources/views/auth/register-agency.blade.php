<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Registration - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-600 via-emerald-600 to-teal-800 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Agency Registration</h2>
                    <p class="text-lg opacity-90">Register your government agency for SDD System</p>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="p-8">
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

                <form method="POST" action="{{ route('register.agency.submit') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Agency Information -->
                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-green-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-building text-white text-sm"></i>
                            </div>
                            Agency Information
                        </h3>
                        <div class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="agency_name" class="block text-sm font-semibold text-gray-700 mb-2">Agency Name *</label>
                                    <input type="text" id="agency_name" name="agency_name" value="{{ old('agency_name') }}" maxlength="50" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}" maxlength="10" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                    <p class="text-sm text-gray-500 mt-1">Maximum 10 characters</p>
                                </div>
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Agency Category *</label>
                                <select id="category" name="category" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                    <option value="">Select Category</option>
                                    <option value="Ministry" {{ old('category') == 'Ministry' ? 'selected' : '' }}>Ministry</option>
                                    <option value="Department" {{ old('category') == 'Department' ? 'selected' : '' }}>Department</option>
                                    <option value="Statutory Body" {{ old('category') == 'Statutory Body' ? 'selected' : '' }}>Statutory Body</option>
                                    <option value="Local Authority" {{ old('category') == 'Local Authority' ? 'selected' : '' }}>Local Authority</option>
                                    <option value="Federal Agency" {{ old('category') == 'Federal Agency' ? 'selected' : '' }}>Federal Agency</option>
                                    <option value="State Agency" {{ old('category') == 'State Agency' ? 'selected' : '' }}>State Agency</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-blue-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-address-book text-white text-sm"></i>
                            </div>
                            Contact Information
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Office Address *</label>
                                <textarea id="address" name="address" rows="3" maxlength="225" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none">{{ old('address') }}</textarea>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Official Email Address *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="50" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Office Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., 0312345678" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Security -->
                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-red-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-lock text-white text-sm"></i>
                            </div>
                            Account Security
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                                <input type="password" id="password" name="password" minlength="8" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" required
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2 mt-1 mr-3">
                            <label for="terms" class="text-gray-700 leading-relaxed">
                                I confirm that I am authorized to register this agency and agree to the 
                                <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Terms and Conditions</a> 
                                and <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <a href="{{ route('register') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Selection
                        </a>
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-building mr-2"></i>Register Agency
                        </button>
                    </div>
                </form>
                
                <!-- Login Link -->
                <div class="text-center mt-8">
                    <p class="text-gray-600">Already have an account? 
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-semibold">
                            Login here
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="text-white hover:text-green-200 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>