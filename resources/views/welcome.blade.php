<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-white">
    <!-- Mobile-First Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-bold text-red-600">{{ config('app.name', 'Laravel') }}</h1>
                </div>

                <!-- Desktop Menu -->
                @if (Route::has('login'))
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-gray-700 hover:text-red-600 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-red-600 transition-colors">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                    Sign Up
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button onclick="toggleMobileMenu()"
                            class="text-gray-700 hover:text-red-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Mobile Menu -->
            @if (Route::has('login'))
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="block py-2 text-gray-700 hover:text-red-600 transition-colors text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="block py-2 text-gray-700 hover:text-red-600 transition-colors text-center">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="block mt-2 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors text-center">
                                Sign Up
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Mobile-Optimized Hero Section -->
    <section class="bg-gradient-to-r from-red-50 to-orange-50 py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                Welcome to
                <span class="text-red-600 block sm:inline">{{ config('app.name', 'Laravel') }}</span>
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 mb-6 sm:mb-8 max-w-3xl mx-auto px-4">
                Your powerful admin dashboard solution built with Laravel and Tailwind CSS.
            </p>
        </div>
    </section>

    <!-- Mobile-Optimized Features Section -->
    <section id="features" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 px-4">
                    Why Choose <span class="text-red-600">Tailadminku?</span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                    A comprehensive admin dashboard solution with cutting-edge features to simplify your application
                    management.
                </p>
            </div>

            <!-- Mobile-First Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-12">
                <!-- Feature 1 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">High Performance</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Built with optimized Laravel framework for maximum performance and lightning-fast loading.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">Easy Customization</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        With Tailwind CSS, you can easily customize the appearance to match your brand identity.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">Secure & Reliable</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Multi-layered security system with authentication, authorization, and protection from common
                        attacks.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">User-Friendly</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Intuitive interface that's easy to understand and use by users of all skill levels.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">Responsive Design</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Perfect display on all devices, from desktop computers to smartphones and tablets.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="text-center p-6 sm:p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-yellow-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">24/7 Support</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Responsive technical support and regular updates with the latest features and improvements.
                    </p>
                </div>
            </div>

            <!-- Mobile-Optimized Call to Action -->
            <div class="text-center px-4">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                    Ready to Get Started with Tailadminku?
                </h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-2xl mx-auto">
                    Join thousands of developers who trust Tailadminku for their projects and experience the difference.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="bg-red-600 text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors shadow-lg">
                            Sign Up Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile-Optimized Footer -->
    <footer class="bg-gray-50 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm sm:text-base text-gray-600">&copy; {{ date('Y') }}
                {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
