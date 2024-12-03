<?php

namespace App\Http\Controllers;

use App\Models\DetailIuranRTPengguna;
use App\Models\IuranRT;
use App\Models\Warga;
use Auth;
use Illuminate\Http\Request;

class ManajemenDetailIuranRTPengguna extends Controller
{
    public function index()
    {
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        // data iuran rt berdasarkan id rt pengguna
        $iuranRT = IuranRT::where('id_rt', $rt_warga)->pluck('id');
        // data detail iuran rt, apakah data iuran rt tersebut ada di dalam $iuranRT
        $detail_iuran = DetailIuranRTPengguna::whereIn('id_iuran_rt', $iuranRT)->get();
        return view('iuranbayar.warga.manajemen.index', compact('detail_iuran'));
    }

    public function detailSelesai($id)
    {
        $detail_iuran = DetailIuranRTPengguna::find($id);
        $detail_iuran -> status = 'selesai';
        $detail_iuran -> save();
        return redirect() -> route('manajemen-detail-iuran-rt-pengguna.index');
    }

    public function detailGagal($id)
    {
        // $detail_iuran = DetailIuranRTPengguna::find($id);
        // $detail_iuran -> status = 'gagal';
        // $detail_iuran -> save();

        DetailIuranRTPengguna::find($id) -> delete();
        return redirect() -> route('manajemen-detail-iuran-rt-pengguna.index');
    }
}
