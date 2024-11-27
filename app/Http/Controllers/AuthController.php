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
                $request -> session() -> regenerate();
                
                switch ($user->role) {
                    case 'Super_Admin':
                        // return view('superadmin.dashboard');
                        return redirect('/dashboard/superadmin');
                        break;
                    case 'Admin_RW':
                        // return view('rw.dashboard');
                        return redirect('/dashboard/adminrw');
                        break;
                    case 'Admin_RT':
                        // return view('rt.dashboard');
                        return redirect('/dashboard/adminrt');
                        break;
                    case 'Ketua_RW':
                        // return view('rw.dashboard');
                        return redirect('/dashboard/ketuarw');
                        break;
                    case 'Ketua_RT':
                        // return view('rt.dashboard');
                        return redirect() -> route('dashboard.ketuart');
                        break;
                    case 'Warga':
                        // return view('warga.dashboard');
                        return redirect('/dashboard/warga');
                        break;
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
