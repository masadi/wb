<table class="table" border=1>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="58">Isian Sekolah</td>
        <td colspan="58">Hasil Verifikasi</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @foreach ($komponen as $item)
        <td colspan="{{$item->aspek->sum('instrumen_count')}}">{{$item->nama}}</td>
        @endforeach
        @foreach ($komponen as $item)
        <td colspan="{{$item->aspek->sum('instrumen_count')}}">{{$item->nama}}</td>
        @endforeach
    </tr>
    <tr>
        <td>NPSN</td>
        <td>Nama Sekolah</td>
        <td>Provinsi</td>
        <td>Kabupaten/Kota</td>
        <td>Jumlah Siswa</td>
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                @foreach ($aspek->instrumen as $instrumen)
                <td>{{$instrumen->pertanyaan}}</td>
                @endforeach
            @endforeach
        @endforeach
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                @foreach ($aspek->instrumen as $instrumen)
                <td>{{$instrumen->pertanyaan}}</td>
                @endforeach
            @endforeach
        @endforeach
    </tr>
    @foreach ($sekolah as $s)
    <tr>
        <td>{{$s->npsn}}</td>
        <td>{{$s->nama}}</td>
        <td>{{$s->provinsi}}</td>
        <td>{{$s->kabupaten}}</td>
        <?php
        $jumlah_siswa = '-';
        if($s->rekap_pd){
            $jumlah_siswa = $s->rekap_pd->pd_kelas_10_laki + $s->rekap_pd->pd_kelas_10_perempuan + $s->rekap_pd->pd_kelas_11_laki + $s->rekap_pd->pd_kelas_11_perempuan + $s->rekap_pd->pd_kelas_12_laki + $s->rekap_pd->pd_kelas_12_perempuan + $s->rekap_pd->pd_kelas_13_laki + $s->rekap_pd->pd_kelas_13_perempuan;
        }
        ?>
        <td>{{$jumlah_siswa}}</td>
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                @foreach ($aspek->instrumen as $instrumen)
        <td>
            <?php
            $jawaban_sekolah = $s->user->jawaban_sekolah()->where('instrumen_id', $instrumen->instrumen_id)->first();
            ?>
            {{($jawaban_sekolah) ? $jawaban_sekolah->nilai : '-'}}
        </td>
                @endforeach
            @endforeach
        @endforeach
        @foreach ($komponen as $item)
            @foreach ($item->aspek as $aspek)
                @foreach ($aspek->instrumen as $instrumen)
        <td>
            <?php
            $jawaban = $s->user->jawaban()->where('instrumen_id', $instrumen->instrumen_id)->first();
            ?>
            {{($jawaban) ? $jawaban->nilai : '-'}}
        </td>
                @endforeach
            @endforeach
        @endforeach
    </tr>
    @endforeach
    <tr>
        <td></td>
    </tr>
</table>