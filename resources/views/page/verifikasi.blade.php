@extends('layouts.app')
@section('title', 'Verifikasi Isian Instrumen Sekolah')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="form" class="form-horizontal">
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
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('js_file')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('js')
<script>
$('.select2').select2();
$('#provinsi_id').change(function(){
    var ini = $(this).val();
	if(ini == ''){
        $('#kabupaten_id').html('<option value="">Semua Kab/Kota</option>');
        $('#sekolah_id').html('<option value="">Semua Sekolah</option>');
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
$('#sekolah_id').change(function(){
    var ini = $(this).val();
    $.ajax({
		url: '{{route('api.verifikasi_sekolah')}}',
		type: 'post',
		data: {
            sekolah_id: ini,
        },
		success: function(response){
            $('#result').html(response.body)
        }
    });
});
</script>
@endsection