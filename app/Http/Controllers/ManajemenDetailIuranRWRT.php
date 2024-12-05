<?php

namespace App\Http\Controllers;

use App\Models\DetailIuranRWRT;
use App\Models\RTModel;
use App\Models\Warga;
use Auth;
use Illuminate\Http\Request;

class ManajemenDetailIuranRWRT extends Controller
{
    public function index()
    {
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        $rw_warga = RTModel::where('id', $rt_warga)->first()->id_rw;

        $detail_iuran = DetailIuranRWRT::where('id_iuran_rw', $rw_warga)->get();
        return view('iuranbayar.rt.manajemen.index', compact('detail_iuran'));
    }

    public function detailSelesai($id)
    {
        $detail_iuran = DetailIuranRWRT::find($id);
        $detail_iuran -> status = 'selesai';
        $detail_iuran -> save();
        return redirect() -> route('manajemen-detail-iuran-rw-rt.index');
    }

    public function detailGagal($id)
    {
        DetailIuranRWRT::find($id) -> delete();
        return redirect() -> route('manajemen-detail-iuran-rw-rt.index');
    }
}
