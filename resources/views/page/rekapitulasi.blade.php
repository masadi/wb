@extends('layouts.app_rapor')
@section('title', 'Rekapitulasi Rapor Mutu')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Provinsi</label>
                                <select class="form-control select2" id="provinsi_id" style="width: 100%;">
                                    <option value="">Semua Provinsi</option>
                                    @foreach($all_wilayah as $wilayah)
                                    <option value="{{$wilayah->kode_wilayah}}">{{$wilayah->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Kabupaten/Kota</label>
                                <select class="form-control select2" id="kabupaten_id" style="width: 100%;">
                                    <option value="">Semua Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                        <!--div class="col-md-3">
                            <div class="form-group">
                                <label>Filter Kecamatan</label>
                                <select class="form-control select2" id="kecamatan_id" style="width: 100%;">
                                    <option value="">Semua Kecamatan</option>
                                </select>
                            </div>
                        </div-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Sekolah</label>
                                <select class="form-control select2" id="sekolah_id" style="width: 100%;">
                                    <option value="">Semua Sekolah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="rekapitulasi">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">No</th>
                            <th rowspan="2" style="vertical-align: middle;">Wilayah</th>
                            <th colspan="6" class="text-center">Kinerja</th>
                            <th colspan="5" class="text-center">Dampak</th>
                            <th rowspan="2" style="vertical-align: middle;">Nilai Keseluruhan</th>
                            <th rowspan="2" style="vertical-align: middle">Nilai Terendah</th>
                            <th rowspan="2" style="vertical-align: middle;">Nilai Tertinggi</th>
                        </tr>
                        <tr>
                            <th>Input</th>
                            <th>Proses</th>
                            <th>Output</th>
                            <th>Rata-rata</th>
                            <th>Nilai Terendah</th>
                            <th>Nilai Tertinggi</th>
                            <th>Outcome</th>
                            <th>Impact</th>
                            <th>Rata-rata</th>
                            <th>Nilai Terendah</th>
                            <th>Nilai Tertinggi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
var table;
function rekapitulasi(id_level_wilayah, kode_wilayah, sekolah_id){
    table = $('#rekapitulasi').DataTable( {
        retrieve: true,
        processing: true,
        serverSide: true,
        //ajax: '{{ route('api.progres') }}',
        ajax: {
            url: '{{ route('api.rekapitulasi.index') }}',
            data: function(d){
                d.id_level_wilayah = (id_level_wilayah) ? id_level_wilayah : null;
                if(kode_wilayah){
                    d.kode_wilayah = kode_wilayah;
                }
                if(sekolah_id){
                    d.sekolah_id = sekolah_id;
                }
            },
        },
        responsive: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, className: 'text-center' },
            {data: 'nama', name: 'nama'},
            {data: 'npsn', name: 'npsn'},
            {data: 'nama_pendamping', name: 'nama_pendamping'},
            {data: 'nama_verifikator', name: 'nama_verifikator'},
            {data: 'instrumen', name: 'instrumen'},
            {data: 'rapor_mutu', name: 'rapor_mutu'},
            {data: 'pakta_integritas', name: 'pakta_integritas'},
            {data: 'verval', name: 'verval'},
            {data: 'verifikasi', name: 'verifikasi'},
            {data: 'pengesahan', name: 'pengesahan'},
        ],
        language: {
            "decimal":        "",
            "emptyTable":     "Tidak ada data untuk ditampilkan",
            "info":           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty":      "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered":   "(difilter dari _MAX_ total data)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Menampilkan _MENU_ data",
            "loadingRecords": "Loading...",
            "processing":     "Memperoses data...",
            "search":         "Cari:",
            "zeroRecords":    "Tidak ada data yang sesuai dengan pencarian",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Berikutnya",
                "previous":   "Sebelumnya"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
}
rekapitulasi({{$id_level_wilayah}});
</script>
@endsection
@section('js_file')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
@endsection
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
@endsection
