<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Cari user berdasarkan email di tabel users
        $user = User::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                Auth::login($user);
                session()->regenerate();

                // Mengarahkan berdasarkan role
                switch ($user->role) {
                    case 'Super_Admin':
                        return redirect('/superadmin/dashboard');
                    case 'Admin_RW':
                        return redirect('/adminrw/dashboard');
                    case 'Admin_RT':
                        return redirect('/adminrt/dashboard');
                    case 'Ketua_RW':
                        return redirect('/ketuarw/dashboard');
                    case 'Ketua_RT':
                        return redirect('/ketuart/dashboard');
                    case 'Warga':
                        return redirect('/warga/dashboard');
                    default:
                        Auth::logout();
                        return redirect('/login')->with('error', 'Role tidak dikenali.');
                }
            } else {
                return redirect('/login')->with('password', 'Password salah.');
            }
        } else {
            return redirect('/login')->with('email', 'User tidak ditemukan.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        // Kembali ke halaman login
        return redirect('/index');
    }
}
