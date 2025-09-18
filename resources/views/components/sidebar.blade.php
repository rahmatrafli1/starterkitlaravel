<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-200 ease-in-out lg:translate-x-0"
    x-data="{ sidebarOpen: false }">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 bg-gray-800">
        <h2 class="text-white text-xl font-bold">Admin Panel</h2>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-5 px-2">
        <div class="space-y-1">
            <!-- Dashboard Menu -->
            @can('view-dashboard')
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 7 4-4 4 4" />
                    </svg>
                    Dashboard
                </a>
            @endcan

            <!-- Users Menu -->
            @can('view-user')
                <a href="{{ route('users.index') }}"
                    class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Users
                </a>
            @endcan
        </div>
    </nav>

    <!-- User Info di bagian bawah -->
    <div class="absolute bottom-0 w-full p-4 bg-gray-800">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if (auth()->user()->photo)
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url(auth()->user()->photo) }}"
                        alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">{{ auth()->user()->roles->first()->name ?? 'No Role' }}</p>
            </div>
        </div>
    </div>
</div>
