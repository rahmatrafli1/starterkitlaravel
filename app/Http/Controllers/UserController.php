<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        // Cek permission
        if (!Gate::allows('view-user')) {
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
        if (!Gate::allows('view-user')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = User::with('roles');

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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
        if (!Gate::allows('add-user')) {
            abort(403, 'Tidak memiliki akses untuk menambah user');
        }

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(UserRequest $request)
    {
        // Cek permission
        if (!Gate::allows('add-user')) {
            abort(403, 'Tidak memiliki akses untuk menambah user');
        }

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
        if (!Gate::allows('edit-user')) {
            abort(403, 'Tidak memiliki akses untuk mengedit user');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(UserRequest $request, User $user)
    {
        // Cek permission
        if (!Gate::allows('edit-user')) {
            abort(403, 'Tidak memiliki akses untuk mengedit user');
        }

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
        if (!Gate::allows('delete-user')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Jangan hapus diri sendiri
        if ($user->id === Auth::id()) {
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
