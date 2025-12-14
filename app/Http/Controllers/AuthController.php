<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // Halaman register
    public function showRegisterForm()
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('register', compact('jabatans'));
    }

    // Proses register (tanpa auto-login)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],

            'nip'        => ['nullable', 'string', 'max:11'],
            'jabatan_id' => ['nullable', 'integer', 'exists:jabatans,id'],
            'gender'     => ['required', 'string', 'in:Laki-laki,Perempuan'],
        ]);

        DB::transaction(function () use ($validated) {

            // ambil data jabatan (boleh null)
            $jabatan = null;
            if (!empty($validated['jabatan_id'])) {
                $jabatan = Jabatan::find($validated['jabatan_id']);
            }

            // user_group diisi nama jabatan (atau 'pegawai' default kalau kosong)
            $userGroup = $jabatan?->nama_jabatan ?? 'pegawai';

            // 1. buat user
            $user = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'user_group' => $userGroup,
            ]);

            // gaji pokok diambil dari jabatan (kalau ada), else 0
            $gajiPokok = $jabatan?->gaji_pokok ?? 0;

            // 2. buat pegawai
            Pegawai::create([
                'nip'        => $validated['nip'] ?? null,
                'nama'       => $validated['name'],
                'jabatan_id' => $validated['jabatan_id'] ?? null,
                'gender'     => $validated['gender'],
                'gaji_pokok' => $gajiPokok,
                // kalau nanti kamu tambah kolom user_id di tabel pegawai:
                // 'user_id'    => $user->id,
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->user_group === 'admin') {
                return redirect('/admin');
            } elseif ($user->user_group === 'Guru') {
                return redirect('/guru');
            } elseif ($user->user_group === 'Kepala Sekolah') {
                return redirect('/kepala-sekolah');
            } elseif ($user->user_group === 'Kebersihan dan Keamanan') {
                return redirect('/kebersihan');
            } elseif ($user->user_group === 'Tata Usaha') {
                return redirect('/tatausaha');
            }

            // fallback kalau ada group lain
            return redirect('/depan');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Halaman ubah password
    public function ubahpassword()
    {
        return view('ubahpassword');
    }

    // Proses ubah password
    public function prosesubahpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('depan')->with('success', 'Password berhasil diperbarui!');
    }
}
