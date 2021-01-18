<table class="table" border=1>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="22">Isian Sekolah</td>
        <td colspan="22">Hasil Verifikasi</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @foreach ($komponen as $item)
        <td colspan="{{$item->aspek_count}}">{{$item->nama}}</td>
        @endforeach
        @foreach ($komponen as $item)
        <td colspan="{{$item->aspek_count}}">{{$item->nama}}</td>
        @endforeach
    </tr>
    <tr>
        <td>NPSN</td>
        <td>Nama Sekolah</td>
        <td>Provinsi</td>
        <td>Kabupaten/Kota</td>
        <td>Sektor</td>
        <td>Jumlah Siswa</td>
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                <td>{{$aspek->nama}}</td>
            @endforeach
        @endforeach
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                <td>{{$aspek->nama}}</td>
            @endforeach
        @endforeach
    </tr>
    @foreach ($sekolah as $s)
    <tr>
        <td>{{$s->npsn}}</td>
        <td>{{$s->nama}}</td>
        <td>{{$s->provinsi}}</td>
        <td>{{$s->kabupaten}}</td>
        <td>{{($s->sekolah_sasaran->sektor) ? $s->sekolah_sasaran->sektor->nama : '-'}}</td>
        <?php
        $jumlah_siswa = '-';
        if($s->rekap_pd){
            $jumlah_siswa = $s->rekap_pd->pd_kelas_10_laki + $s->rekap_pd->pd_kelas_10_perempuan + $s->rekap_pd->pd_kelas_11_laki + $s->rekap_pd->pd_kelas_11_perempuan + $s->rekap_pd->pd_kelas_12_laki + $s->rekap_pd->pd_kelas_12_perempuan + $s->rekap_pd->pd_kelas_13_laki + $s->rekap_pd->pd_kelas_13_perempuan;
        }
        ?>
        <td>{{$jumlah_siswa}}</td>
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
        <td>
            <?php
            $nilai_aspek_sekolah = $s->user->nilai_aspek_sekolah()->where('aspek_id', $aspek->id)->first();
            ?>
            {{($nilai_aspek_sekolah) ? $nilai_aspek_sekolah->total_nilai : $s->user->user_id}}
        </td>
            @endforeach
        @endforeach
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
        <td>
            <?php
            $nilai_aspek_verifikasi = $s->user->nilai_aspek_verifikasi()->where('aspek_id', $aspek->id)->first();
            ?>
            {{($nilai_aspek_verifikasi) ? $nilai_aspek_verifikasi->total_nilai : '-'}}
        </td>
            @endforeach
        @endforeach
    </tr>
    @endforeach
    <tr>
        <td></td>
    </tr>
</table>