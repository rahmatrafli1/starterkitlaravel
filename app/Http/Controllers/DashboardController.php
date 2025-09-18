<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        // Cek permission
        if (!Gate::allows('view-dashboard')) {
            abort(403, 'Tidak memiliki akses ke dashboard');
        }

        // Statistik untuk dashboard
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        return view('dashboard', compact('totalUsers', 'activeUsers', 'inactiveUsers'));
    }
}
