@extends('layouts.app')
@section('title', 'Formulir Laporan Penjaminan Mutu SMK CoE')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row justify-content-md-center">
                    <div class="col col-lg-6">
                        <!--input class="form-control form-control-lg" type="text" placeholder=".form-control-lg"-->
                        <div class="input-group">
                            <input type="text" class="form-control token" placeholder="Token" aria-label="Token"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="token">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
                @role('admin')
                <input type="hidden" id="admin" value="1">
                @else
                <input type="hidden" id="admin" value="0">
                @endrole
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="{{asset('vendor/dropzone-5.7.0/dist/min/dropzone.min.css')}}">
@endsection
@section('js_file')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('vendor/dropzone-5.7.0/dist/min/dropzone.min.js')}}"></script>
@endsection
@section('js')
<script>
    $('.select2').select2();
$('#token').click(function(e){
    e.preventDefault();
    var token = $('.token').val();
    if(token){
        $.ajax({
            url: '{{route('api.laporan.validasi_token')}}',
            type: 'post',
            data: {
                token: token,
                admin: $('#admin').val(),
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