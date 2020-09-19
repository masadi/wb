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
    <h3>Laporan Hasil Verifikasi</h3>
    <p>Nama Sekolah: {{$laporan->sekolah->nama}}</p>
    <p>Nama Tim Penjaminan Mutu: {{$laporan->penjamin_mutu->name}}</p>
    <p>Detil Laporan: </p>
    {!!$laporan->sekolah->sekolah_sasaran->waiting->keterangan!!}
    <p style="page-break-before: always"></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Komponen</th>
                <th class="text-center">Aspek</th>
                <th class="text-center">Instrumen</th>
                <th class="text-center">Jawaban Sekolah</th>
                <th class="text-center">Jawaban Verifikator</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach($all_komponen as $komponen)
            @foreach ($komponen->aspek as $aspek)
            @foreach ($aspek->instrumen as $instrumen)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$komponen->nama}}</td>
                <td>{{$aspek->nama}}</td>
                <td>{{$instrumen->pertanyaan}}</td>
                <td class="text-center">{{($instrumen->jawaban) ? $instrumen->jawaban->nilai : '-'}}</td>
                <td class="text-center">{{($instrumen->jawaban_penjamin_mutu) ? $instrumen->jawaban_penjamin_mutu->nilai : '-'}}</td>
                <td>{{($instrumen->jawaban_penjamin_mutu) ? $instrumen->jawaban_penjamin_mutu->keterangan : '-'}}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection