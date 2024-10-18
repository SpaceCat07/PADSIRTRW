<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function masuk ()
    {
        return view('users.login');
    }
    public function login(Request $request)
    {
        $this -> validate($request, [
            'email' => 'required',
            'password'=> 'required',
            'terms' => 'required|accepted'
        ]);
        $email = $request->email;
        $password = $request->password;
        $syarat = $request -> has('terms');

        // Cari user berdasarkan email di tabel users
        $user = User::where('id_pengguna', $email)->first();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            if ($user -> aktivasi === 'Activated') {
                Auth::login($user);
                session() -> regenerate();

                switch ($user->role) {
                    case 'Super_Admin':
                        return view('superadmin.dashboard');
                        // return redirect('/superadmin/dashboard');
                    case 'Admin_RW':
                        return view('rw.dashboard');
                        // return redirect('/adminrw/dashboard');
                    case 'Admin_RT':
                        return view('rt.dashboard');
                        // return redirect('/adminrt/dashboard');
                    case 'Ketua_RW':
                        return view('rw.dashboard');
                        // return redirect('/ketuarw/dashboard');
                    case 'Ketua_RT':
                        return view('rt.dashboard');
                        // return redirect('/ketuart/dashboard');
                    case 'Warga':
                        return view('warga.dashboard');
                        // return redirect('/warga/dashboard');
                    default:
                        Auth::logout();
                        return redirect('/login')->with('error', 'Role tidak dikenali.');
                }
            } else {
                return redirect('/login') -> with('aktivasi', 'Akun anda belum diaktivasi oleh admin');
            }
        } else {
            return redirect('/login') -> with('akun', 'Email atau password anda salah');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        // Kembali ke halaman login
        return redirect() -> route('masuk');
    }
}
