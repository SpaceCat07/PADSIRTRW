<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{
    public function setPassword($id)
    {
        $user = User::find($id);
        return view('users.setPassword', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::find($id);
        $user->password = bcrypt($request->password);
        $user->save();

        session()->forget('passwordForgot');

        return redirect()->route('masuk')->with('success', 'Password has been changed');
    }
}
