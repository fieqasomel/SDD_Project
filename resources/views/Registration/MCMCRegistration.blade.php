<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCMC Staff Registration - SDD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-red-600 via-orange-600 to-pink-800 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">MCMC Staff Registration</h2>
                    <p class="text-lg opacity-90">Register as MCMC staff for administrative access</p>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <!-- Information Alert -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-blue-800 font-semibold">Note:</p>
                            <p class="text-blue-700">This registration is for authorized MCMC staff only. Please ensure you have proper authorization before proceeding.</p>
                        </div>
                    </div>
                </div>

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

                <form method="POST" action="{{ route('register.mcmc.submit') }}" class="space-y-8">
                    @csrf

                    <!-- Staff Information -->
                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-red-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie text-white text-sm"></i>
                            </div>
                            Staff Information
                        </h3>
                        <div class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="50" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}" maxlength="10" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                    <p class="text-sm text-gray-500 mt-1">Maximum 10 characters</p>
                                </div>
                            </div>
                            <div>
                                <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Position/Designation *</label>
                                <select id="position" name="position" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                    <option value="">Select Position</option>
                                    <option value="Director" {{ old('position') == 'Director' ? 'selected' : '' }}>Director</option>
                                    <option value="Deputy Director" {{ old('position') == 'Deputy Director' ? 'selected' : '' }}>Deputy Director</option>
                                    <option value="Assistant Director" {{ old('position') == 'Assistant Director' ? 'selected' : '' }}>Assistant Director</option>
                                    <option value="Principal Assistant Director" {{ old('position') == 'Principal Assistant Director' ? 'selected' : '' }}>Principal Assistant Director</option>
                                    <option value="Senior Manager" {{ old('position') == 'Senior Manager' ? 'selected' : '' }}>Senior Manager</option>
                                    <option value="Manager" {{ old('position') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Assistant Manager" {{ old('position') == 'Assistant Manager' ? 'selected' : '' }}>Assistant Manager</option>
                                    <option value="Executive" {{ old('position') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                    <option value="Senior Executive" {{ old('position') == 'Senior Executive' ? 'selected' : '' }}>Senior Executive</option>
                                    <option value="Officer" {{ old('position') == 'Officer' ? 'selected' : '' }}>Officer</option>
                                    <option value="Senior Officer" {{ old('position') == 'Senior Officer' ? 'selected' : '' }}>Senior Officer</option>
                                    <option value="Administrator" {{ old('position') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
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
                                    <p class="text-sm text-gray-500 mt-1">Use your official MCMC email</p>
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
                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-green-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-lock text-white text-sm"></i>
                            </div>
                            Account Security
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                                <input type="password" id="password" name="password" minlength="8" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" required
                                   class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2 mt-1 mr-3">
                            <label for="terms" class="text-gray-700 leading-relaxed">
                                I confirm that I am an authorized MCMC staff member and agree to the
                                <a href="#" class="text-red-600 hover:text-red-800 font-semibold">Terms and Conditions</a>,
                                <a href="#" class="text-red-600 hover:text-red-800 font-semibold">Privacy Policy</a>, and
                                <a href="#" class="text-red-600 hover:text-red-800 font-semibold">Code of Conduct</a>
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
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-shield-alt mr-2"></i>Register Staff
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="text-center mt-8">
                    <p class="text-gray-600">Already have an account?
                        <a href="{{ route('login') }}" class="text-red-600 hover:text-red-800 font-semibold">
                            Login here
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <a href="{{ route('welcome') }}" class="text-white hover:text-red-200 transition-colors duration-200">
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
