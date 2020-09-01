@extends('layouts.cetak')
@section('content')
<div class="container-fluid">
    <h3 class="text-center">BERITA ACARA <br>
        VERIFIKASI DAN VALIDASI INSTRUMEN SEKOLAH <br>
        CENTRE OF EXCELLENCE (CoE) <br>
    </h3>
    <p></p>
    <p>Pada hari ini, {{Helper::TanggalIndo($now, true)}}, telah dilaksanakan verifikasi dan validasi instrumen sekolah SMK Centre of Excellence (COE):</p>
    <table width="100%" style="margin-left: -5px;">
        <tr>
            <td width="2%">1</td>
            <td width="15%">Nama Sekolah</td>
            <td width="1%">:</td>
            <td width="82%">{{$sekolah->nama}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>NPSN</td>
            <td>:</td>
            <td>{{$sekolah->npsn}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{$sekolah->alamat}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Kecamatan</td>
            <td>:</td>
            <td>{{$sekolah->kecamatan}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Kabupaten/Kota</td>
            <td>:</td>
            <td>{{$sekolah->kabupaten}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Provinsi</td>
            <td>:</td>
            <td>{{$sekolah->provinsi}}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Nama Verifikator</td>
            <td>:</td>
            <td>{{$verifikator->name}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>NIP</td>
            <td>:</td>
            <td>{{$verifikator->nip}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>{{$verifikator->asal_institusi}}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>3</td>
            <td colspan="2">Catatan</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="3" style="vertical-align: top; border:1px solid black;">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </td>
        </tr>
    </table>
    <p></p>
    <table width="100%" style="margin-left: -5px;">
        <tr>
            <td colspan="2" class="text-right" style="padding-bottom: 0px;">
                {{$sekolah->kabupaten}}, {{Helper::TanggalIndo($now)}}
            </td>
        </tr>
        <tr>
            <td width="50%" class="text-center" style="padding-top: 0px;">
                <p>Kepala Sekolah</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>{{$sekolah->nama_kepsek}}<br>
                    NIP. {{$sekolah->nip_kepsek}}</p>
            </td>
            <td width="50%" class="text-center" style="padding-top: 0px;">
                <p>Tim Penjamin Mutu SMK</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>{{$verifikator->name}}<br>
                    NIP. {{$verifikator->nip}}</p>
            </td>
        </tr>
    </table>
</div>
@endsection