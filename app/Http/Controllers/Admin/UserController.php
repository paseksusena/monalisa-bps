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
        // Ambil semua data pengguna dengan pagination dengan user tampil 20
        $users = User::paginate(20);

        // Kirim data pengguna ke view
        return view('page.admin.admin', compact('users'));
    }


    public function store(Request $request)
    {
        // validasi dengan request beberapa parameter
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,organik,anorganik',
        ]);

        //memamnggil model untuk membuat user dengan beberapa parameter
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        //kembali ke halaman jika user ditambahkan
        return redirect()->route('admin.index')->with('success', 'User berhasil ditambahkan.');
    }

    //function untuk menghapus user
    public function destroy(User $user)
    {
        // Validasi apakah user yang sedang login adalah administrator
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.index')->with('error', 'Anda tidak memiliki izin untuk menghapus pengguna');
        }

        //kembali ke halaman jika user berhasil di hapus
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User berhasil dihapus');
    }
    //function untuk update data user
    public function update(Request $request, User $user)
    {
        $user = User::find($request->id); //mengambil Id user yang akan di edit
        $request->validate([
            'role' => 'required|in:admin,organik,anorganik'
        ]);

        // Validasi apakah user yang sedang login adalah administrator
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui peran pengguna');
        }

        $user->update([
            'role' => $request->role //rubah role admin
        ]);

        //kembali ke halaman jika user berhasil terupdate
        return redirect()->route('admin.index')->with('success', 'Peran pengguna berhasil diperbarui');
    }

    //function edit
    public function edit($id)
    {

        $user = User::find($id);
        return response()->json($user);
    }
}
