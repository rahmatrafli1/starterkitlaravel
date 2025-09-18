# Laravel 12 Custom Auth dengan TailAdmin Dashboard

## 1. Setup Project

```bash
# Install Laravel 12
composer create-project laravel/laravel laravel-auth-app

# Masuk ke direktori project
cd laravel-auth-app

# Install dependencies yang diperlukan
composer require spatie/laravel-permission
npm install alpinejs tailwindcss @tailwindcss/forms
```

## 2. Konfigurasi Database

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_auth_app
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Setup Tailwind CSS

Buat file `tailwind.config.js`:
```js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

Edit file `resources/css/app.css`:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

## 4. Setup Alpine.js

Edit file `resources/js/app.js`:
```js
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

## 5. Migration Files

### User Migration (modify default)

```php
<?php
// database/migrations/2014_10_12_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nomor_telepon')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

## 6. Models

### User Model

```php
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_telepon',
        'photo',
        'status',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope untuk user yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Cek apakah user aktif
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
```

## 7. Seeders

### Permission Seeder

```php
<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Jalankan database seeder
     */
    public function run(): void
    {
        // Buat permissions
        $permissions = [
            'add-user',
            'edit-user', 
            'delete-user',
            'view-user',
            'view-dashboard',
            'edit-profile'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Buat roles dan assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $ownerRole = Role::create(['name' => 'owner']);
        $karyawanRole = Role::create(['name' => 'karyawan']);

        // Admin mendapat semua permission
        $adminRole->givePermissionTo($permissions);

        // Owner mendapat permission terbatas
        $ownerRole->givePermissionTo([
            'view-user',
            'view-dashboard', 
            'edit-profile'
        ]);

        // Karyawan mendapat permission terbatas
        $karyawanRole->givePermissionTo([
            'edit-user',
            'view-user',
            'view-dashboard',
            'edit-profile'
        ]);
    }
}
```

### User Seeder

```php
<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeder
     */
    public function run(): void
    {
        $roles = ['admin', 'owner', 'karyawan'];
        $statuses = ['active', 'inactive'];

        // Buat admin utama
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'nomor_telepon' => '08123456789',
            'status' => 'active',
        ]);
        $admin->assignRole('admin');

        // Buat 49 user lainnya
        for ($i = 1; $i <= 49; $i++) {
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'nomor_telepon' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'status' => $statuses[array_rand($statuses)],
            ]);
            
            // Assign role secara acak
            $role = $roles[array_rand($roles)];
            $user->assignRole($role);
        }
    }
}
```

### Database Seeder

```php
<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database aplikasi
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
```

## 8. Controllers

### Auth Controller

```php
<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah user aktif
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !$user->isActive()) {
            return back()->withErrors([
                'email' => 'Akun tidak aktif atau tidak ditemukan.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nomor_telepon' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        // Assign role default (karyawan)
        $user->assignRole('karyawan');

        Auth::login($user);

        return redirect('dashboard');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

### Dashboard Controller

```php
<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        // Cek permission
        if (!auth()->user()->can('view-dashboard')) {
            abort(403, 'Tidak memiliki akses ke dashboard');
        }

        // Statistik untuk dashboard
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        return view('dashboard', compact('totalUsers', 'activeUsers', 'inactiveUsers'));
    }
}
```

### User Controller

```php
<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        // Cek permission
        if (!auth()->user()->can('view-user')) {
            abort(403, 'Tidak memiliki akses untuk melihat daftar user');
        }

        return view('users.index');
    }

    /**
     * API untuk mendapatkan data user (untuk VanillaJS)
     */
    public function api(Request $request)
    {
        // Cek permission
        if (!auth()->user()->can('view-user')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = User::with('roles');

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($users);
    }

    /**
     * Tampilkan form tambah user
     */
    public function create()
    {
        // Cek permission
        if (!auth()->user()->can('add-user')) {
            abort(403, 'Tidak memiliki akses untuk menambah user');
        }

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        // Cek permission
        if (!auth()->user()->can('add-user')) {
            abort(403, 'Tidak memiliki akses untuk menambah user');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nomor_telepon' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'status' => ['required', 'in:active,inactive'],
            'role' => ['required', 'exists:roles,name'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ];

        // Handle upload foto
        if ($request->hasFile('photo')) {
            $userData['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create($userData);
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit user
     */
    public function edit(User $user)
    {
        // Cek permission
        if (!auth()->user()->can('edit-user')) {
            abort(403, 'Tidak memiliki akses untuk mengedit user');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Cek permission
        if (!auth()->user()->can('edit-user')) {
            abort(403, 'Tidak memiliki akses untuk mengedit user');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nomor_telepon' => ['nullable', 'string', 'max:15'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'status' => ['required', 'in:active,inactive,deleted'],
            'role' => ['required', 'exists:roles,name'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'status' => $request->status,
        ];

        // Update password jika diisi
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle upload foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $userData['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($userData);
        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Cek permission
        if (!auth()->user()->can('delete-user')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Jangan hapus diri sendiri
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Tidak dapat menghapus akun sendiri'], 400);
        }

        // Hapus foto jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json(['success' => 'User berhasil dihapus']);
    }
}
```

## 9. Routes

```php
<?php
// routes/web.php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route publik
Route::get('/', function () {
    return view('welcome');
});

// Route auth
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Route yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/api', [UserController::class, 'api'])->name('users.api');
});
```

## 10. Views

### Layout Utama

```php
<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Auth App') }}</title>

    <!-- Scripts dan Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div id="app">
        @auth
            @include('components.sidebar')
            
            <!-- Main Content -->
            <div class="lg:ml-64">
                <!-- Header -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <h1 class="text-xl font-semibold text-gray-900">
                                    @yield('title', 'Dashboard')
                                </h1>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="py-6">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <!-- Alert Messages -->
                        @if(session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        @else
            @yield('content')
        @endauth
    </div>

    <!-- Scripts tambahan -->
    @stack('scripts')
</body>
</html>
```

### Sidebar Component

```php
<!-- resources/views/components/sidebar.blade.php -->

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-200 ease-in-out lg:translate-x-0" x-data="{ sidebarOpen: false }">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 7 4-4 4 4"/>
                </svg>
                Dashboard
            </a>
            @endcan

            <!-- Users Menu -->
            @can('view-user')
            <a href="{{ route('users.index') }}" 
               class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
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
                @if(auth()->user()->photo)
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url(auth()->user()->photo) }}" alt="{{ auth()->user()->name }}">
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
```

## 11. Jalankan Project

```bash
# Publish spatie permission migration
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Jalankan migration
php artisan migrate

# Jalankan seeder
php artisan db:seed

# Link storage
php artisan storage:link

# Build assets
npm run build

# Jalankan server
php artisan serve
```

## 12. Testing

- Login dengan: admin@example.com / password (role: admin)
- Login dengan: user1@example.com / password (role: bervariasi)
- Test semua fitur CRUD users
- Test permission system
- Test pencarian dan filtering

## Fitur Utama:

1. ✅ Custom Authentication (login, register, logout)
2. ✅ Spatie Permission dengan 3 role berbeda
3. ✅ TailAdmin-inspired responsive design
4. ✅ VanillaJS untuk table dengan performa tinggi
5. ✅ Sidebar component terpisah
6. ✅ User management lengkap
7. ✅ Upload foto user
8. ✅ Pencarian dan filtering
9. ✅ Konfirmasi hapus
10. ✅ Seeder 50 user dengan role berbeda
11. ✅ Responsive design
12. ✅ Permission-based access control

Project siap digunakan dan mudah dikembangkan lebih lanjut!
