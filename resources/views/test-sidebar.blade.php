<x-app-layout-with-sidebar>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sidebar Test Page
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">🎉 Sidebar Test Page</h1>
                <p class="text-lg text-gray-600 mb-6">If you can see this page with a sidebar, it's working!</p>
                
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <strong>Success!</strong> Your sidebar is properly integrated.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">✅ Sidebar Features</h3>
                        <ul class="text-sm text-blue-600 space-y-1">
                            <li>• Mobile responsive</li>
                            <li>• User-specific content</li>
                            <li>• Active state highlighting</li>
                            <li>• Smooth animations</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">🎨 Design Elements</h3>
                        <ul class="text-sm text-green-600 space-y-1">
                            <li>• Color-coded by user type</li>
                            <li>• Professional icons</li>
                            <li>• Clean typography</li>
                            <li>• Consistent spacing</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-800 mb-2">⚡ Functionality</h3>
                        <ul class="text-sm text-purple-600 space-y-1">
                            <li>• Route-based navigation</li>
                            <li>• User authentication</li>
                            <li>• Logout integration</li>
                            <li>• Mobile menu toggle</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <strong>Current User Type:</strong> 
                        @if(Auth::guard('publicuser')->check())
                            <span class="text-blue-600 font-semibold">Public User</span>
                        @elseif(Auth::guard('agency')->check())
                            <span class="text-green-600 font-semibold">Agency</span>
                        @elseif(Auth::guard('mcmc')->check())
                            <span class="text-purple-600 font-semibold">MCMC Administrator</span>
                        @else
                            <span class="text-gray-600">Not authenticated</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout-with-sidebar>