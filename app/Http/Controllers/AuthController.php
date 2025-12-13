<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// tambahan untuk proses authentikasi
use Illuminate\Support\Facades\Auth;
use App\Models\User; //untuk akses kelas model user

// untuk bisa menggunakan hash
use Illuminate\Support\Facades\Hash;
use App\Models\Jabatan;

class AuthController extends Controller
{
    // method untuk menampilkan halaman awal login
    public function showLoginForm()
    {
        return view('login');
    }

    // menampilkan halaman daftar
public function showRegisterForm()
{
    $jabatans = Jabatan::orderBy('nama_jabatan')->get(); // panggil model di sini
    return view('register', compact('jabatans'));
}

// proses daftar user baru
public function register(Request $request)
{
    $validated = $request->validate([
        'name'       => ['required', 'string', 'max:255'],
        'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
        'user_group' => ['required', 'string'], // bisa tambahkan rule in:admin,customer,dll
        'password'   => ['required', 'string', 'min:6', 'confirmed'],
    ]);

    $user = User::create([
        'name'       => $validated['name'],
        'email'      => $validated['email'],
        'password'   => Hash::make($validated['password']),
        'user_group' => $validated['user_group'], // langsung dari select
    ]);

    Auth::login($user);
    return redirect('/depan');
}

    // proses validasi data login
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->user_group === 'admin') {
            return redirect('/admin'); // halaman admin
        } elseif ($user->user_group === 'pegawai') {
            return redirect('/depan'); // halaman customer
        } else {
            Auth::logout(); // kalau user_group tidak dikenal
            return redirect('/login')->withErrors(['user_group' => 'Role tidak dikenal.']);
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}


    // method untuk menangani logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ubah password
    public function ubahpassword(){
        return view('ubahpassword');
    }

    // ubah password
    public function prosesubahpassword(Request $request){
        // echo $request->password ;
        $request->validate([
            'password' => 'required|string|min:5',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('depan')->with('success', 'Password berhasil diperbarui!');
    }
}