@extends('layouts.app_rapor')
@section('title', 'Rapor Mutu SMK')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Provinsi</label>
                                <select class="form-control select2" id="provinsi_id" style="width: 100%;">
                                    <option value="">Semua Provinsi</option>
                                    @foreach($all_wilayah as $wilayah)
                                    <option value="{{$wilayah->kode_wilayah}}">{{$wilayah->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Kabupaten/Kota</label>
                                <select class="form-control select2" id="kabupaten_id" style="width: 100%;">
                                    <option value="">Semua Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Kecamatan</label>
                                <select class="form-control select2" id="kecamatan_id" style="width: 100%;">
                                    <option value="">Semua Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Sekolah</label>
                                <select class="form-control select2" id="sekolah_id" style="width: 100%;">
                                    <option value="">Semua Sekolah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div id="rekap_coe" class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle">JML SEKOLAH CoE</th>
                                    <th class="text-center" colspan="2">PENGISIAN INSTRUMEN</th>
                                    <th class="text-center" colspan="2">HITUNG RAPOR MUTU</th>
                                    <th class="text-center" colspan="2">CETAK PAKTA INTEGRITAS</th>
                                    <th class="text-center" colspan="2">VERVAL VERIFIKATOR</th>
                                    <th class="text-center" colspan="2">VERVAL PUSAT</th>
                                    <th class="text-center" colspan="2">PENGESAHAN PUSAT</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                    <th class="text-center">Sudah</th>
                                    <th class="text-center">Belum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                    <td class="text-center rekap">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="rekap_sekolah" class="col-12" style="display: none;">
                        <h2>Rapor Mutu 2020</h2>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <h4 class="nama_sekolah">-</h4>
                                <h4 class="alamat_sekolah">-</h4>
                                <h4><strong>Telp</strong>: <span class="telp">-</span> Fax: <span class="fax">-</span></h4>
                                <h4><strong>Laman</strong>: <span class="laman">-</span></h4>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <h4>&nbsp;</h4>
                                <h4><strong>Kepsek</strong> : <span class="kepsek">-</span></h4>
                                <h4><strong>Program Keahlian</strong> : <span class="proli">-</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jumlah Siswa</td>
                                    <td class="kelas_10">-</td>
                                    <td class="kelas_11">-</td>
                                    <td class="kelas_12">-</td>
                                    <td class="kelas_13">-</td>
                                    <td class="jumlah_siswa">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>PTK</th>
                                    <th>Guru</th>
                                    <th>Tendik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jumlah</td>
                                    <td class="ptk">-</td>
                                    <td class="tendik">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @auth
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-warna-{{strtolower($komponen[0]->nama)}}">
                                <h3 class="card-title">{{$komponen[0]->nama}}</h3>
                                <div class="card-tools">
                                    <span class="avg">{{number_format($komponen[0]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                    <span class="bintang">{!! Helper::bintang_icon(number_format($komponen[0]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen[0]->aspek as $aspek)
                                    <div class="col-lg-4 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($komponen[0]->nama)}} text-center" style="height:125px">
                                            {{$aspek->nama}} <br>
                                            <h1 class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</h1>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <canvas id="marksChart" width="100%" height="100%"></canvas>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-warna-{{strtolower($komponen[1]->nama)}}">
                                <h3 class="card-title">{{$komponen[1]->nama}}</h3>
                                <div class="card-tools">
                                    <span class="avg">{{number_format($komponen[1]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                    <span class="bintang">{!! Helper::bintang_icon(number_format($komponen[1]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen[1]->aspek as $aspek)
                                    <div class="col-lg-4 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($komponen[1]->nama)}} text-center" style="height:125px">
                                            {{$aspek->nama}} <br>
                                            <h1 class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</h1>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-warna-{{strtolower($komponen[2]->nama)}}">
                                <h3 class="card-title">{{$komponen[2]->nama}}</h3>
                                <div class="card-tools">
                                    <span class="avg">{{number_format($komponen[2]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                    <span class="bintang">{!! Helper::bintang_icon(number_format($komponen[2]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen[2]->aspek as $aspek)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($komponen[2]->nama)}} text-center" style="height:125px">
                                            {{$aspek->nama}} <br>
                                            <h1 class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</h1>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-warna-{{strtolower($komponen[3]->nama)}}">
                                <h3 class="card-title">{{$komponen[3]->nama}}</h3>
                                <div class="card-tools">
                                    <span class="avg">{{number_format($komponen[3]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                    <span class="bintang">{!! Helper::bintang_icon(number_format($komponen[3]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen[3]->aspek as $aspek)
                                    <div class="col-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($komponen[3]->nama)}} text-center" style="height:125px">
                                            {{$aspek->nama}} <br>
                                            <h1 class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</h1>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-warna-{{strtolower($komponen[4]->nama)}}">
                                <h3 class="card-title">{{$komponen[4]->nama}}</h3>
                                <div class="card-tools">
                                    <span class="avg">{{number_format($komponen[4]->all_nilai_komponen->avg('total_nilai'),2)}}</span>
                                    <span class="bintang">{!! Helper::bintang_icon(number_format($komponen[4]->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen[4]->aspek as $aspek)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($komponen[4]->nama)}} text-center" style="height:125px">
                                            {{$aspek->nama}} <br>
                                            <h1 class="{{strtolower(Helper::clean($aspek->nama))}}">{{number_format($aspek->all_nilai_aspek->avg('total_nilai'),2)}}</h1>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h3 class="card-title">Kinerja (Performance)</h3>
                                <div class="card-tools">
                                    <?php
                                    $rerata_kinerja = [];
                                    foreach($komponen_kinerja as $kinerja){
                                        $rerata_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
                                    }
                                    ?>
                                    <span class="avg_kinerja">{{number_format(array_sum($rerata_kinerja) / count($rerata_kinerja),2)}}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen_kinerja as $kinerja)
                                    <div class="col-lg-4 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($kinerja->nama)}} text-center" style="height:125px">
                                            {{$kinerja->nama}} <br>
                                            <h1 class="kinerja-{{strtolower(Helper::clean($kinerja->nama))}}">{{number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2)}}</h1>
                                            <span class="bintang-kinerja">{!! Helper::bintang_icon(number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h3 class="card-title">Dampak (Impact)</h3>
                                <div class="card-tools">
                                    <?php
                                    $rerata_dampak = [];
                                    foreach($komponen_dampak as $dampak){
                                        $rerata_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
                                    }
                                    ?>
                                    <span class="avg_dampak">{{number_format(array_sum($rerata_dampak) / count($rerata_dampak),2)}}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($komponen_dampak as $dampak)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="position-relative p-3 mb-3 card-warna-{{strtolower($dampak->nama)}} text-center" style="height:125px">
                                            {{$dampak->nama}} <br>
                                            <h1 class="dampak-{{strtolower(Helper::clean($dampak->nama))}}">{{number_format($dampak->all_nilai_komponen->avg('total_nilai'),2)}}</h1>
                                            <span class="bintang-dampak">{!! Helper::bintang_icon(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2), 'warning') !!}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-4">
                        <canvas id="marksChart" width="100%" height="100%"></canvas>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .card-warna-{{strtolower($komponen[0]->nama)}} {
      background:#d9434e !important;
      color:white;
  }
  .card-warna-{{strtolower($komponen[1]->nama)}} {
      background:#1fac4d !important;
      color:white;
  }
  .card-warna-{{strtolower($komponen[2]->nama)}} {
      background:#48cfc1 !important;
      color:white;
  }
  .card-warna-{{strtolower($komponen[3]->nama)}} {
      background:#9398ec !important;
      color:white;
  }
  .card-warna-{{strtolower($komponen[4]->nama)}} {
      background:#d27b25 !important;
      color:white;
  }
</style> 
@endsection
@section('js_file')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
@endsection
@section('js')
<script>
$('.select2').select2();
$('#provinsi_id').change(function(){
    $('#rekap_coe').show();
    $('#rekap_sekolah').hide();
	var ini = $(this).val();
	if(ini == ''){
        $.get( "{{route('get_chart')}}", function( data ) {
            tampilChart(data)
        });
		return false;
	}
	$.ajax({
		url: '{{route('api.filter_wilayah')}}',
		type: 'post',
		data: {
            id_level_wilayah: 1,
            kode_wilayah: ini.trim(),
        },
		success: function(response){
            $('.avg_kinerja').html(response.output.all_kinerja.rerata);
            $('.avg_dampak').html(response.output.all_dampak.rerata);
            $.each(response.output.all_kinerja.nama, function (i, item) {
                var a = $('h1').hasClass('kinerja-'+item);
                $('h1.kinerja-'+item).html(response.output.all_kinerja.nilai[i]);
            })
            $.each(response.output.all_dampak.nama, function (i, item) {
                var a = $('h1').hasClass('dampak-'+item);
                $('h1.dampak-'+item).html(response.output.all_dampak.nilai[i]);
            })
            $.each($('.bintang-kinerja'), function(i, val) {
                $(this).html(response.output.all_kinerja.bintang[i])
            })
            $.each($('.bintang-dampak'), function(i, val) {
                $(this).html(response.output.all_dampak.bintang[i])
            })
            $.each($('.avg'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].nilai)
            })
            $.each($('.bintang'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].bintang)
            })
            $.each(response.nilai_komponen_kotak, function (i, item) {
                $.each(item.nilai_aspek, function (key, val) {
                    var a = $('h1').hasClass(key);
                    $('h1.'+key).html(val);
                })
            })
            tampilChart(response)
            $('#kabupaten_id').html('<option value="">Semua Kab/Kota</option>');
			$.each(response.output.result, function (i, item) {
				$('#kabupaten_id').append($('<option>', { 
					value: item.value,
					text : item.text
				}));
			});
		}
	});
});
$('#kabupaten_id').change(function(){
    $('#rekap_coe').show();
    $('#rekap_sekolah').hide();
	var ini = $(this).val();
	if(ini == ''){
		return false;
	}
	$.ajax({
		url: '{{route('api.filter_wilayah')}}',
		type: 'post',
		data: {
            id_level_wilayah: 2,
            kode_wilayah: ini.trim(),
        },
		success: function(response){
            $('.avg_kinerja').html(response.output.all_kinerja.rerata);
            $('.avg_dampak').html(response.output.all_dampak.rerata);
            $.each(response.output.all_kinerja.nama, function (i, item) {
                var a = $('h1').hasClass('kinerja-'+item);
                $('h1.kinerja-'+item).html(response.output.all_kinerja.nilai[i]);
            })
            $.each(response.output.all_dampak.nama, function (i, item) {
                var a = $('h1').hasClass('dampak-'+item);
                $('h1.dampak-'+item).html(response.output.all_dampak.nilai[i]);
            })
            $.each($('.bintang-kinerja'), function(i, val) {
                $(this).html(response.output.all_kinerja.bintang[i])
            })
            $.each($('.bintang-dampak'), function(i, val) {
                $(this).html(response.output.all_dampak.bintang[i])
            })
            $.each($('.avg'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].nilai)
            })
            $.each($('.bintang'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].bintang)
            })
            $.each(response.nilai_komponen_kotak, function (i, item) {
                $.each(item.nilai_aspek, function (key, val) {
                    var a = $('h1').hasClass(key);
                    $('h1.'+key).html(val);
                })
            })
            tampilChart(response)
            $('#kecamatan_id').html('<option value="">Semua Kecamatan</option>');
			$.each(response.output.result, function (i, item) {
				$('#kecamatan_id').append($('<option>', { 
					value: item.value,
					text : item.text
				}));
			});
		}
	});
});
$('#kecamatan_id').change(function(){
    $('#rekap_coe').show();
    $('#rekap_sekolah').hide();
	var ini = $(this).val();
	if(ini == ''){
		return false;
	}
	$.ajax({
		url: '{{route('api.filter_wilayah')}}',
		type: 'post',
		data: {
            id_level_wilayah: 3,
            kode_wilayah: ini.trim(),
        },
		success: function(response){
            $('.avg_kinerja').html(response.output.all_kinerja.rerata);
            $('.avg_dampak').html(response.output.all_dampak.rerata);
            $.each(response.output.all_kinerja.nama, function (i, item) {
                var a = $('h1').hasClass('kinerja-'+item);
                $('h1.kinerja-'+item).html(response.output.all_kinerja.nilai[i]);
            })
            $.each(response.output.all_dampak.nama, function (i, item) {
                var a = $('h1').hasClass('dampak-'+item);
                $('h1.dampak-'+item).html(response.output.all_dampak.nilai[i]);
            })
            $.each($('.bintang-kinerja'), function(i, val) {
                $(this).html(response.output.all_kinerja.bintang[i])
            })
            $.each($('.bintang-dampak'), function(i, val) {
                $(this).html(response.output.all_dampak.bintang[i])
            })
            $.each($('.avg'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].nilai)
            })
            $.each($('.bintang'), function(i, val) {
                $(this).html(response.nilai_komponen_kotak[i].bintang)
            })
            $.each(response.nilai_komponen_kotak, function (i, item) {
                $.each(item.nilai_aspek, function (key, val) {
                    var a = $('h1').hasClass(key);
                    $('h1.'+key).html(val);
                })
            })
            $('#sekolah_id').html('<option value="">Semua Sekolah</option>');
            $.each(response.output.all_sekolah, function (i, item) {
                $('#sekolah_id').append($('<option>', { 
                    value: item.value,
                    text : item.text
                }));
            });
            tampilChart(response)
		}
	});
});
$('#sekolah_id').change(function(){
	var ini = $(this).val();
	//if(ini == ''){
		//return false;
	//}
	$.ajax({
		url: '{{route('api.rapor_sekolah')}}',
		type: 'post',
		data: {
            sekolah_id: ini,
        },
		success: function(response){
            $('#rekap_coe').hide();
            if(response.sekolah){
                $('#rekap_sekolah').show();
                $('.nama_sekolah').html(response.sekolah.nama);
                $('.alamat_sekolah').html(response.sekolah.alamat);
                $('.telp').html(response.sekolah.no_telp);
                $('.fax').html(response.sekolah.no_fax);
                $('.laman').html(response.sekolah.website);
                $('.kepsek').html(response.sekolah.nama_kepsek);
                $('.proli').html('');
                $('.kelas_10').html(response.sekolah.kelas_10_count);
                $('.kelas_11').html(response.sekolah.kelas_11_count);
                $('.kelas_12').html(response.sekolah.kelas_12_count);
                $('.kelas_13').html(response.sekolah.kelas_13_count);
                $('.jumlah_siswa').html(response.sekolah.anggota_rombel_count);
                $('.ptk').html(response.sekolah.guru_count);
                $('.tendik').html(response.sekolah.tendik_count);
                $('.avg_kinerja').html(response.group_komponen.all_kinerja.rerata);
                $('.avg_dampak').html(response.group_komponen.all_dampak.rerata);
                $.each(response.group_komponen.all_kinerja.nama, function (i, item) {
                    var a = $('h1').hasClass('kinerja-'+item);
                    $('h1.kinerja-'+item).html(response.group_komponen.all_kinerja.nilai[i]);
                })
                $.each(response.group_komponen.all_dampak.nama, function (i, item) {
                    var a = $('h1').hasClass('dampak-'+item);
                    $('h1.dampak-'+item).html(response.group_komponen.all_dampak.nilai[i]);
                })
                $.each($('.bintang-kinerja'), function(i, val) {
                    $(this).html(response.group_komponen.all_kinerja.bintang[i])
                })
                $.each($('.bintang-dampak'), function(i, val) {
                    $(this).html(response.group_komponen.all_dampak.bintang[i])
                })
                $.each($('.avg'), function(i, val) {
                    $(this).html(response.nilai_komponen_kotak[i].nilai)
                })
                $.each($('.bintang'), function(i, val) {
                    $(this).html(response.nilai_komponen_kotak[i].bintang)
                })
                $.each(response.nilai_komponen_kotak, function (i, item) {
                    $.each(item.nilai_aspek, function (key, val) {
                        var a = $('h1').hasClass(key);
                        $('h1.'+key).html(val);
                    })
                })
                tampilChart(response)
            }
		}
	});
});
$.get( "{{route('get_chart')}}", function( data ) {
    tampilChart(data)
});
function tampilChart(data){
    console.log(data.counting);
    if(data.counting){
        $.each($('td.rekap'), function(i, val) {
            $(this).html(data.counting[i])
        });
    }
    var marksCanvas = document.getElementById("marksChart");
    var marksData = {
        labels: data.nama_komponen,
        //labels: ["English", "Maths", "Physics", "Chemistry", "Biology", "History"],
        datasets: [{
            label: "Tahun 2020",
            backgroundColor: "transparent",
            data: data.nilai_komponen,
            //data: [65, 75, 70, 80, 60, 80],
            borderColor: "rgba(200,0,0,0.6)",
            fill: false,
            radius: 6,
            pointRadius: 6,
            pointBorderWidth: 3,
            pointBackgroundColor: "orange",
            pointBorderColor: "rgba(200,0,0,0.6)",
            pointHoverRadius: 10,
            }]
    };

    var radarChart = new Chart(marksCanvas, {
        type: 'radar',
        data: marksData,
        options: {
            legend: {
                position: 'top',
            },
            title: {
                display: false,
                text: 'Chart.js Outcome Graph'
            },
            scale: {
                ticks: {
                    beginAtZero: true,
                    min: 0,
                    max: 100,
                }
            },
            tooltips:{
                enabled:true,
            },
            /*tooltips:{
                enabled:true,
                callbacks:{
                    label: function(tooltipItem, data){
                        var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                        //This will be the tooltip.body
                        return datasetLabel + ': ' + tooltipItem.yLabel;
                    }
                },
            }*/
        }
    });
    if(data.output){
        radarChart.reset();
        radarChart.labels = data.nama_komponen;
        radarChart.data.datasets[0].data = data.nilai_komponen;
        radarChart.update();
    }
}
</script>
@endsection