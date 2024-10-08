<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use Auth;
use Hash;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.addUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'nik' => 'required|min:10',
            'nama' => 'required|min:5',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required|min:10',
            'role' => 'required'
        ]);

        $warga = new User();
        $warga -> id_pengguna = $request -> nik;
        $warga -> nama = $request -> nama;
        $warga -> email = $request -> email;
        $warga -> no_hp = $request -> no_hp;
        $warga -> role = $request ->input('Role');
        $warga -> save();

        return redirect('/hasil');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request, [
            'nama' => 'required|min:5',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required|min:10'
        ]);

        $user = User::find($id);
        $user -> nama = $request -> nama;
        $user -> email = $request -> email;
        $user -> no_hp = $request -> no_hp;
        $user -> save();

        return redirect('/hasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
