<div class="row">
    <div class="col-12">
        @if($sekolah)
        <form id="form" class="form-horizontal">
            <input type="hidden" class="verifikator_id" name="verifikator_id" value="{{$user->user_id}}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Pilih Sekolah</label>
                        <select class="form-control select2" id="sekolah_id" style="width: 100%;" name="sekolah_id">
                            <option value="">== Pilih Sekolah == </option>
                            @foreach ($sekolah as $item)
                            <option value="{{$item->sekolah_id}}">{{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="result_sekolah"></div>
            <div class="tombol-submit" style="display: none;">
                <input type="hidden" name="action" value="simpan">
                <a class="btn btn-lg btn-success float-left" id="cetak" href="javacript:void(0)" target="_blank"><i
                        class="fa fas-print"></i> CETAK</a>
                <button class="btn btn-lg btn-primary float-right" id="simpan"><i class="fa far-save"></i>
                    SIMPAN</button>
            </div>
        </form>
        @else
        <h3 class="text-center mt-3">Token yang dimasukkan tidak ditemukan di database!</h3>
        @endif
    </div>
</div>
<script>
    $('.select2').select2();
$('#sekolah_id').change(function(){
    var ini = $(this).val();
    if(ini){
        $.ajax({
            url: '{{route('api.verifikasi_sekolah')}}',
            type: 'post',
            data: {
                sekolah_id: ini,
                verifikator_id: $('.verifikator_id').val(),
            },
            success: function(response){
                $('.tombol-submit').show();
                $('#result_sekolah').html(response.body);
                $('a#cetak').attr('href', '{{url('/cetak-hasil-verifikasi')}}/'+ini)
            }
        });
    } else {
        $('#result_sekolah').html('');
        $('.tombol-submit').hide();
        $('a#cetak').attr('href', 'javacript:void(0)')
    }
});
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