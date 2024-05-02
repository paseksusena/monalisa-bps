<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua data pengguna dengan pagination
        $users = User::paginate(10);

        // Kirim data pengguna ke view
        return view('page.admin.admin', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,organik,anorganik',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        // Validasi apakah user yang sedang login adalah administrator
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.index')->with('error', 'Anda tidak memiliki izin untuk menghapus pengguna');
        }

        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User berhasil dihapus');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,organik,anorganik'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('admin.index')->with('success', 'Peran pengguna berhasil diperbarui');
    }
}
