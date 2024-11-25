<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::all();
        $users = User::orderBy('id_warga', 'asc') -> get();
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.addUser');
    }

    public function requestCreate ()
    {
        // $roleList = User::select('role') -> distinct() -> get();
        $roleList = ['Warga', 'Admin_RW', 'Admin_RT', 'Ketua_RW', 'Ketua_RT', 'Super_Admin'];
        return view('users.requestAddUser', compact('roleList'));
    }

    public function requestStore (Request $request)
    {
        $this -> validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required',
            'password' => 'required|String|min:8',
            // 'role' => 'required|in:Warga,Admin_RW,Admin_RT,Ketua_RW,Ketua_RT,Super_Admin'
        ]);

        if (DB::table('warga') -> where('id_warga', $request -> nik) -> first()) {
            $users = new User();
            $users -> id_warga = $request -> nik;
            $users -> email = $request -> email;
            $users -> no_hp = $request -> no_hp;
            $users -> password = Hash::make($request -> password);
            $users -> role = $request -> input('role');
            $users -> save();

            return redirect('/masuk') -> with('success', 'Akun pengguna berhasil dibuat');
        } else {
            return redirect('/masuk') -> with('error', 'Akun pengguna gagal dibuat. NIK pada table warga tidak ditemukan');
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required',
            'role' => 'required',
            'password' => 'required|String|min:8',
            'role' => 'required|in:Warga,Admin_RW,Admin_RT,Ketua_RW,Ketua_RT,Super_Admin'
        ]);

        if (DB::table('warga') -> where('id_warga', $request -> nik) -> first()) {
            $users = new User();
            $users -> id_warga = $request -> nik;
            $users -> email = $request -> email;
            $users -> no_hp = $request -> no_hp;
            $users -> password = Hash::make($request -> password);
            $users -> role = $request -> input('role');
            $users -> save();

            return redirect('/account') -> with('success', 'Akun pengguna berhasil dibuat');
        } else {
            return redirect('/account') -> with('error', 'Akun pengguna gagal dibuat. NIK pada table warga tidak ditemukan');
        }

        // $warga = new User();
        // $warga -> id_pengguna = $request -> nik;
        // $warga -> nama = $request -> nama;
        // $warga -> email = $request -> email;
        // $warga -> no_hp = $request -> no_hp;
        // $warga -> role = $request ->input('Role');
        // $warga -> save();

        // return redirect('/hasil');
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
        // $roleList = User::select('role') -> distinct() -> get();
        return view('users.edit', compact('user', 'roleList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request, [
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required|min:10',
            'aktivasi' => 'required',
            // 'role' => 'required|in:Warga,Admin_RW,Admin_RT,Ketua_RW,Ketua_RT,Super_Admin'
        ]);

        $user = User::find($id);
        $user -> email = $request -> email;
        $user -> no_hp = $request -> no_hp;
        $user -> aktivasi = $request -> input('aktivasi');
        // $user -> role = $request -> input('role');
        $user -> save();

        // return redirect('/hasil');
        return redirect() -> route('account.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id) -> delete();
        return redirect() -> route('account.index') -> with('pesan', "Data pengguna dengan id {$id} telah berhasil dihapus");
    }
}
