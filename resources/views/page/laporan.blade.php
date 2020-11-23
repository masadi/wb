@extends('layouts.app_rapor')
@section('title', 'Laporan Proses Penjamian Mutu SMK')
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
                <table class="table table-bordered" id="rekapitulasi" style="width: 100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;" class="text-center">No</th>
                            <th rowspan="2" style="vertical-align: middle;">Nama Sekolah</th>
                            <th rowspan="2" style="vertical-align: middle;" class="text-center">NPSN</th>
                            <th colspan="7" class="text-center">Kinerja</th>
                            <th colspan="3" class="text-center">Dampak</th>
                            <th rowspan="2" style="vertical-align: middle;">Nilai Rapor Mutu</th>
                            <th rowspan="2" style="vertical-align: middle;">Predikat</th>
                        </tr>
                        <tr>
                            <th>Input</th>
                            <th>Proses</th>
                            <th>Output</th>
                            <th>Rerata</th>
                            <th>Nilai Terendah</th>
                            <th>Nilai Tertinggi</th>
                            <th>Afirmasi</th>
                            <th>Outcome</th>
                            <th>Impact</th>
                            <th>Rerata</th>
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
            { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
            {data: 'nama', name: 'nama', orderable: false},
            {data: 'npsn', name: 'npsn', orderable: false, className: 'text-center'},
            {data: 'nilai_input', name: 'nilai_input', orderable: false, className: 'text-center'},
            {data: 'nilai_proses', name: 'nilai_proses', orderable: false, className: 'text-center'},
            {data: 'nilai_output', name: 'nilai_output', orderable: false, className: 'text-center'},
            {data: 'nilai_kinerja', name: 'nilai_kinerja', orderable: false, className: 'text-center'},
            {data: 'terendah', name: 'terendah', orderable: false, className: 'text-center'},
            {data: 'tertinggi', name: 'tertinggi', orderable: false, className: 'text-center'},
            {data: 'afirmasi', name: 'afirmasi', orderable: false, className: 'text-center'},
            {data: 'nilai_outcome', name: 'nilai_outcome', orderable: false, className: 'text-center'},
            {data: 'nilai_impact', name: 'nilai_impact', orderable: false, className: 'text-center'},
            {data: 'nilai_dampak', name: 'nilai_dampak', orderable: false, className: 'text-center'},
            {data: 'nilai_akhir', name: 'nilai_akhir', orderable: false, className: 'text-center'},
            {data: 'predikat', name: 'predikat', orderable: false, className: 'text-center'},
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
$('.select2').select2();
rekapitulasi({{$id_level_wilayah}});
</script>
@endsection
@section('js_file')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection
