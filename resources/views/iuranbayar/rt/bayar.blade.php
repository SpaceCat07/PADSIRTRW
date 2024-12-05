<h1>Konfirmasi Pembayaran Iuran</h1>

<h2>Iuran Tambahan</h2>
<table>
    <thead>
        <tr>
            <th>Jenis Iuran</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($iuranTambahan as $tambahan)
            <tr>
                <td>{{ $tambahan->jenis_iuran }}</td>
                <td>Rp {{ number_format($tambahan->total_iuran, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2>Iuran Bulanan</h2>
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($iuranBulanan as $bulanan)
            <tr>
                <td>{{ $bulanan->bulan }}</td>
                <td>Rp {{ number_format($bulanan->total_iuran, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<form action="{{ route('bayar-iuran-rt.konfirmasibayar') }}" method="post">
    @csrf

    @php
        use App\Models\Warga;
        use App\Models\RTModel;
        use App\Models\RWModel;

        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        $rw_warga = RTModel::where('id', $rt_warga)->first()->id_rw;
    @endphp
    <p>{{'Nomor Rekening RW anda adalah '. RWModel::find($rw_warga) -> nomer_rekening}}</p>
    <input type="hidden" name="iuran_tambahan" value="{{ json_encode($iuranTambahan->pluck('id')) }}">
    <input type="hidden" name="iuran_bulanan" value="{{ json_encode($iuranBulanan->pluck('id')) }}">
    <input type="number" name="nomor_rekening" id="" placeholder="Nomor Rekening yang anda gunakan">
    <button type="submit">Proses Pembayaran</button>
</form>