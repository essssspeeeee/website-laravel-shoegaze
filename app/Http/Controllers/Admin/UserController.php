<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->where('name','like','%'.$term.'%')
                  ->orWhere('email','like','%'.$term.'%');
            });
        }

        // available role options with display label => value
        $roles = [
            'Admin'   => 'admin',
            'Petugas' => 'petugas',
            'User'    => 'user',
        ];

        if ($request->filled('role') && $request->role !== 'Semua') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('id','desc')->paginate(10);

        return view('dashboard.users', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,petugas,user',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Nonaktif',
            'password' => 'required|string|min:6|confirmed',
        ]);
        // ensure lowercase role
        $validated['role'] = strtolower($validated['role']);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,petugas,user',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Nonaktif',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if(isset($validated['role'])){
            $validated['role'] = strtolower($validated['role']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // reset password to default if requested
        if ($request->filled('reset_password') && $request->reset_password) {
            $validated['password'] = Hash::make('password'); // default password
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
