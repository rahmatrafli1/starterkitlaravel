<div class="flex flex-col h-full bg-white sidebar-bg">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 flex-shrink-0">
        <div class="flex items-center">
            <h2 class="text-lg font-bold text-red-600">{{ config('app.name', 'Laravel') }}</h2>
        </div>
        <!-- Close button for mobile -->
        <button onclick="closeSidebar()"
            class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-700 border-r-2 border-red-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
            </svg>
            Dashboard
        </a>

        <!-- Users -->
        <a href="{{ route('users.index') }}"
            class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-50 hover:text-gray-900 transition-colors {{ request()->routeIs('users.index') ? 'bg-red-50 text-red-700 border-r-2 border-red-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            Users
        </a>
    </nav>

    <!-- User Info (Mobile) -->
    <div class="lg:hidden border-t border-gray-200 p-4 flex-shrink-0">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-red-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <!-- Theme Toggle (Mobile) -->
        <div class="mt-3 mb-3">
            <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                <span>Theme</span>
            </div>
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button onclick="setTheme('system')"
                    class="theme-btn flex-1 px-2 py-1 text-xs rounded-md transition-colors" data-theme="system">
                    <svg class="h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </button>
                <button onclick="setTheme('light')"
                    class="theme-btn flex-1 px-2 py-1 text-xs rounded-md transition-colors" data-theme="light">
                    <svg class="h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <button onclick="setTheme('dark')"
                    class="theme-btn flex-1 px-2 py-1 text-xs rounded-md transition-colors" data-theme="dark">
                    <svg class="h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Theme Toggle & User Info (Desktop) -->
    <div class="hidden lg:block border-t border-gray-200 p-4 flex-shrink-0">
        <!-- Theme Toggle (Desktop) -->
        <div class="mb-4">
            <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                <span>Theme</span>
            </div>
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button onclick="setTheme('system')"
                    class="theme-btn flex-1 px-3 py-2 text-xs rounded-md transition-colors" data-theme="system">
                    <svg class="h-4 w-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="block">System</span>
                </button>
                <button onclick="setTheme('light')"
                    class="theme-btn flex-1 px-3 py-2 text-xs rounded-md transition-colors" data-theme="light">
                    <svg class="h-4 w-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="block">Light</span>
                </button>
                <button onclick="setTheme('dark')"
                    class="theme-btn flex-1 px-3 py-2 text-xs rounded-md transition-colors" data-theme="dark">
                    <svg class="h-4 w-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span class="block">Dark</span>
                </button>
            </div>
        </div>

        <!-- User Info (Desktop) -->
        <div class="flex items-center mb-3">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-red-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                Logout
            </button>
        </form>
    </div>

    <!-- Theme Script -->
    <script>
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
            body.classList.remove('theme-default', 'theme-light', 'theme-dark', 'theme-system');

            // Apply new theme
            switch (theme) {
                case 'light':
                    body.classList.add('theme-light');
                    break;
                case 'dark':
                    body.classList.add('theme-dark');
                    break;
                case 'system':
                default:
                    // Check system preference
                    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (systemPrefersDark) {
                        body.classList.add('theme-dark');
                    } else {
                        body.classList.add('theme-light');
                    }

                    // Add system class to track that it's following system
                    body.classList.add('theme-system');
                    break;
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

        // Listen for system theme changes
        function setupSystemThemeListener() {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

            mediaQuery.addEventListener('change', function(e) {
                const currentTheme = localStorage.getItem('theme') || 'system';

                // Only update if using system theme
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            });
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'system';
            applyTheme(savedTheme);
            updateThemeButtons(savedTheme);
            setupSystemThemeListener();
        });
    </script>
</div>
