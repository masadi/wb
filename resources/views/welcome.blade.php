@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="apmIndikator" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#apmIndikator" data-slide-to="0" class="active"></li>
                        <li data-target="#apmIndikator" data-slide-to="1"></li>
                        <li data-target="#apmIndikator" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-1.png')}}"
                                alt="Alur Aplikasi Penjaminan Mutu">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-2.png')}}"
                                alt="Alur Aplikasi Akses Sekolah">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-3.png')}}"
                                alt="Alur Aplikasi Akses Tim Penjamin Mutu">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-4.png')}}"
                                alt="Alur Aplikasi Akses Direktorat">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{asset('vendor/img/apm-5.jpg')}}"
                                alt="Domain Aplikasi Penjaminan Mutu">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#apmIndikator" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#apmIndikator" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">INFORMASI PENTING</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h2>Jadwal Pengisian APM SMK CoE</h2>
            <ol style="padding-left: 20px;">
                <li>Tanggal 1-3 Oktober 2020: Simulasi pengisian APM SMK, sekolah melakukan seluruh tahapan pengisian pada APM SMK:
                    <ol type="a" style="padding-left: 15px;">
                        <li>Pengisian capaian kinerja seluruh instrumen/kuesioner (58), diupayakan capaian kinerja yang sesungguhnya atau target yang akan dicapai sekolah</li>
                        <li>Melakukan hitung Rapor Mutu Sekolah</li>
                        <li>Melakukan cetak Pakta Integritas</li>
                        <li>Melakukan Kirim Rapor Mutu Sekolah
                            <ul style="padding-left: 15px;">
                                <li>Simulasi pengisian APM SMK ditutup pada tanggal 03 Oktober 2020 Pukul 24.00 WIB</li>
                                <li>Tanggal 15 Oktober 2020 APM SMK akan dilakukan reset kembali seluruh data isian (simulasi) SMK CoE</li>
                            </ul>
                        </li>
                    </ol>
                </li>
                <li>Tanggal 16-26 Oktober 2020: Pengisian Final APM SMK, Sekolah melakukan pengisian kembali capaian kinerja seluruh instrumen/kuesioner (58), dan pada range waktu ini adalah pengisian capaian kinerja final SMK CoE
                    <ol type="a" style="padding-left: 15px;">
                        <li>Pengisian instrumen final</li>
                        <li>Melakukan hitung Rapor Mutu Sekolah</li>
                        <li>Melakukan cetak Pakta Integritas</li>
                        <li>Melakukan Kirim Rapor Mutu Sekolah
                            <ul style="padding-left: 15px;">
                                <li>Pada tahap ini, ketika sekolah sudah kirim Rapor Mutu Sekolah maka tidak dapat dilakukan reset atau perbaikan kembali</li>
                            </ul>
                        </li>
                    </ol>
                </li>
                <li>Tanggal 26 Oktober 2020: Cut Off pengisian APM SMK, pengiriman terkahir Rapor Mutu Sekolah pada tanggal 26 Oktober 2020 Pukul 24.00 WIB</li>
                <li>Tanggal 27-31 Oktober 2020: Pencetakan Rapor Mutu Sekolah oleh Verifikator dan Dit.SMK</li>
            </ol>
            <h3>Helpdesk APM SMK (CoE)</h3>
            <ol style="padding-left: 20px;">
                <li>Deni Warsa Setiawan : 0818 624 330</li>
                <li>Ahmad Aripin : 0812 2999 7730</li>
                <li>Wahyudin : 0815 6441 864</li>
            </ol>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
<script>
    $(window).on('load',function(){
        $('#modalInfo').modal('show');
    });
</script>
@endsection