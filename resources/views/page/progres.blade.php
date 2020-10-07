@extends('layouts.app')
@section('title', 'Progres Data Penjaminan Mutu SMK')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-refresh mr-1"></i>
                Progres Data Penjaminan Mutu SMK - {{($nama_wilayah) ? $nama_wilayah->nama : ''}}
            </h3></div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Sekolah</th>
                            <th class="text-center">NPSN</th>
                            <th class="text-center">Pengisian Instrumen</th>
                            <th class="text-center">Hitung Rapor Mutu</th>
                            <th class="text-center">Pakta Integritas</th>
                            <th class="text-center">Verval</th>
                            <th class="text-center">Verifikasi Pusat</th>
                            <th class="text-center">Pengesahan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var table = $('#datatable').DataTable( {
        retrieve: true,
        processing: true,
        serverSide: true,
        //ajax: '{{ route('api.progres') }}',
        ajax: {
            url: '{{ route('api.progres') }}',
            data: function(d){
                d.id_level_wilayah = {{($id_level_wilayah) ? $id_level_wilayah : 'null'}};
                d.kode_wilayah = '{{$kode_wilayah}}';
            },
        },
        responsive: true,
        columns: [
            {data: 'nama', name: 'nama'},
            {data: 'npsn', name: 'npsn'},
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
		/*"retrieve": true,
		"processing": true,
        "serverSide": true,
        "ajax": "{{ route('api.progres') }}",
		"columns": [
            {data: 'nama', name: 'nama'},
            {data: 'npsn', name: 'npsn'},
            {data: 'instrumen', name: 'instrumen'},
            {data: 'rapor_mutu', name: 'rapor_mutu'},
            {data: 'hitung_rapor', name: 'hitung_rapor'},
            {data: 'pakta_integritas', name: 'pakta_integritas'},
            {data: 'verval', name: 'verval'},
            {data: 'verifikasi', name: 'verifikasi'},
            {data: 'pengesahan', name: 'pengesahan'},
        ],*/
    });
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
