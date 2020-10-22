@extends('layouts.app_rapor')
@section('title', 'Rapor Mutu SMK')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="form">
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Kabupaten/Kota</label>
                                <select class="form-control select2" id="kabupaten_id" style="width: 100%;">
                                    <option value="">Semua Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                        <!--div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Kecamatan</label>
                                <select class="form-control select2" id="kecamatan_id" style="width: 100%;">
                                    <option value="">Semua Kecamatan</option>
                                </select>
                            </div>
                        </div-->
                        <div class="col-md-4">
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
                <div id="btn_toggle" class="col-12" style="display: none;">
                    <button class="show_satu button btn btn-warning btn-lg" data-query="nasional">Tampilkan Data Nasional</button>
                    <button class="show_dua button btn btn-danger btn-lg" style="display: none;" data-query="provinsi">Tampilkan Data <span class="nama_provinsi"></span></button>
                </div>
                @auth
                <div class="row">
                    <div class="col-12">
                        <canvas id="scatterChart" width="100%" height="50%" class="mb-3" style="display: none;"></canvas>
                    </div>
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
                    <div class="col-12">
                        <canvas id="scatterChart" width="100%" height="50%" class="mb-3" style="display: none;"></canvas>
                    </div>
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
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
@endsection
@section('js')
<script>
$('.select2').select2();
$('#provinsi_id').change(function(){
    $('#rekap_coe').show();
    $('#rekap_sekolah').hide();
    $('#scatterChart').hide();
    $('#btn_toggle').hide();
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
    $('#scatterChart').hide();
    $('#btn_toggle').hide();
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
            $('#sekolah_id').html('<option value="">Semua Sekolah</option>');
			$.each(response.output.all_sekolah, function (i, item) {
				$('#sekolah_id').append($('<option>', { 
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
    $('#scatterChart').hide();
    $('#btn_toggle').hide();
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
    var params = {
        provinsi_id : $('#provinsi_id').val().trim(),
        sekolah_id: ini,
    }
	getRaporMutu(params);
});
function getRaporMutu(params){
    $.ajax({
		url: '{{route('api.rapor_sekolah')}}',
		type: 'post',
		/*data: {
            provinsi_id : $('#provinsi_id').val().trim(),
            sekolah_id: ini,
        },*/
        data: params,
		success: function(response){
            $( ".show_satu" ).toggle();
            $( ".show_dua" ).toggle();
            $('.nama_provinsi').html(response.nama_wilayah);
            if(response.sekolah){
                $('#rekap_coe').hide();
                $('#rekap_sekolah').show();
                $('#scatterChart').show();
                $('#btn_toggle').show();
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
                $('.avg_kinerja').html(response.rerata_komponen_kinerja);
                $('.avg_dampak').html(response.rerata_komponen_dampak);
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
            } else {
                $('#rekap_sekolah').hide();
                $('#scatterChart').hide();
                $('#rekap_coe').show();
            }
		}
	});
}
$( ".button" ).click(function() {
    var data = $(this).data('query');
    var params;
    if(data === 'nasional'){
        params = {
            all_provinsi: 1,
            provinsi_id : $('#provinsi_id').val().trim(),
            sekolah_id: $('#sekolah_id').val(),
        }
    } else {
        params = {
            all_provinsi: 0,
            provinsi_id : $('#provinsi_id').val().trim(),
            sekolah_id: $('#sekolah_id').val(),
        }
    }
    getRaporMutu(params);
});
$.get( "{{route('get_chart')}}", function( data ) {
    tampilChart(data)
});
var radarChart;
var scatterChart;
function tampilChart(data){
    if(data.counting){
        $.each($('td.rekap'), function(i, val) {
            $(this).html(data.counting[i])
        });
    }
    if(radarChart){
        radarChart.destroy();
    }
    var marksCanvas = document.getElementById("marksChart");
    var marksData = {
        labels: data.nama_komponen,
        datasets: [{
            label: "Tahun 2020",
            backgroundColor: "transparent",
            data: data.nilai_komponen,
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
    radarChart = new Chart(marksCanvas, {
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
        }
    });
    if(data.group_komponen){
        function totalNilai(total, num) {
            return total + num;
        }
        var responseApi = data;
        var dataLainnya = responseApi.all_sekolah;
        var smkLainnya = [];
        $.each(dataLainnya, function (k, v) {
            var set_nilai_kinerja = [];
            $.each(v.nilai_kinerja, function(a,b){
                set_nilai_kinerja.push(parseFloat(b.total_nilai).toFixed(2));
            })
            var set_nilai_dampak = [];
            $.each(v.nilai_dampak, function(c,d){
                set_nilai_dampak.push(parseFloat(d.total_nilai).toFixed(2));
            })
            var total_nilai_kinerja = parseFloat(set_nilai_kinerja.reduce(totalNilai, 0)).toFixed(2);// / set_nilai_kinerja.length;
            var total_nilai_dampak = parseFloat(set_nilai_dampak.reduce(totalNilai, 0)).toFixed(2);// / set_nilai_dampak.length;
            smkLainnya.push({
                x: total_nilai_kinerja - 50,
                y: total_nilai_dampak - 50,
            });
        })
        var color = Chart.helpers.color;
        var scatterChartData = {
            datasets: [{
                label: responseApi.sekolah.nama,
                borderColor: window.chartColors.red,
                backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
                data: [{
                    x: (data.group_komponen.all_kinerja.nilai_scatter > 0) ? data.group_komponen.all_kinerja.nilai_scatter - 50 : 0,
                    y: (data.group_komponen.all_dampak.nilai_scatter > 0) ? data.group_komponen.all_dampak.nilai_scatter - 50 : 0
                }]
            }, {
                label: 'SMK Lainnya',
                borderColor: window.chartColors.blue,
                backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
                data: smkLainnya,
            }]
        };
        if(scatterChart){
            scatterChart.destroy();
        }
        var ctx = document.getElementById('scatterChart').getContext('2d');
        scatterChart = new Chart(ctx, {
            type: 'scatter',
            data: scatterChartData,
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var nilai_kinerja = tooltipItem.xLabel;//Math.round(tooltipItem.yLabel * 100) / 100;
                            var nilai_dampak = tooltipItem.yLabel;//Math.round(tooltipItem.xLabel * 100) / 100;
                            var label_nama_sekolah = responseApi.all_sekolah[tooltipItem.index];
                            if(tooltipItem.index === 0){
                                label = responseApi.sekolah.nama;
                            } else {
                                if(label_nama_sekolah){
                                    label = label_nama_sekolah.nama+' - '+label_nama_sekolah.kabupaten+' - '+label_nama_sekolah.provinsi;
                                } 
                            }
                            if (label) {
                                label += ': ';
                            } 
                            
                            nilai_dampak = (nilai_dampak) ? nilai_dampak + 50 : 0;
                            nilai_kinerja = (nilai_kinerja) ? nilai_kinerja + 50 : 0;
                            label += 'Kinerja ('+nilai_kinerja+') | Dampak ('+nilai_dampak+')';
                            return label;
                        }
                    },
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            min: -50,
                            max: 50,
                            stepSize: 5,
                            callback: v => v == 0 ? 'Dampak Rendah' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'bottom',
                        id: 'x-axis-1',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}, {
                        ticks: {
                            min: -50,
                            max: 50,
                            stepSize: 5,
                            callback: v => v == 0 ? 'Dampak Tinggi' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'top',
						reverse: true,
						id: 'x-axis-2',

						// grid line settings
						gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
                    }],
                    yAxes: [{
                        ticks: {
                            min: -50,
                            max: 50,
                            stepSize: 5,
                            callback: v => v == 0 ? 'Kinerja Rendah' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'left',
                        id: 'y-axis-1',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}, {
                        ticks: {
                            min: -50,
                            max: 50,
                            stepSize: 5,
                            callback: v => v == 0 ? 'Kinerja Tinggi' : ''
                        },
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'right',
						reverse: true,
						id: 'y-axis-2',
                        gridLines: {
                            drawTicks: false,
                            drawOnChartArea:true,
                            color: 'rgba(0, 0, 0, 0)',
                            zeroLineColor: 'rgba(0,0,0,0.50)',
						},
					}],
                },
                title: {
                    display: true,
                    text: 'Pembandingan Kinerja dan Impact',
                },
            }
        });
        if(data.output){
            scatterChart.update();
        }
    }
}
</script>
@endsection