@extends('layouts.cetak')
@section('content')
<htmlpagefooter name="page-footer">
	<table width="100%">
        <tr>
            <td width="33%">
                <span style="font-weight: bold; font-style: italic; font-size:7pt;">{DATE j-m-Y}</span>
            </td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic; font-size:7pt;">
                {PAGENO}/{nbpg}
            </td>
            <td width="33%" style="text-align: right;font-style: italic; font-size:7pt;">
                Dicetak dari Aplikasi APM SMK V.1.0.0
            </td>
        </tr>
    </table>
</htmlpagefooter>
<div class="container-fluid">
    <h3 class="text-center">INSTRUMEN MONITORING DAN EVALUASI<br>
        HASIL WORKSHOP PEMBELAJARAN<br>
        SMK MENUJU COE<br>
        TAHUN 2020
    </h3>
    <p></p>
    <table width="100%" style="margin-left: -5px;">
        <tr>
            <td width="20%">Nama Sekolah</td>
            <td width="1%">:</td>
            <td width="79%">{{$laporan->sekolah->nama}}</td>
        </tr>
        <tr>
            <td>NPSN</td>
            <td>:</td>
            <td>{{$laporan->sekolah->npsn}}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{$laporan->sekolah->alamat}}</td>
        </tr>
        <tr>
            <td>Petugas Monev</td>
            <td>:</td>
            <td>{{$laporan->sekolah->pendamping->nama}}</td>
        </tr>
    </table>
    <p></p>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-yellow">
                <th class="text-center">No</th>
                <th class="text-center">Program</th>
                <th class="text-center">Kegiatan</th>
                <th class="text-center">Indikator Keberhasilan</th>
                <th class="text-center">Dokumen yang ditelaah</th>
                <th class="text-center">Kendala</th>
                <th class="text-center">Solusi</th>
                <th class="text-center">Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $nilai = 0;
            ?>
            @foreach ($monev as $item)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{$item->nama}}</td>
                <td>{!! $item->kegiatan !!}</td>
                <td>{!! $item->indikator !!}</td>
                <td>
                    @foreach ($item->dokumen_program as $dokumen_program)
                        @if($dokumen_program->poin)
                            @if($dokumen_program->nilai_afirmasi)
                            {!! ($dokumen_program->nilai_afirmasi->ada)  ? '√ ' : 'x ' !!}
                            <?php
                            $nilai += $dokumen_program->nilai_afirmasi->ada;
                            ?>
                            @endif
                        @endif
                        {{$dokumen_program->nama}}
                        <br>
                    @endforeach
                </td>
                <td>
                    @foreach ($item->dokumen_program as $dokumen_program)
                    {{($dokumen_program->nilai_afirmasi) ? $dokumen_program->nilai_afirmasi->kendala : '-'}}<br>
                    @endforeach
                </td>
                <td>
                    @foreach ($item->dokumen_program as $dokumen_program)
                    {{($dokumen_program->nilai_afirmasi) ? $dokumen_program->nilai_afirmasi->solusi : '-'}}<br>
                    @endforeach
                </td>
                <td>
                    @foreach ($item->dokumen_program as $dokumen_program)
                    {{($dokumen_program->nilai_afirmasi) ? $dokumen_program->nilai_afirmasi->tindak_lanjut : '-'}}<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p></p>
    <table class="table table-bordered">
        <tr>
            <td width="50%">
                <strong>Hasil Monev</strong><br><br>
                Perhitungan = jumlah dokumen yang ada (real) / total dokumen yang harus ada <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= {{$nilai}} / 89 <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= butuh rumus <br>
                <br>
                <br>
                Kriteria nilai :
                <table class="table table-bordered" style="width: 50%">
                    <thead>
                        <tr>
                            <th>Tingkat Kinerja</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>91 - 100</td>
                            <td>Sangat Baik</td>
                        </tr>
                        <tr>
                            <td>76 - 90</td>
                            <td>Baik</td>
                        </tr>
                        <tr>
                            <td>61 - 75</td>
                            <td>Cukup Baik</td>
                        </tr>
                        <tr>
                            <td>46 - 60</td>
                            <td>Kurang Baik</td>
                        </tr>
                        <tr>
                            <td>0 - 45</td>
                            <td>Tidak Baik</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td width="50%">
                <strong>Catatan Hasil Monev</strong><br><br>
                {{$laporan->catatan}}
            </td>
        </tr>
    </table>
    <p></p>
    <table width="100%" style="margin-left: -5px;">
        <tr>
            <td width="33%" class="text-center" style="padding-top: 0px;">
                <p>Mengetahui;</p>
                <p>Pengawas Pembina,</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>{{$laporan->sekolah->nama_pengawas}}<br>
                    NIP. {{$laporan->sekolah->nip_pengawas}}</p>
            </td>
            <td width="33%" class="text-center" style="padding-top: 0px;">
                <p>Menyetujui;</p>
                <p>Kepala {{$laporan->sekolah->nama}}</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>{{$laporan->sekolah->nama_kepsek}}<br>
                    NIP. {{$laporan->sekolah->nip_kepsek}}</p>
            </td>
            <td width="33%" class="text-center" style="padding-top: 0px;">
                <p class="text-right">{{$laporan->sekolah->kabupaten}}, {{Helper::TanggalIndo($now)}}</p>
                <p>Petugas Monev</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>{{$laporan->sekolah->pendamping->nama}}<br>
                    NIP. {{$laporan->sekolah->pendamping->nip}}</p>
            </td>
        </tr>
    </table>
</div>
@endsection