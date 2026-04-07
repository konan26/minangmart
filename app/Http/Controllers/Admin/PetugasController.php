<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PetugasController extends Controller
{
    public function index()
    {
        // Get users who only have the role 'petugas'
        $petugasUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'petugas');
        })->latest()->get();

        return view('admin.petugas.index', compact('petugasUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('petugas');

        return redirect()->route('admin.petugas.index')->with('success', 'Akun Petugas berhasil ditambahkan.');
    }

    public function destroy(User $petuga)
    {
        // Quick verify they have the role
        if ($petuga->hasRole('petugas')) {
            $petuga->delete();
            return redirect()->route('admin.petugas.index')->with('success', 'Akun Petugas berhasil dihapus.');
        }

        return redirect()->route('admin.petugas.index')->with('error', 'Gagal menghapus: Akun tersebut bukan Petugas.');
    }
}
