<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MySebenarnya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-sm bg-opacity-95">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20 rounded-full mb-6 backdrop-blur-sm">
                        <i class="fas fa-shield-alt text-4xl"></i>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">Welcome to MySebenarnya</h1>
                    <p class="text-xl mb-2 opacity-90">Stakeholder Dialogue and Dispute Resolution System</p>
                    <p class="text-lg opacity-80">Connecting Public Users, Government Agencies, and MCMC</p>
                </div>
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
            </div>
            
            <!-- Main Content -->
            <div class="px-8 py-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Get Started</h2>
                    <p class="text-gray-600 text-lg">Choose an option below to access the system</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                    <a href="{{ route('login') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-lg">
                        <i class="fas fa-sign-in-alt mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                        Login to Your Account
                    </a>
                    <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-purple-600 text-purple-600 font-semibold rounded-full shadow-lg hover:shadow-xl hover:bg-purple-600 hover:text-white transform hover:-translate-y-1 transition-all duration-300 text-lg">
                        <i class="fas fa-user-plus mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                        Create New Account
                    </a>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="group text-center p-6 rounded-2xl hover:bg-gradient-to-br hover:from-blue-50 hover:to-purple-50 transition-all duration-300 hover:shadow-lg">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-user text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Public Users</h3>
                        <p class="text-gray-600 leading-relaxed">Submit inquiries and track progress with our intuitive interface</p>
                    </div>
                    
                    <div class="group text-center p-6 rounded-2xl hover:bg-gradient-to-br hover:from-green-50 hover:to-emerald-50 transition-all duration-300 hover:shadow-lg">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Government Agencies</h3>
                        <p class="text-gray-600 leading-relaxed">Efficiently manage and respond to citizen inquiries</p>
                    </div>
                    
                    <div class="group text-center p-6 rounded-2xl hover:bg-gradient-to-br hover:from-red-50 hover:to-pink-50 transition-all duration-300 hover:shadow-lg">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-pink-600 text-white rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">MCMC Staff</h3>
                        <p class="text-gray-600 leading-relaxed">Administrative oversight and comprehensive monitoring</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white text-opacity-80">&copy; {{ date('Y') }} MySebenarnya. All rights reserved.</p>
        </div>
    </div>
</body>
</html>