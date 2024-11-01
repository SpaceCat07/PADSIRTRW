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
            'password'=> 'required'
        ]);
        $email = $request->email;
        $password = $request->password;

        // Cari user berdasarkan email di tabel users
        $user = User::where('email', $email)->first();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            if ($user -> aktivasi === 'Activated') {
                // Auth::login($user);
                session() -> regenerate();

                switch ($user->role) {
                    case 'Super_Admin':
                        // return view('superadmin.dashboard');
                        return redirect('/dashboard/superadmin');
                    case 'Admin_RW':
                        // return view('rw.dashboard');
                        return redirect('/dashboard/adminrw');
                    case 'Admin_RT':
                        // return view('rt.dashboard');
                        return redirect('/dashboard/adminrt');
                    case 'Ketua_RW':
                        // return view('rw.dashboard');
                        return redirect('/dashboard/ketuarw');
                    case 'Ketua_RT':
                        // return view('rt.dashboard');
                        return redirect('/dashboard/ketuart');
                    case 'Warga':
                        // return view('warga.dashboard');
                        return redirect('/dashboard/warga');
                    default:
                        Auth::logout();
                        return redirect() -> route('masuk')->with('error', 'Role tidak dikenali.');
                }
            } else {
                return redirect() -> route('masuk') -> with('aktivasi', 'Akun anda belum diaktivasi oleh admin');
            }
        } else {
            return redirect() -> route('masuk') -> with('akun', 'Email atau password anda salah');
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
