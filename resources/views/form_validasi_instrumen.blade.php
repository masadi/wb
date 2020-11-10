<div class="row">
    <div class="col-12">
        @if($instrumens)
        <div class="row">
            <div class="col-12">
                <div class="form-group mt-3">
                    <a href="{{route('cetak_validasi_instrumen')}}" class="btn btn-lg btn-success btn-block">CETAK HASIL VALIDASI</a>
                </div>
                <div class="form-group">
                    <label>Pilih Nomor Instrumen</label>
                    <select class="form-control select2" id="instrumen_id" style="width: 100%;" name="instrumen_id">
                        <option value="">== Pilih Nomor Instrumen == </option>
                        @foreach ($instrumens as $instrumen)
                        <option value="{{$instrumen->instrumen_id}}">{{$loop->iteration}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div id="result"></div>
        @else
        <form id="form" class="form-horizontal">
            <input type="hidden" name="instrumen_id" value="{{$instrumen->instrumen_id}}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="pertanyaan">Rumusan Pertanyaan</label>
                        <textarea name="pertanyaan" id="pertanyaan" class="form-control textarea">{{$instrumen->pertanyaan}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pertanyaan">Petunjuk Pengisian</label>
                        <textarea name="petunjuk_pengisian" id="petunjuk_pengisian" class="form-control textarea">{{$instrumen->petunjuk_pengisian}}</textarea>
                    </div>
                    @foreach ($instrumen->subs as $subs)
                    <div class="form-group">
                        <label for="{{$subs->instrumen_id}}">Pertanyaan ke {{$subs->urut}}</label>
                        <textarea name="pertanyaan_sub[{{$subs->instrumen_id}}]" id="{{$subs->instrumen_id}}" class="form-control textarea">{{$subs->pertanyaan}}</textarea>
                    </div>
                    @endforeach
                    @foreach ($instrumen->telaah_dokumen as $telaah_dokumen)
                    <div class="form-group">
                        <label for="{{$telaah_dokumen->dok_id}}">Telaah Dokumen ke {{$subs->urut}}</label>
                        <textarea name="telaah_dokumen[{{$telaah_dokumen->dok_id}}]" id="{{$subs->dok_id}}" class="form-control textarea">{{$telaah_dokumen->nama}}</textarea>
                    </div>
                    @endforeach
                    <div class="form-group">
                        <button class="btn btn-lg btn-primary float-right" id="simpan">SIMPAN</button>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
<script>
    $('.select2').select2();
$('#instrumen_id').change(function(){
    $('a#cetak').hide();
    var ini = $(this).val();
    if(ini){
        $.ajax({
            url: '{{route('api.get_instrumen')}}',
            type: 'post',
            data: {
                instrumen_id: ini,
            },
            success: function(response){
                $('.tombol-submit').show();
                $('#result').html(response.body);
            }
        });
    } else {
        $('#result').html('');
        $('.tombol-submit').hide();
    }
});
var textArea = $('.textarea');
$.each(textArea, function(i, v){
    v.style.overflow = 'hidden';
    v.style.height = 0;
    v.style.height = v.scrollHeight + 'px';
})
$("form").on("submit", function(event){
    event.preventDefault();
    var formValues= $(this).serialize();
    $.post("{{route('api.validasi_instrumen')}}", formValues, function(data){
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
        }).then((result) => {
            $("#result").html('');
            $('#instrumen_id').val('').trigger("change");
        })
    });
});
</script>