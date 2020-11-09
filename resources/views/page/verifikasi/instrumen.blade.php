@extends('layouts.app')
@section('title', 'Verifikasi Isian Instrumen Sekolah')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-md-center">
                    <div class="col col-lg-6">
                        <!--input class="form-control form-control-lg" type="text" placeholder=".form-control-lg"-->
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg token" placeholder="Token" aria-label="Token"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="token">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endsection
@section('js')
<script>
    $('.select2').select2();
$('#provinsi_id').change(function(){
    $('.card-footer').hide();
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
    $('.card-footer').hide();
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
            $('.card-footer').show();
            $('#result').html(response.body);
            $('a#cetak').attr('href', '{{url('/cetak-hasil-verifikasi')}}/'+ini)
        }
    });
});
$('#token').click(function(e){
    e.preventDefault();
    var token = $('.token').val();
    if(token){
        $.ajax({
            url: '{{route('api.validasi_token')}}',
            type: 'post',
            data: {
                token: token,
            },
            success: function(response){
                $('.token').val(response.token)
                $('#result').html(response.body);
            }
        });
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Token tidak boleh kosong',
            icon: 'error',
        })
    }
})
$("form").on("submit", function(event){
    event.preventDefault();
    var formValues= $(this).serialize();
    $.post("{{route('api.verifikasi_sekolah')}}", formValues, function(data){
        // Display the returned data in browser
        $("#result").html('');
        $('#provinsi_id').val('').trigger("change");
        $('#sekolah_id').html('<option value="">Semua Sekolah</option>');
        $('#kabupaten_id').html('<option value="">Semua Kab/Kota</option>');
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
            showDenyButton: true,
            //showCancelButton: true,
            confirmButtonText: 'Cetak Sekarang',
            denyButtonText: 'Cetak Nanti',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Terima Kasih', '', 'success')
                window.open('{{url('/cetak-hasil-verifikasi')}}/'+data.sekolah_id)
            } else if (result.isDenied) {
                Swal.fire('Terima Kasih', '', 'info')
            }
        })
    });
});
</script>
@endsection