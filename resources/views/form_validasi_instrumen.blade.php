<div class="row">
    <div class="col-12">
        @if($instrumens)
        <div class="row">
            <div class="col-12">
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
        <div class="tombol-submit" style="display: none;">
            <button class="btn btn-lg btn-primary float-right" id="simpan"><i class="fa far-save"></i>
                SIMPAN</button>
        </div>
        @else
        <form id="form" class="form-horizontal">
            <input type="hidden" name="instrumen_id" value="{{$instrumen->instrumen_id}}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="pertanyaan">Rumusan Pertanyaan</label>
                        <textarea name="pertanyaan" id="pertanyaan" class="form-control">{{$instrumen->pertanyaan}}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="pertanyaan">Petunjuk Pengisian</label>
                        <textarea name="petunjuk_pengisian" id="petunjuk_pengisian" class="form-control">{{$instrumen->petunjuk_pengisian}}</textarea>
                    </div>
                </div>
            </div>
            {{$instrumen}}
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
$("form").on("submit", function(event){
    event.preventDefault();
    var formValues= $(this).serialize();
    $.post("{{route('api.validasi_instrumen')}}", formValues, function(data){
        console.log(data);
        return false;
        // Display the returned data in browser
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