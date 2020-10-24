<table class="table">
    <tr>
        <td>Nama Sekolah</td>
        <td>: {{$sekolah->nama}}</td>
    </tr>
    <tr>
        <td>NPSN</td>
        <td>: {{$sekolah->npsn}}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: {{$sekolah->alamat}}</td>
    </tr>
    <tr>
        <td>Kepala Sekolah</td>
        <td>: {{$sekolah->nama_kepsek}}</td>
    </tr>
</table>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="rekapitulasi-tab" data-toggle="tab" href="#rekapitulasi" role="tab" aria-controls="rekapitulasi" aria-selected="true">Rekapitulasi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="rapor-mutu-tab" data-toggle="tab" href="#rapor-mutu" role="tab" aria-controls="rapor-mutu" aria-selected="false">Rapor Mutu</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="rekapitulasi" role="tabpanel" aria-labelledby="rekapitulasi-tab">
        <table class="table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah Siswa</td>
                    <td class="kelas_10">-</td>
                    <td class="kelas_11">-</td>
                    <td class="kelas_12">-</td>
                    <td class="kelas_13">-</td>
                    <td class="jumlah_siswa">-</td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>PTK</th>
                    <th>Guru</th>
                    <th>Tendik</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah</td>
                    <td class="ptk">-</td>
                    <td class="tendik">-</td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>Kompetensi Keahlian</th>
                    <th>Jumlah PD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="rapor-mutu" role="tabpanel" aria-labelledby="rapor-mutu-tab">
        @permission('users-create', 'repot-read')
        <p class="text-center mt-3">Sedang dalam pengembangan</p>
        @else
        <p class="text-center mt-3">Sedang dalam pengembangan</p>
        @endpermission
    </div>
  </div>
{{--$sekolah--}}
<script>
    //$('p.mt-3').html('text')
</script>