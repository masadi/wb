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
<p><strong>Petunjuk Pengisian:</strong>
    <ol>
        <li>Beri tanda (√) pada kolom Ada/Tidak;</li>
        <li>Pada kolom keterangan tulislah kata sesuai jika dokumen pendukung sesuai dengan jawaban di aplikasi atau
            kata tidak sesuai jika dokumen pendukung tidak mendukung jawaban di aplikasi;</li>
    </ol>
</p>
<table class="table">
    <thead>
        <tr>
            <th style="width: 5%">NO.</th>
            <th style="width: 25%">INDIKATOR/PERTANYAAN</th>
            <th style="width: 20%">VERIFIKASI/TELAAH DOKUMEN</th>
            <th style="width: 10%">ADA</th>
            <th style="width: 10%">TIDAK</th>
            <th style="width: 30%">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($instrumens as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->pertanyaan}}</td>
            <td>
                <table>
                    @foreach ($item->telaah_dokumen as $telaah_dokumen)
                    <tr>
                        <td>{{$telaah_dokumen->nama}}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td>
                <table>
                    @foreach ($item->telaah_dokumen as $telaah_dokumen)
                    <tr>
                        <td><input type="radio" name="ada[{{$telaah_dokumen->dok_id}}]" id="{{$telaah_dokumen->dok_id}}"></td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td>
                <table>
                    @foreach ($item->telaah_dokumen as $telaah_dokumen)
                    <tr>
                        <td><input type="radio" name="ada[{{$telaah_dokumen->dok_id}}]" id="{{$telaah_dokumen->dok_id}}"></td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td>
                <table>
                    @foreach ($item->telaah_dokumen as $telaah_dokumen)
                    <tr>
                        <td>
                            <div class="form-group col-12"><input type="text" class="form-control form-control-sm" name="keterangan[{{$telaah_dokumen->dok_id}}]" style="width: 100%"></div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>