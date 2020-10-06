@extends('layouts.app')
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
                                    <option value="">Nasional</option>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Kecamatan</label>
                                <select class="form-control select2" id="kecamatan_id" style="width: 100%;">
                                    <option value="">Semua Kecamatan</option>
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
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
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
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                {{--dd($komponen[0])--}}
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
	var ini = $(this).val();
	if(ini == ''){
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
	});
});
$.get( "{{route('get_chart')}}", function( data ) {
    tampilChart(data)
});
function tampilChart(data){
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