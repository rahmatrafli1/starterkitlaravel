<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Auth App') }}</title>

    <!-- Scripts dan Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Theme Styles */
        body.theme-system,
        body.theme-light {
            background-color: #ffffff;
        }

        body.theme-system .bg-gray-100,
        body.theme-light .bg-gray-100 {
            background-color: #f9fafb;
        }

        body.theme-system .bg-white,
        body.theme-light .bg-white {
            background-color: #ffffff;
        }

        body.theme-system .sidebar-bg,
        body.theme-light .sidebar-bg {
            background-color: #fefefe;
        }

        body.theme-dark {
            background-color: #1f2937;
            color: #f9fafb;
        }

        body.theme-dark .bg-white {
            background-color: #374151;
        }

        body.theme-dark .bg-gray-100 {
            background-color: #1f2937;
        }

        body.theme-dark .text-gray-900 {
            color: #f9fafb;
        }

        body.theme-dark .text-gray-700 {
            color: #d1d5db;
        }

        body.theme-dark .text-gray-600 {
            color: #9ca3af;
        }

        body.theme-dark .text-gray-500 {
            color: #6b7280;
        }

        body.theme-dark .border-gray-200 {
            border-color: #4b5563;
        }

        body.theme-dark .sidebar-bg {
            background-color: #374151;
        }

        body.theme-dark .hover\:bg-gray-50:hover {
            background-color: #4b5563;
        }

        body.theme-dark .hover\:bg-gray-100:hover {
            background-color: #4b5563;
        }

        /* System theme follows OS preference when theme-system class is present */
        @media (prefers-color-scheme: dark) {
            body.theme-system {
                background-color: #1f2937;
                color: #f9fafb;
            }

            body.theme-system .bg-white {
                background-color: #374151;
            }

            body.theme-system .bg-gray-100 {
                background-color: #1f2937;
            }

            body.theme-system .text-gray-900 {
                color: #f9fafb;
            }

            body.theme-system .text-gray-700 {
                color: #d1d5db;
            }

            body.theme-system .text-gray-600 {
                color: #9ca3af;
            }

            body.theme-system .text-gray-500 {
                color: #6b7280;
            }

            body.theme-system .border-gray-200 {
                border-color: #4b5563;
            }

            body.theme-system .sidebar-bg {
                background-color: #374151;
            }

            body.theme-system .hover\:bg-gray-50:hover {
                background-color: #4b5563;
            }

            body.theme-system .hover\:bg-gray-100:hover {
                background-color: #4b5563;
            }
        }
    </style>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        function setTheme(theme) {
            // Save theme to localStorage
            localStorage.setItem('theme', theme);

            // Apply theme
            applyTheme(theme);

            // Update button states
            updateThemeButtons(theme);
        }

        function applyTheme(theme) {
            const body = document.body;

            // Remove existing theme classes
            body.classList.remove('theme-default', 'theme-light', 'theme-dark');

            // Apply new theme
            switch (theme) {
                case 'light':
                    body.classList.add('theme-light');
                    break;
                case 'dark':
                    body.classList.add('theme-dark');
                    break;
                default:
                    body.classList.add('theme-default');
            }
        }

        function updateThemeButtons(activeTheme) {
            const buttons = document.querySelectorAll('.theme-btn');
            buttons.forEach(button => {
                const theme = button.getAttribute('data-theme');
                if (theme === activeTheme) {
                    button.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                    button.classList.remove('text-gray-600', 'hover:text-gray-900');
                } else {
                    button.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                    button.classList.add('text-gray-600', 'hover:text-gray-900');
                }
            });
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'default';
            applyTheme(savedTheme);
            updateThemeButtons(savedTheme);
        });
    </script>
</head>

<body class="bg-gray-100">
    <div id="app" class="flex h-screen bg-gray-100">
        @auth
            <!-- Mobile Sidebar Overlay -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
                onclick="closeSidebar()"></div>

            <!-- Sidebar (Mobile + Desktop) -->
            <div id="mobile-sidebar"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white sidebar-bg shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:relative lg:flex lg:flex-col">
                @include('components.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="bg-white sidebar-bg shadow-sm border-b border-gray-200 h-16 flex-shrink-0">
                    <div class="px-4 sm:px-6 lg:px-8 h-full">
                        <div class="flex justify-between items-center h-full">
                            <div class="flex items-center">
                                <!-- Mobile Menu Button -->
                                <button onclick="toggleSidebar()"
                                    class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>

                                <h1 class="ml-2 lg:ml-0 text-lg sm:text-xl font-semibold text-gray-900 truncate">
                                    @yield('title', 'Dashboard')
                                </h1>
                            </div>

                            <!-- User Menu -->
                            <div class="flex items-center space-x-2 sm:space-x-4">
                                <span
                                    class="text-sm text-gray-700 hidden sm:block truncate max-w-32">{{ auth()->user()->name }}</span>

                                <!-- Mobile User Menu -->
                                <div class="relative lg:hidden">
                                    <button onclick="toggleUserMenu()"
                                        class="flex items-center p-2 rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </button>

                                    <!-- Mobile User Dropdown -->
                                    <div id="user-menu"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white sidebar-bg rounded-md shadow-lg py-1 z-50">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                            {{ auth()->user()->name }}
                                        </div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Desktop Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
                                    @csrf
                                    <button type="submit"
                                        class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="flex-1 overflow-auto">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <!-- Alert Messages -->
                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        @else
            <!-- Guest Layout -->
            <div class="min-h-screen w-full">
                @yield('content')
            </div>
        @endauth
    </div>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');

            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Close sidebar when clicking on links (mobile)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#mobile-sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
