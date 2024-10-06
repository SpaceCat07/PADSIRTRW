<?php

namespace App\Http\Controllers;

use App\Models\Penjabat_RT;
use App\Models\Penjabat_RW;
use App\Models\Warga;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function wargaMasuk ()
    {
        return view('warga.LoginWarga');
    }

    public function wargaLogin (Request $request)
    {
        $email = $request -> email;
        $password = $request -> password;

        $warga = Warga::where('email', $email) -> first();

        if ($warga) {
            if (Hash::check($password, $warga -> password)) {
                if ($warga -> aktivasi === 'Activated') {
                    Auth::login($warga);
                    session() -> regenerate();
                    return redirect('/hasil');
                } else {
                    return redirect('/warga/masuk') -> with('password', 'Password incorrect');
                }
            } else {
                return redirect('/warga/masuk') -> with('aktivasi', "Account hasn't been activated");
            }
        } else {
            return redirect('/warga/masuk') -> with('email', 'User not found');
        }
    }

    public function wargaLogout (Request $request)
    {
        $request -> session() -> flush();
        Auth::logout();

        // kembali ke route login
        return redirect() -> route('/index');
    }

    public function penjabatRTMasuk ()
    {
        return view('rt.LoginPenjabat');
    }

    public function penjabatRTLogin (Request $request)
    {
        $email = $request -> email;
        $pasword = $request -> password;

        $penjabat = Penjabat_RT::where('email', $email) -> first();

        if ($penjabat) {
            if (Hash::check($pasword, $penjabat -> password)) {
                if ($penjabat -> role === "Admin_RT") {
                    Auth::login($penjabat);
                    session() -> regenerate();
                    return redirect('/hasil');
                    // mengarah ke dashboard admin rt
                } else {
                    Auth::login($penjabat);
                    session() -> regenerate();
                    return redirect('/hasil');
                    // mengarah ke dashboard ketua rt
                }
            } else {
                // mengarah ke login penjabat rt
                return redirect('/rt/masuk') -> with('password', 'Incorrect Password');
            }
        } else {
            // mengarah ke login penjabat rt
            return redirect('/rt/masuk') -> with('email', 'User not Found');
        }
    }

    public function penjabatRTLogout (Request $request)
    {
        $request -> session() -> flush();
        Auth::logout();

        // kembali ke route login
        return redirect() -> route('/index');
    }

    public function penjabatRWMasuk ()
    {
        return view('rw.penjabatLogin');
    }

    public function penjabatRWLogin (Request $request)
    {
        $email = $request -> email;
        $pasword = $request -> password;

        $penjabat = Penjabat_RW::where('email', $email) -> first();

        if ($penjabat) {
            if (Hash::check($pasword, $penjabat -> password)) {
                if ($penjabat -> role === "Admin_RT") {
                    Auth::login($penjabat);
                    session() -> regenerate();
                    return redirect('/hasil');
                    // mengarah ke dashboard admin rt
                } else {
                    Auth::login($penjabat);
                    session() -> regenerate();
                    return redirect('/hasil');
                    // mengarah ke dashboard ketua rt
                }
            } else {
                // mengarah ke login penjabat rt
                return redirect('/rw/masuk') -> with('password', 'Incorrect Password');
            }
        } else {
            // mengarah ke login penjabat rt
            return redirect('/rw/masuk') -> with('email', 'User not Found');
        }
    }

    public function penjabatRWLogout (Request $request)
    {
        $request -> session() -> flush();
        Auth::logout();

        // kembali ke route login
        return redirect() -> route('/index');
    }
}
