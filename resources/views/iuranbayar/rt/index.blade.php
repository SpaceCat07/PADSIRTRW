@php
    use App\Models\DetailIuranRWRT;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Warga;

    $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
@endphp

<form action="{{route('bayar-iuran-rt.bayar')}}" method="post">
    @csrf

    <h1>bayar iuran tambahan</h1>
    <div class="additional-payment">
        <table>
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Jenis Iuran</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listIuranTambahan as $tambahan)
                    <tr>
                        @php
                            $detailIuranTambahan = DetailIuranRWRT::where('id_iuran_rw', $tambahan->id)
                                ->where('id_rt', $rt_warga)
                                ->first();
                        @endphp
                        <td>
                            @if (is_null($detailIuranTambahan) || $detailIuranTambahan->status === 'gagal')
                                <input type="checkbox" name="iuran_tambahan[]" value="{{ $tambahan->id }}" />
                            @endif
                        </td>
                        <td>{{ $tambahan->jenis_iuran }}</td>
                        <td>Rp {{ number_format($tambahan->total_iuran, 0, ',', '.') }}</td>
                        <td>
                            @if ( optional($detailIuranTambahan) -> status === 'belum')
                            Belum dibayar
                            @else
                            {{optional($detailIuranTambahan)->status}}
                            @endif
                        </td>
                        <!-- <td class="{{ $tambahan->status === 'Lunas' ? 'status-paid' : 'status-due' }}">{{ $tambahan->status }}</td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <h1>bayar iuran bulanan</h1>
    <div class="additional-payment">
        <table>
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Bulan</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listIuranBulanan as $bulanan)
                    <tr>
                        @php
                            $detailIuranBulanan = DetailIuranRWRT::where('id_iuran_rw', $bulanan->id)
                                ->where('id_rt', $rt_warga)
                                ->first();
                        @endphp
                        <td>
                            @if (is_null($detailIuranBulanan) || $detailIuranBulanan->status === 'gagal')
                                <input type="checkbox" name="iuran_bulanan[]" value="{{ $bulanan->id }}" />
                            @endif
                        </td>
                        <td>{{ $bulanan->bulan }}</td>
                        <td>Rp {{ number_format($bulanan->total_iuran, 0, ',', '.') }}</td>
                        <td>
                            @if (optional($detailIuranBulanan)-> status === 'belum')
                            Belum dibayar
                            @else
                            <!-- {{$bulanan -> status}} -->
                             {{optional($detailIuranBulanan)->status}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button type="submit">Bayar</button>
</form>