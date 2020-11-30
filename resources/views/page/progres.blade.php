@extends('layouts.app_rapor')
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
                <form id="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filter Status Verifikasi</label>
                                <select class="form-control select2" id="verifikasi" style="width: 100%;">
                                    <option value="">== Semua ==</option>
                                    <option value="1">Sudah Verifikasi</option>
                                    <option value="2">Belum Verifikasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filter Tahap Verifikasi</label>
                                <select class="form-control select2" id="tahap" style="width: 100%;">
                                    <option value="">== Semua ==</option>
                                    <option value="1">Tahap 1</option>
                                    <option value="2">Tahap 2</option>
                                    <option value="3">Tahap 3</option>
                                    <option value="4">Tahap 4</option>
                                    <option value="5">Tahap 5</option>
                                </select>
                            </div>
                        </div>
                        <!--div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Sekolah</label>
                                <select class="form-control select2" id="sekolah_id" style="width: 100%;">
                                    <option value="">Semua Sekolah</option>
                                </select>
                            </div>
                        </div-->
                    </div>
                </form>
                <table id="datatable" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama Sekolah</th>
                            <th class="text-center">NPSN</th>
                            <th>Pendamping</th>
                            <th>Verifikator</th>
                            <th class="text-center">Pengisian Instrumen</th>
                            <th class="text-center">Hitung Rapor Mutu</th>
                            <th class="text-center">Kirim Rapor Mutu</th>
                            <th class="text-center">Verval</th>
                            <th class="text-center">Verifikasi Pusat</th>
                            <th class="text-center">Pengesahan</th>
                            @role('admin')
                            <th class="text-center">Edit Tahap</th>
                            @endrole
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
$(document).ready( function () {
    $('.select2').select2();
    var oTable = $('#datatable').DataTable( {
        retrieve: true,
        processing: true,
        serverSide: true,
        //ajax: '{{ route('api.progres') }}',
        ajax: {
            url: '{{ route('api.progres') }}',
            data: function(d){
                var verifikasi = $('#verifikasi').val();
                if(verifikasi){
                    d.status_verifikasi = verifikasi;
                }
                var tahap = $('#tahap').val();
                if(tahap){
                    d.tahap = tahap;
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
            @role('admin')
            {data: 'edit_tahap', name: 'edit_tahap', orderable: false, className: 'text-center'},
            @endrole
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
        },
        fnDrawCallback: function(oSettings){
			turn_on_icheck();
		}
    });
    $('#verifikasi').change(function(e){
		$('#tahap').prop("selectedIndex", 0);
        oTable.draw();
        e.preventDefault();
    });
    $('#tahap').change(function(e){
		oTable.draw();
        e.preventDefault();
    });
});
function turn_on_icheck(){
    $('a.toggle-modal').bind('click',function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var sekolah_sasaran_id = $(this).data('sekolah_sasaran_id')
        $.get(url).done(function(data) {
            console.log(data.output);
            Swal.fire({
                title: 'Pilih Tahap',
                input: 'select',
                inputOptions: data.output,
                inputValue: data.tahap,
                inputPlaceholder: 'Pilih Tahap',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        console.log(value);
                    if (value) {
                        $.ajax({
                            url: '{{route('api.simpan_tahap')}}',
                            type: 'post',
                            data: {
                                sekolah_sasaran_id:sekolah_sasaran_id,
                                tahap: value,
                            },
                            success: function(response){
                                resolve()
                                //oTable.draw();
                            }
                        });
                    } else {
                        resolve('Tahap tidak boleh kosong')
                    }
                    })
                }
            })
        });
    });
}
</script>
@endsection
@section('js_file')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endsection
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection
