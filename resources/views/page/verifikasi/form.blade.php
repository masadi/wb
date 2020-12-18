<table class="table">
    <tr>
        <td style="width: 20%">Petugas Supervisi/Verifikasi</td>
        <td style="width: 1px;">:</td>
        <td>{{$sekolah->sekolah_sasaran->verifikator->name}}</td>
    </tr>
    <tr>
        <td>Nama Sekolah</td>
        <td>:</td>
        <td>{{$sekolah->nama}}</td>
    </tr>
    <tr>
        <td>NPSN</td>
        <td>:</td>
        <td>{{$sekolah->npsn}}</td>
    </tr>
    <tr>
        <td>Alamat Sekolah</td>
        <td>:</td>
        <td>{{$sekolah->alamat}}</td>
    </tr>
    <tr>
        <td>Kabupaten/Kota</td>
        <td>:</td>
        <td>{{$sekolah->kabupaten}}</td>
    </tr>
    <tr>
        <td>Provinsi</td>
        <td>:</td>
        <td>{{$sekolah->provinsi}}</td>
    </tr>
    <tr>
        <td>Nama Kepala Sekolah</td>
        <td>:</td>
        <td>{{$sekolah->nama_kepsek}}</td>
    </tr>
    <tr>
        <td>Nama Pengawas Pembina</td>
        <td>:</td>
        <td>{{$sekolah->nama_pengawas}}</td>
    </tr>
    <tr>
        <td>Jenis/Sektor CoE</td>
        <td>:</td>
        <td>{{($sekolah->sekolah_sasaran->sektor) ? $sekolah->sekolah_sasaran->sektor->nama : 'Belum ditentukan'}}</td>
    </tr>
</table>
<input type="hidden" name="sekolah_sasaran_id" value="{{$sekolah->sekolah_sasaran->sekolah_sasaran_id}}">
<p><strong>Petunjuk Pengisian:</strong>
    <ol>
        <li>Beri tanda (√) pada kolom Ada/Tidak;</li>
        <li>Pada kolom keterangan tulislah kata sesuai jika dokumen pendukung sesuai dengan jawaban di aplikasi atau
            kata tidak sesuai jika dokumen pendukung tidak mendukung jawaban di aplikasi;</li>
    </ol>
