<table class="table">
    <tr>
        <td>Petugas Supervisi/Verifikasi</td>
        <td style="width: 1px;">:</td>
        <td><input type="text" class="form-control form-control-sm" name="petugas"></td>
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
        <td><input type="text" class="form-control form-control-sm" name="sektor"></td>
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
            <tr>
                <th style="width: 5%" class="text-center">NO.</th>
                <th style="width: 25%" class="text-center">INDIKATOR/PERTANYAAN</th>
                <th style="width: 20%" class="text-center">VERIFIKASI/TELAAH DOKUMEN</th>
                <th style="width: 10%" class="text-center">ADA</th>
                <th style="width: 10%" class="text-center">TIDAK</th>
                <th style="width: 30%" class="text-center">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instrumens as $item)
            <tr>
                <td rowspan="{{($item->telaah_dokumen_count + 1)}}">
                    {{$loop->iteration}}
                    <input type="hidden" name="instrumen_id[]" value="{{$item->instrumen_id}}">
                </td>
                <td rowspan="{{($item->telaah_dokumen_count + 1)}}">
                    {{$item->pertanyaan}}</td>
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
        </tbody>
    </table>
</div>