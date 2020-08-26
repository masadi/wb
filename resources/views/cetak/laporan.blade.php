@extends('layouts.cetak')
@section('content')
<div class="container-fluid">
    <h3>Laporan Hasil Verifikasi</h3>
    <p>Nama Sekolah: {{$laporan->sekolah->nama}}</p>
    <p>Nama Tim Penjaminan Mutu: {{$laporan->penjamin_mutu->name}}</p>
    <p>Detil Laporan: </p>
    {!! $laporan->keterangan !!}
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