</p>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <?php
            /*
            <tr>
                <th style="width: 2%" class="text-center">NO.</th>
                <th style="width: 38%" class="text-center">INDIKATOR/PERTANYAAN</th>
                <th style="width: 20%" class="text-center">VERIFIKASI/TELAAH DOKUMEN</th>
                <th style="width: 5%" class="text-center">ADA</th>
                <th style="width: 5%" class="text-center">TIDAK</th>
                <th style="width: 30%" class="text-center">KETERANGAN</th>
            </tr>
            */
            ?>

        </thead>
        <tbody>
            @foreach ($instrumens as $item)
            <?php
            if($loop->iteration % 2 == 0){ 
                $class = "#ffffff";  
            } 
            else{ 
                $class = "#ecf0f1"; 
            } 
            ?>
            <tr style="background: #ecf0f1">
                <th style="width: 2%; vertical-align: middle;" class="text-center">NO.</th>
                <th style="width: 78%; vertical-align: middle;" class="text-center" colspan="2">INDIKATOR/PERTANYAAN
                </th>
                <th style="width: 10%" class="text-center">ISIAN INSTRUMEN SEKOLAH</th>
                <th style="width: 10%" class="text-center">ISIAN INSTRUMEN VERIFIKASI</th>
            </tr>
            <tr>
                <td class="text-center" rowspan="{{($item->telaah_dokumen_count + 3)}}">
                    {{$loop->iteration}}
                    <input type="hidden" name="instrumen_id[]" value="{{$item->instrumen_id}}">
                    <input type="hidden" name="komponen_id[{{$item->instrumen_id}}]"
                        value="{{$item->indikator->atribut->aspek->komponen_id}}">
                    <input type="hidden" name="aspek_id[{{$item->instrumen_id}}]"
                        value="{{$item->indikator->atribut->aspek_id}}">
                    <input type="hidden" name="atribut_id[{{$item->instrumen_id}}]"
                        value="{{$item->indikator->atribut_id}}">
                    <input type="hidden" name="indikator_id[{{$item->instrumen_id}}]" value="{{$item->indikator_id}}">
                </td>
                <td colspan="2">
                    {{$item->pertanyaan}}
                </td>
                <td class="text-center">{{($item->jawaban_sekolah) ? $item->jawaban_sekolah->nilai : '-'}}</td>
                <td>
                    {{--dd($item->jawaban)--}}
                    <?php 
                    $nilai_jawaban = 0;
                    if($item->jawaban){
                        $nilai_jawaban = $item->jawaban->nilai;
                    } else {
                        if($item->jawaban_sekolah){
                            $nilai_jawaban = $item->jawaban_sekolah->nilai;
                        }
                    }
                    ?>
                    <input type="text" class="form-control form-control-sm verifikasi-{{$item->instrumen_id}}"
                        style="width: 100%" value="{{$nilai_jawaban}}" readonly>
                    <input type="hidden" class="form-control form-control-sm" name="verifikasi[{{$item->instrumen_id}}]"
                        style="width: 100%" value="{{$nilai_jawaban}}">
                    <?php
                    /*
                    <select name="verifikasi[{{$item->instrumen_id}}]" class="form-control form-control-sm">
                        @foreach ($item->subs as $sub)
                        <option value="{{$sub->urut}}"
                            {{($item->jawaban) ? ($item->jawaban->nilai == $sub->urut) ? 'selected' : '' : ''}}>
                            {{$sub->urut}}</option>
                        @endforeach
                    </select>
                    */
                    ?>
                </td>
            </tr>
            <tr>
                <th colspan="4">TELAAH DOKUMEN</th>
            </tr>
            <tr>
                <th class="text-center">VERIFIKASI/TELAAH DOKUMEN</th>
                <th class="text-center">ADA</th>
                <th class="text-center">TIDAK</th>
                <th class="text-center">KETERANGAN</th>
            </tr>
            @foreach ($item->telaah_dokumen as $telaah_dokumen)
            <tr>
                <td>
                    {{trim($telaah_dokumen->nama)}}
                </td>
                @if($dokumen_verifikasi)
                @isset($dokumen_verifikasi->ada->{$item->instrumen_id}->{$telaah_dokumen->dok_id})
                <td class="text-center">
                    <input type="radio" class="{{$item->instrumen_id}} hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="0"
                        {{($dokumen_verifikasi->ada->{$item->instrumen_id}->{$telaah_dokumen->dok_id})  ? '' : 'checked'}} required>
                </td>
                <td class="text-center">
                    <input type="radio" class="hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="1"
                        {{($dokumen_verifikasi->ada->{$item->instrumen_id}->{$telaah_dokumen->dok_id})  ? 'checked' : ''}}>
                </td>
                <td>
                    <input type="text" class="form-control keterangan-{{$telaah_dokumen->dok_id}}"
                        name="keterangan[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]" style="width: 100%"
                        value="{{ $dokumen_verifikasi->keterangan->{$item->instrumen_id}->{$telaah_dokumen->dok_id} }}">
                </td>
                @else
                <td class="text-center">
                    <input type="radio" class="{{$item->instrumen_id}} hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="0"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 0) ? 'checked' : '' : ''}} required>
                </td>
                <td class="text-center"><input type="radio" class="hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="1"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 1) ? 'checked' : '' : ''}}>
                </td>
                <td>
                    <input type="text" class="form-control keterangan-{{$telaah_dokumen->dok_id}}"
                        name="keterangan[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"  style="width: 100%"
                        value="{{($telaah_dokumen->nilai_dokumen) ? $telaah_dokumen->nilai_dokumen->keterangan : ''}}">
                </td>
                @endisset
                @else
                <td class="text-center">
                    <input type="radio" class="{{$item->instrumen_id}} hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="0"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 0) ? 'checked' : '' : ''}} required>
                </td>
                <td class="text-center"><input type="radio" class="hitung-{{$item->instrumen_id}}"
                        name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        data-instrumen_id="{{$item->instrumen_id}}" id="{{$telaah_dokumen->dok_id}}" data-dok_id="{{$telaah_dokumen->dok_id}}" value="1"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 1) ? 'checked' : '' : ''}}>
                </td>
                <td>
                    <input type="text" class="form-control keterangan-{{$telaah_dokumen->dok_id}}"
                        name="keterangan[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"  style="width: 100%"
                        value="{{($telaah_dokumen->nilai_dokumen) ? $telaah_dokumen->nilai_dokumen->keterangan : ''}}">
                </td>
                @endif
            </tr>
            @endforeach
            @endforeach
            <?php
            /*
            @foreach ($instrumens as $item)
            <tr>
                <td rowspan="{{($item->telaah_dokumen_count + 1)}}" class="text-center">
                    {{$loop->iteration}}
                    <input type="hidden" name="instrumen_id[]" value="{{$item->instrumen_id}}">
                </td>
                <td rowspan="{{($item->telaah_dokumen_count + 1)}}">
                    {{$item->pertanyaan}}
                </td>
            </tr>
            @foreach ($item->telaah_dokumen as $telaah_dokumen)
            <tr>
                <td>
                    {{trim($telaah_dokumen->nama)}}
                </td>
                <td class="text-center"><input type="radio" name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        id="{{$telaah_dokumen->dok_id}}" value="0"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 0) ? 'checked' : '' : 'checked'}}>
                </td>
                <td class="text-center"><input type="radio" name="ada[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]"
                        id="{{$telaah_dokumen->dok_id}}" value="1"
                        {{($telaah_dokumen->nilai_dokumen) ? ($telaah_dokumen->nilai_dokumen->ada == 1) ? 'checked' : '' : ''}}>
                </td>
                <td><input type="text" class="form-control"
                        name="keterangan[{{$item->instrumen_id}}][{{$telaah_dokumen->dok_id}}]" style="width: 100%"
                        value="{{($telaah_dokumen->nilai_dokumen) ? $telaah_dokumen->nilai_dokumen->keterangan : ''}}">
                </td>
            </tr>
            @endforeach
            @endforeach
            */
            ?>
        </tbody>
    </table>
</div>
<script>
$( "input[type='radio']" ).change(function() {
    var instrumen_id = $(this).data('instrumen_id');
    var dok_id = $(this).data('dok_id');
    var ada = $("."+instrumen_id+":checked").length;
    var hitung = $(".hitung-"+instrumen_id+":checked").length;
    var ini = $(this).val();
    ini = parseInt(ini)
    if(ini === 1){
        $('.keterangan-'+dok_id).val('Tidak sesuai');
    } else {
        $('.keterangan-'+dok_id).val('Sesuai');
    }
    console.log(ini);
    $.ajax({
		url: '{{route('api.hitung_dokumen')}}',
		type: 'post',
		data: {
            hitung: hitung,
            ada: ada,
        },
		success: function(response){
            console.log(response);
            $('input[name="verifikasi['+instrumen_id+']"]').val(response.jawaban);
            $('.verifikasi-'+instrumen_id).val(response.jawaban);
        }
    });
});
</script>