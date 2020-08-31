@extends('layouts.cetak')
@section('content')
<div class="container-fluid">
    <h3 class="text-center">PAKTA INTEGRITAS SEKOLAH</h3>
    <p></p>
    <p>Yang bertanda tangan di bawah ini :</p>
    <table width="100%" style="margin-left: -5px;">
        <tr>
            <td width="10%">Nama</td>
            <td width="1%">:</td>
            <td width="89%">{{$sekolah->nama_kepsek}}</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>{{$sekolah->nip_kepsek}}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>Kepala Sekolah</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>{{$sekolah->nama}}</td>
        </tr>
    </table>
    <p></p>
    <p>Menyatakan bahwa seluruh data yang diisikan dalam instrumen Aplikasi Penjaminan Mutu SMK (APM SMK) sudah sesuai
        dengan data yang sebenarnya.</p>
    <p>Jika dikemudian hari ditemukan ketidaksesuaian antara data yang dikirimkan dengan data yang ada, saya siap
        menerima sanksi baik secara moral atau administrasi.</p>

    <p>{{$sekolah->kabupaten}}, {{$now}}<br>
        Kepala Sekolah</p>
    <p></p>
    <p></p>
    <p></p>
    <p>{{$sekolah->nama_kepsek}}<br>
        NIP. {{$sekolah->nip_kepsek}}</p>
    <p style="page-break-before: always"></p>
    <h3 class="text-center">NILAI RAPOR MUTU SEKOLAH</h3>
    <table class="table table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th scope="col">Nama Sekolah</th>
                <th class="text-center" scope="col">Nilai Rapor Mutu</th>
                <th class="text-center" scope="col">Predikat</th>
                <th class="text-center" scope="col">Kategori</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$sekolah->nama}}</td>
                <td class="text-center">{{$nilai_rapor_mutu}}</td>
                <td class="text-center">{{$predikat_sekolah}}</td>
                <td class="text-center">
                    {!! Helper::bintang_pdf($nilai_rapor_mutu) !!}
                </td>
            </tr>
        </tbody>
    </table>
    <h3 class="text-center">NILAI RAPOR MUTU SEKOLAH PER KOMPONEN</h3>
    <table class="table table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th class="text-center" scope="col">No</th>
                <th class="text-center" scope="col">Komponen</th>
                <th class="text-center" scope="col">Nilai</th>
                <th class="text-center" scope="col">Predikat</th>
                <th class="text-center" scope="col">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai_komponen as $key => $komponen)
            <tr>
                <td class="text-center">{{$key + 1}}</td>
                <td>{{$komponen->nama}}</td>
                <td class="text-center">{{($komponen->nilai_komponen) ? $komponen->nilai_komponen->total_nilai : 0}}
                </td>
                <td class="text-center">{{($komponen->nilai_komponen) ? $komponen->nilai_komponen->predikat : '-'}}</td>
                <td class="text-center">
                    @if($komponen->nilai_komponen)
                    {!! Helper::bintang_pdf($komponen->nilai_komponen->total_nilai) !!}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3 class="text-center">DETIL NILAI RAPOR MUTU SEKOLAH</h3>
    <table class="table table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th class="text-center" scope="col" width="60%" colspan="4">Komponen / Aspek / Indikator</th>
                <th class="text-center" scope="col" width="10%">Nilai</th>
                <th class="text-center" scope="col" width="15%">Predikat</th>
                <th class="text-center" scope="col" width="15%">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai_komponen as $key => $komponen)
            <tr>
                <td class="text-center">{{$key + 1}}</td>
                <td colspan="3">{{$komponen->nama}}</td>
                <td class="text-center">{{($komponen->nilai_komponen) ? $komponen->nilai_komponen->total_nilai : 0}}
                </td>
                <td class="text-center">{{($komponen->nilai_komponen) ? $komponen->nilai_komponen->predikat : '-'}}</td>
                <td class="text-center">
                    @if($komponen->nilai_komponen)
                    {!! Helper::bintang_pdf($komponen->nilai_komponen->total_nilai) !!}
                    @endif
                </td>
            </tr>
            @foreach ($komponen->aspek as $sub_key => $aspek)
            <tr>
                <td class="text-right"></td>
                <td class="text-right" style="border-left: none;">{{$key+1}}.{{$sub_key+1}}</td>
                <td colspan="2">{{$aspek->nama}}</td>
                <td class="text-center">{{($aspek->nilai_aspek) ? $aspek->nilai_aspek->total_nilai : 0}}</td>
                <td class="text-center">{{($aspek->nilai_aspek) ? $aspek->nilai_aspek->predikat : '-'}}</td>
                <td class="text-center">
                    @if($aspek->nilai_aspek)
                    {!! Helper::bintang_pdf($aspek->nilai_aspek->total_nilai) !!}
                    @endif
                </td>
            </tr>
            @foreach ($aspek->instrumen as $sub_sub_key => $instrumen)
            <tr>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td style="vertical-align:middle;" class="text-right">{{$key+1}}.{{$sub_key+1}}.{{$sub_sub_key+1}}</td>
                <td>{{$instrumen->pertanyaan}}</td>
                <td class="text-center">{{($instrumen->nilai_instrumen) ? $instrumen->nilai_instrumen->nilai : 0}}</td>
                <td class="text-center">{{($instrumen->nilai_instrumen) ? $instrumen->nilai_instrumen->predikat : '-'}}
                </td>
                <td class="text-center">
                    @if($instrumen->nilai_instrumen)
                    {!! Helper::bintang_pdf($instrumen->nilai_instrumen->nilai, true) !!}
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection