@extends('layouts.cetak')
@section('content')
<htmlpagefooter name="page-footer">
    <table width="100%">
        <tr>
            <td width="33%">
                <span style="font-weight: bold; font-style: italic; font-size:7pt;">{{date('d-m-Y')}}</span>
            </td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic; font-size:7pt;">
                {PAGENO}/{nbpg}
            </td>
            <td width="33%" style="text-align: right;font-style: italic; font-size:7pt;font-weight: bold;">
                Dicetak dari Aplikasi APM SMK v.1.0.0
            </td>
        </tr>
    </table>
</htmlpagefooter>
<div class="container-fluid">
    <h3 class="text-center">LEMBAR VERIFIKASI PENJAMINAN MUTU<br>
        SMK CENTRE of EXCELLENCE TAHUN 2020
    </h3>
    <p></p>
    <table class="table">
        <tr>
            <td style="width: 30%">Petugas Supervisi/Verifikasi</td>
            <td style="width: 1px;">:</td>
            <td>{{$sekolah->sekolah_sasaran->verifikator->name}}</td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td>{{$sekolah->nama}}</td>
        </tr>
        <tr>
            <td>NPSN</td>
            <td>:</td>
            <td>{{$sekolah->npsn}}</td>
        </tr>
        <tr>
            <td>Alamat Sekolah</td>
            <td>:</td>
            <td>{{$sekolah->alamat}}</td>
        </tr>
        <tr>
            <td>Kabupaten/Kota</td>
            <td>:</td>
            <td>{{$sekolah->kabupaten}}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>:</td>
            <td>{{$sekolah->provinsi}}</td>
        </tr>
        <tr>
            <td>Nama Kepala Sekolah</td>
            <td>:</td>
            <td>{{$sekolah->nama_kepsek}}</td>
        </tr>
        <tr>
            <td>Nama Pengawas Pembina</td>
            <td>:</td>
            <td>{{$sekolah->nama_pengawas}}</td>
        </tr>
        <tr>
            <td>Jenis/Sektor CoE</td>
            <td>:</td>
            <td>{{$sekolah->sekolah_sasaran->sektor->nama}}</td>
        </tr>
    </table>
    <p></p>
    <p><b>Petunjuk Pengisian:</b>
        <ol>
            <li>Beri tanda (√) pada kolom Ada/Tidak;</li>
            <li>Pada kolom keterangan tulislah kata sesuai jika dokumen pendukung sesuai dengan jawaban di aplikasi atau
                kata tidak sesuai jika dokumen pendukung tidak mendukung jawaban di aplikasi;</li>
        </ol>
    </p>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%" class="text-center">NO.</th>
                    <th style="width: 38%" class="text-center">INDIKATOR/PERTANYAAN</th>
                    <th style="width: 20%" class="text-center">VERIFIKASI/TELAAH DOKUMEN</th>
                    <th style="width: 5%" class="text-center">ADA</th>
                    <th style="width: 7%" class="text-center">TIDAK</th>
                    <th style="width: 30%" class="text-center">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instrumens as $item)
                <tr>
                    <td rowspan="{{($item->telaah_dokumen_count + 1)}}" class="text-center">
                        {{$loop->iteration}}
                    </td>
                    <td rowspan="{{($item->telaah_dokumen_count + 1)}}">
                        {{$item->pertanyaan}}
                    </td>
                </tr>
                @foreach ($item->telaah_dokumen as $telaah_dokumen)
                <tr>
                    <td>
                        {{trim($telaah_dokumen->nama)}}
                    </td>
                    @if($dokumen_verifikasi)
                    <td class="text-center">
                        {{($dokumen_verifikasi->ada->{$item->instrumen_id}->{$telaah_dokumen->dok_id})  ? '' : '√'}}
                    </td>
                    <td class="text-center">
                        {{($dokumen_verifikasi->ada->{$item->instrumen_id}->{$telaah_dokumen->dok_id})  ? '√' : ''}}
                    </td>
                    <td>
                        {{ $dokumen_verifikasi->keterangan->{$item->instrumen_id}->{$telaah_dokumen->dok_id} }}
                    </td>
                    @else
                    <td class="text-center">
                        {!!($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 0) ? '√' : '' :
                        '√'!!}
                    </td>
                    <td class="text-center">
                        {!!($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 1) ? '√' : '' :
                        ''!!}
                    </td>
                    <td>
                        {{($telaah_dokumen->nilai_dokumen) ? $telaah_dokumen->nilai_dokumen->keterangan : ''}}
                    </td>
                    @endif
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <?php
    $kabupaten = $sekolah->kabupaten;
    $kabupaten = str_replace('Kab.', '', $kabupaten);
    $kabupaten = str_replace('Kota', '', $kabupaten);
    ?>
    <table border="0" width="100%">
        <tr>
            <td colspan="3">{{trim($kabupaten)}}, {{App\HelperModel::TanggalIndo(date('Y-m-d'))}}</td>
        </tr>
        <tr>
            <td width="33%" class="text-center">
                Kepala Sekolah
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{$sekolah->nama_kepsek}}<br>
                NIP. {{$sekolah->nip_kepsek}}
            </td>
            <td width="33%" class="text-center">
                Pengawas Pembina
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{$sekolah->nama_pengawas}}<br>
                NIP. {{$sekolah->nip_pengawas}}
            </td>
            <td width="33%" class="text-center">
                Petugas Verifikasi
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{$sekolah->sekolah_sasaran->verifikator->name}}<br>
                NIP. {{$sekolah->sekolah_sasaran->verifikator->nip}}
            </td>
        </tr>
    </table>
</div>
@endsection