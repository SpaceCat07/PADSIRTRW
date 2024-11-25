<?php

namespace App\Http\Controllers;

use App\Models\KeuanganRW;
use App\Models\RTModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Storage;

class KeuanganRWController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga') -> where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin) -> first() -> id_rw;
        $listKeu = KeuanganRW::where('id_rw', $rw_admin ) -> get();

        return view('rw.menkeu.index', compact('listKeu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisList = ['D', 'K'];
        return view('rw.menkeu.create', compact('jenisList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga') -> where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin) -> first() -> id_rw;


        $this->validate($request, [
            'jenis' => 'required|in:D, K',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file',
            'keterangan' => 'required|String',
            'tanggal' => 'required|date',
        ]);

        if ($request -> hasFile('path_file')) {
            $file = $request -> file('path_file');
            $fileNameWithExt = $file -> getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file -> getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '_'. $extension;

            $file -> storeAs('KeuanganRW', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        KeuanganRW::create([
            'id_rw' => $rw_admin,
            'jenis' => $request -> input('jenis'),
            'jumlah' => $request -> input('jumlah'),
            'path_file' => $filenameToStore,
            'keterangan' => $request -> input('keterangan'),
            'tanggal' => $request -> input('tanggal')
        ]);

        return redirect() -> route('RW.Keuangan.index') -> with('pesan', "Laporan Keuangan RW telah berhasil dibuat");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = KeuanganRW::findOrFail($id);
        return Storage::download('KeuanganRW/' . $laporan -> path_file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporan = KeuanganRW::findOrFail($id);
        // $jenisList = KeuanganRW::select('jenis')->distinct()->get();
        $jenisList = ['D', 'K'];

        return view('rw.menkeu.edit', compact('laporan', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // // find the id rt of the admin
        // $rt_admin = DB::table('warga') -> where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt;
        // // find the id rw of the rt from admin
        // $rw_admin = RTModel::find($rt_admin) -> first() -> id_rw;


        // $this->validate($request, [
        //     'jenis' => 'required',
        //     'jumlah' => 'required|numeric',
        //     'path_file' => 'required|file',
        //     'keterangan' => 'required|String',
        //     'tanggal' => 'required|date',
        // ]);

        // KeuanganRW::where($id) -> update([
        //     'id_rw' => $rw_admin,
        //     'jenis' => $request -> input('jenis'),
        //     'jumlah' => $request -> input('jumlah'),
        //     'path_file' => $request -> input('path_file'),
        //     'keterangan' => $request -> input('keterangan'),
        //     'tanggal' => $request -> input('tanggal')
        // ]);

        // // return redirect() -> route('RW.Keuangan.index') -> with('pesan', "Laporan Keuangan RW dengan id {$id} telah berhasil diubah");

        // ini dari gpt
        // Find the existing KeuanganRW record
        $keuanganRW = KeuanganRW::findOrFail($id);

        // Get the id_rt of the logged-in admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        // Find the id_rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin)->id_rw;

        // Validate the incoming request
        $this->validate($request, [
            'jenis' => 'required',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file', // The file is optional during update
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Handle the file upload (only if a new file is uploaded)
        if ($request->hasFile('path_file')) {
            // Delete the old file from the storage if it exists
            Storage::delete('KeuanganRW/' . $keuanganRW->path_file);
            // if ($keuanganRW->path_file && Storage::exists('KeuanganRW/' . $keuanganRW->path_file)) {
            // }

            // Get the new file details
            $file = $request->file('path_file');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'KeuanganRW' directory
            $file->storeAs('KeuanganRW', $filenameToStore);

            // Update the record with the new file path
            $keuanganRW->path_file = $filenameToStore;
        }

        // Update the KeuanganRW record
        $keuanganRW->update([
            'id_rw' => $rw_admin,
            'jenis' => $request->input('jenis'),
            'jumlah' => $request->input('jumlah'),
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal')
        ]);

        // Redirect with a success message
        return redirect()->route('RW.Keuangan.index')->with('pesan', "Laporan Keuangan RW dengan id {$id} telah berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KeuanganRW::where('id', $id) -> delete();

        return redirect() -> route('RW.Keuangan.index') -> with('pesan', "Laporan Keuangan RW dengan id {$id} telah berhasil dihapus");
    }

    public function showImage(string $filename)
    {
        return Storage::get('KeuanganRW'. $filename);
    }
}
