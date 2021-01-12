<table class="table">
    <tr>
        <td colspan="2">Nama Sekolah</td>
        <td colspan="2">: {{$sekolah->nama}}</td>
    </tr>
    <tr>
        <td colspan="2">NPSN</td>
        <td colspan="2">: {{$sekolah->npsn}}</td>
    </tr>
    <tr>
        <td colspan="2">Alamat Sekolah</td>
        <td colspan="2">: {{$sekolah->alamat}}</td>
    </tr>
    <tr>
        <td colspan="2">Kabupaten/Kota</td>
        <td colspan="2">: {{$sekolah->kabupaten}}</td>
    </tr>
    <tr>
        <td colspan="2">Provinsi</td>
        <td colspan="2">: {{$sekolah->provinsi}}</td>
    </tr>
    <tr>
        <td colspan="2">Nama Kepala Sekolah</td>
        <td colspan="2">: {{$sekolah->nama_kepsek}}</td>
    </tr>
    <tr>
        <td colspan="2">Nama Pengawas Pembina</td>
        <td colspan="2">: {{$sekolah->nama_pengawas}}</td>
    </tr>
    <tr>
        <td colspan="2">Jenis/Sektor CoE</td>
        <td colspan="2">:
            {{($sekolah->sekolah_sasaran->sektor) ? $sekolah->sekolah_sasaran->sektor->nama : 'Belum ditentukan'}}</td>
    </tr>
    <tr>
        <th class="text-center">NO.</th>
        <th class="text-center">INDIKATOR/PERTANYAAN</th>
        <th class="text-center">NILAI SEKOLAH</th>
        <th class="text-center">NILAI VERIFIKASI</th>
    </tr>
    @foreach ($instrumens as $item)
    <?php
            if($loop->iteration % 2 == 0){ 
                $class = "#ffffff";  
            } 
            else{ 
                $class = "#ecf0f1"; 
            } 
            ?>
    <?php
            /*
            <tr style="background: #ecf0f1">
                <th style="width: 2%; vertical-align: middle;" class="text-center">NO.</th>
                <th style="width: 78%; vertical-align: middle;" class="text-center" colspan="2">INDIKATOR/PERTANYAAN
                </th>
                <th style="width: 10%" class="text-center">ISIAN INSTRUMEN SEKOLAH</th>
                <th style="width: 10%" class="text-center">ISIAN INSTRUMEN VERIFIKASI</th>
            </tr>
            */
            ?>
    <tr>
        <td class="text-center">
            {{$loop->iteration}}
        </td>
        <td>
            {{$item->pertanyaan}}
        </td>
        <td class="text-center">{{($item->jawaban_sekolah) ? $item->jawaban_sekolah->nilai : '-'}}</td>
        <td>
            {{--dd($item->jawaban)--}}
            <?php 
                    $nilai_jawaban = '-';
                    if($item->jawaban){
                        $nilai_jawaban = $item->jawaban->nilai;
                    }
                    ?>
            {{$nilai_jawaban}}
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
</table>