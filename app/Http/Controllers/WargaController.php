<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Auth;
use Hash;
use Illuminate\Http\Request;

class WargaController extends Controller
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
        return view('warga.RegisterWarga');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $warga = new Warga();
        $warga -> nama = $request -> nama;
        $warga -> email = $request -> email;
        $warga -> no_hp = $request -> no_hp;
        $warga -> username = $request -> username;
        $warga -> password =  Hash::make($request -> password);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
