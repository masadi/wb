@extends('layouts.app')
@section('title', 'Progres Data Penjaminan Mutu SMK')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-refresh mr-1"></i>
                Progres Data Penjaminan Mutu SMK
            </h3></div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover">
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
        processing: true,
        serverSide: true,
        ajax: '{{ route('api.progres') }}',
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
@endsection
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
