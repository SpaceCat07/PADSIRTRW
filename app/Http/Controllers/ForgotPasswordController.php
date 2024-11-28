<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('users.forgotPassword');
    }

    public function validation(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'nik' => 'required|numeric',
        // ]);

        $user = User::where('email', $request -> email) -> first();
        if($user -> id_warga == $request -> nik)
        {
            session(['passwordForgot' => $user -> id]);
            return redirect() -> route('reset-password', $user -> id);
        }
        else
        {
            return back() -> with('fail', 'Email or NIK is incorrect');
        }
    }
}
