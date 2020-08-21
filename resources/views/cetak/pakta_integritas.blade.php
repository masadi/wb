@extends('layouts.cetak')
@section('content')
<div class="container-fluid">
    <h3>PAKTA INTEGRITAS SEKOLAH</h3>
    <span class="text-muted"><i class="fas fa-clock"></i> {{$now}}</span>
    <p>Dengan ini Saya sebagai Kepala Sekolah {{$sekolah->nama}} menyatakan bahwa data yang diisi pada kuesioner Penjaminan Mutu SMK tahun pendataan {{$tahun_pendataan}} telah diperiksa kebenarannya dan telah sesuai dengan fakta yang ada di lapangan.</p>
    <p>Saya sepenuhnya siap bertanggung jawab apabila di kemudian hari ditemukan ketidaksesuaian antara data yang diisi di kuesioner Penjaminan Mutu SMK dengan fakta yang ada di lapangan, dan Saya siap menerima sanksi moral, sanksi administrasi, dan sanksi hukum sesuai dengan peraturan dan perundang-undangan yang berlaku.</p>
    
    <p>Penanggungjawab</p>
    <p>Kepala Sekolah {{$sekolah->nama}}</p>
</div>
@endsection