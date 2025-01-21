<?php

namespace App\Http\Controllers;

use App\Mail\MailPembayaranGagal;
use App\Models\DetailIuranRTPengguna;
use App\Models\IuranRT;
use App\Models\User;
use App\Models\Warga;
use App\Notifications\NotifPembayaranGagal;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Storage;

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

    public function keGagal($id)
    {
        $detail_iuran = DetailIuranRTPengguna::find($id);
        return view('iuranbayar.warga.manajemen.gagal', compact('detail_iuran'));
    }

    public function detailGagal(Request $request, $id)
    {
        // $detail_iuran = DetailIuranRTPengguna::find($id);
        // $detail_iuran -> status = 'gagal';
        // $detail_iuran -> save();

        // DetailIuranRTPengguna::find($id) -> delete();
        $this->validate($request, [
            'keterangan' => 'required|string'
        ]);

        // mencari data detail gambar berdasarkan id
        $detail_iuran = DetailIuranRTPengguna::find($id);

        // mencari data user pembayar dengan id dari detail iuran
        $id_pengguna = $detail_iuran -> id_pengguna;

        // mencari data pengguna berdasarkan data id_pengguna dari table pengguna
        $user = User::find($id_pengguna);

        // dd($user, $detail_iuran);

        $content = [
            'name' => 'Sistem Informasi Masyarakat (SIMAS)',
            'subject' => 'Pembayaran Iuran RT Gagal',
            'body1' => 'Pembayaran iuran dengan id '. $detail_iuran -> id,
            'body' => 'Pembayaran iuran RT anda gagal. Keterangan: ' . $request -> keterangan,
            'body2' => 'Silahkan hubungi ketua RT untuk informasi lebih lanjut.'
        ];

        // mengirim email ke pengguna yang melakukan pembayaran iuran rt
        // $user -> notify(new NotifPembayaranGagal($request -> keterangan, $detail_iuran));
        // Notification::send($user, new NotifPembayaranGagal($request -> keterangan, $detail_iuran));
        // try {
        //     $user->notify(new NotifPembayaranGagal( $request->keterangan, $detail_iuran));
        // } catch (Exception $e) {
        //     return redirect()->route('manajemen-detail-iuran-rt-pengguna.index')->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        // }

        // dd($user -> email);

        Mail::to($user -> email) -> send(new MailPembayaranGagal($content));

        // menghapus data gambar yang tersimpan di dalam storage
        Storage::delete('BuktiPembayaranIuranRTPengguna/'.$detail_iuran -> bukti_pembayaran);

        // menghapus data detail iuran yang ada di database
        $detail_iuran -> delete();


        return redirect() -> route('manajemen-detail-iuran-rt-pengguna.index') -> with('success', 'Data iuran RT pengguna berhasil dihapus');
    }

    public function show($id)
    {
        $gambar = DetailIuranRTPengguna::find($id);
        return Storage::get('BuktiPembayaranIuranRTPengguna/'.$gambar -> bukti_pembayaran);
    }
}
