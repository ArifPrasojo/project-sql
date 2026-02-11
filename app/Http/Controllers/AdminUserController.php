<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class AdminUserController extends Controller
{
    /**
     * Tampilkan semua user.
     */
    public function index()
    {
        // Ambil data user, urutkan terbaru, dan paginate 10 per halaman
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,dosen,mahasiswa'],
            // Validasi Kondisional: NIM wajib jika Mahasiswa, NIK wajib jika Dosen/Admin
            'nim'  => ['nullable', 'unique:users,nim', 'required_if:role,mahasiswa'],
            'nik'  => ['nullable', 'unique:users,nik', 'required_if:role,dosen', 'required_if:role,admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'nim'  => $request->role === 'mahasiswa' ? $request->nim : null,
            'nik'  => $request->role !== 'mahasiswa' ? $request->nik : null,
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Create User',
            'description' => 'Admin menambahkan user baru: ' . $request->name,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,dosen,mahasiswa'],
            // Validasi Unique tapi abaikan ID user yang sedang diedit
            'nim'  => ['nullable', Rule::unique('users')->ignore($user->id), 'required_if:role,mahasiswa'],
            'nik'  => ['nullable', Rule::unique('users')->ignore($user->id), 'required_if:role,dosen', 'required_if:role,admin'],
            // Password boleh kosong jika tidak ingin diganti
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'nim'  => $request->role === 'mahasiswa' ? $request->nim : null,
            'nik'  => $request->role !== 'mahasiswa' ? $request->nik : null,
        ];

        // Hanya update password jika input tidak kosong
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update User',
            'description' => 'Admin mengubah data user: ' . $user->name,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Delete User',
            'description' => 'Admin menghapus user: ' . $name,
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
