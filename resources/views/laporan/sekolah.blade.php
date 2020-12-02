<div class="row">
    <div class="col-12">
        @if($sekolah)
        <form id="form" class="form-horizontal">
            <input type="hidden" class="pendamping_id" name="pendamping_id" value="{{$pendamping->pendamping_id}}">
            <h3>Biodata Pendamping</h3>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{$pendamping->nama}}">
            </div>
            <div class="form-group">
                <label for="instansi">Instansi Asal</label>
                <input type="text" name="instansi" id="instansi" class="form-control" value="{{$pendamping->instansi}}">
            </div>
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" value="{{$pendamping->nip}}">
            </div>
            <div class="form-group">
                <label for="nuptk">NUPTK</label>
                <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{$pendamping->nuptk}}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{$pendamping->email}}">
            </div>
            <div class="form-group">
                <label for="nomor_hp">Nomor HP</label>
                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" value="{{$pendamping->nomor_hp}}">
            </div>
            <h3>Formulir Laporan</h3>
            <div class="form-group">
                <label for="sekolah_id">Pilih Sekolah</label>
                <select class="form-control select2" id="sekolah_id" style="width: 100%;" name="sekolah_id">
                    <option value="">== Pilih Sekolah == </option>
                    @foreach ($sekolah as $item)
                    <option value="{{$item->sekolah_id}}">{{$item->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="jenis_laporan_id">Pilih Jenis Laporan</label>
                <select class="form-control select2" id="jenis_laporan_id" style="width: 100%;" name="jenis_laporan_id">
                    <option value="">== Pilih Jenis Laporan == </option>
                    @foreach ($jenis_laporan as $laporan)
                    <option value="{{$laporan->id}}">{{$laporan->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div id="result_sekolah"></div>
            <div class="tombol-submit" style="display: none;">
                <input type="hidden" name="action" value="simpan">
                <a class="btn btn-lg btn-success float-left" id="cetak" href="javacript:void(0)" target="_blank" style="display: none;"><i
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
    $('.tombol-submit').hide();
    $('#result_sekolah').html('');
    $('#jenis_laporan_id').val('').trigger("change");
});
$('#jenis_laporan_id').change(function(){
    $('.tombol-submit').hide();
    var ini = $(this).val();
    if(ini){
        $('.tombol-submit').show();
        $.ajax({
            url: '{{route('api.laporan.sekolah')}}',
            type: 'post',
            data: {
                sekolah_id: $('#sekolah_id').val(),
                jenis_laporan_id: ini,
                pendamping_id: $('.pendamping_id').val(),
            },
            success: function(response){
                $('.tombol-submit').show();
                $('#result_sekolah').html(response.body);
                //if(response.dokumen_verifikasi || response.nilai_dokumen){
                //    $('a#cetak').show();
                //    $('a#cetak').attr('href', '{{url('/cetak-hasil-verifikasi')}}/'+ini)
                //}
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
    $.post("{{route('api.laporan.simpan')}}", formValues, function(data){
        // Display the returned data in browser
        $("#result").html('');
        $('#provinsi_id').val('').trigger("change");
        Swal.fire('Terima Kasih', '', 'success')
    });
});
</script>