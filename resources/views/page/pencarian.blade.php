@extends('layouts.app')
@section('title', 'Pencarian Sekolah')
@section('content')
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control npsn" placeholder="NPSN" aria-label="NPSN" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary pencarian" type="button"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center info_1">Silahkan masukkan NPSN di form sebelah kiri</h3>
                <h3 class="text-center info_2" style="display: none;">Hasil pencarian NPSN : <span class="info_3"></span></h3>
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script>
    $('.pencarian').click(function(e){
        e.preventDefault();
        var npsn = $('.npsn').val()
        $.post('{{route('pencarian')}}', {npsn:npsn}).then(function(response){
            $('.info_1').hide();
            $('.info_2').show();
            $('.info_3').html('<b>'+npsn+'</b>');
            $('#result').html(response.body)
            console.log(response);
        }).fail(function(error){
            var message = [];
            $.each(error.responseJSON.errors, function(i, v){
                message.push(v[0]);
            });
            Swal.fire({
                icon: 'error',
                text: message.join('<br>'),
            })
        })
    })
</script>
@endsection