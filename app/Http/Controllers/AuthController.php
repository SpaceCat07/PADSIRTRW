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
            'nik' => 'required|min:10',
            'password'=> 'required',
            'terms' => 'required|accepted'
        ]);
        $nik = $request->nik;
        $password = $request->password;
        $syarat = $request -> has('terms');

        // Cari user berdasarkan email di tabel users
        $user = User::where('id_pengguna', $nik)->first();

        if (Auth::attempt(['nik' => $nik, 'password' => $password]) && $syarat && $user -> aktivasi === 'Activated') {
            Auth::login($user);
            session() -> regenerate();

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
