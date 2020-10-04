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
                <table id="datatable_test" class="table table-bordered table-striped table-hover table-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Wilayah</th>
                            <th class="text-center" colspan="2">Jml SMK</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Pengisian Instrumen</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Hitung Rapor Mutu</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Pakta Integritas</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Verval</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Verifikasi Pusat</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Pengesahan</th>
                        </tr>
                        <tr>
                            <th class="text-center">Nasional</th>
                            <th class="text-center">CoE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $jml_sekolah_nasional = 0;
                        $jml_sekolah_coe = 0;
                        $jml_instrumen = 0;
                        $jml_pakta_integritas = 0;
                        $jml_rapor_mutu = 0;
                        $jml_waiting = 0;
                        $jml_proses = 0;
                        $jml_terima = 0;
                        $total_instrumen = 0;
                        $total_rapor_mutu = 0;
                        $total_pakta_integritas = 0;
                        $total_waiting = 0;
                        $total_proses = 0;
                        $total_terima = 0;
                        ?>
                        @forelse ($all_wilayah as $item)
                        <?php
                        //dd($item);
                        /*$count_instrumen = $item->{$with}->map(function($data){
                            return $data->nilai_instrumen_count;
                        })->toArray();
                        $nilai1_instrumen = count(array_filter($count_instrumen));
                        $nilai2_instrumen = count($count_instrumen);
                        $persen_instrumen = ($nilai2_instrumen) ? $nilai1_instrumen / $nilai2_instrumen * 100 : 0;
                        $count_nilai_akhir = $item->{$with}->map(function($data){
                            if(isset($data->user->nilai_akhir)){
                                $return = 1;
                            } else {
                                $return = 0;
                            }
                            return $return;
                        })->toArray();
                        $nilai1_nilai_akhir = count(array_filter($count_nilai_akhir));
                        $nilai2_nilai_akhir = count($count_nilai_akhir);
                        $persen_nilai_akhir = ($nilai2_nilai_akhir) ? $nilai1_nilai_akhir / $nilai2_nilai_akhir * 100 : 0;
                        $count_pakta_integritas = $item->{$with}->map(function($data){
                            return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->pakta_integritas : NULL;
                        })->toArray();
                        $nilai1_pakta_integritas = count(array_filter($count_pakta_integritas));
                        $nilai2_pakta_integritas = count($count_pakta_integritas);
                        $persen_pakta_integritas = ($nilai2_pakta_integritas) ? $nilai1_pakta_integritas / $nilai2_pakta_integritas * 100 : 0;
                        $count_waiting = $item->{$with}->map(function($data){
                            return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->waiting : NULL;
                        })->toArray();
                        $nilai1_waiting = count(array_filter($count_waiting));
                        $nilai2_waiting = count($count_waiting);
                        $persen_waiting = ($nilai2_waiting) ? $nilai1_waiting / $nilai2_waiting * 100 : 0;
                        $count_proses = $item->{$with}->map(function($data){
                            return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->proses : NULL;
                        })->toArray();
                        $nilai1_proses = count(array_filter($count_proses));
                        $nilai2_proses = count($count_proses);
                        $persen_proses = ($nilai2_proses) ? $nilai1_proses / $nilai2_proses * 100 : 0;
                        $count_terima = $item->{$with}->map(function($data){
                            return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->terima : NULL;
                        })->toArray();
                        $nilai1_terima = count(array_filter($count_terima));
                        $nilai2_terima = count($count_terima);
                        $persen_terima = ($nilai2_terima) ? $nilai1_terima / $nilai2_terima * 100 : 0;*/
                        $jml_sekolah_nasional += $item->$count_smk;
                        $jml_sekolah_coe += $item->$count_smk_coe;
                        $jml_instrumen += $item->$count_instrumen;
                        $jml_pakta_integritas += $item->$count_pakta_integritas;
                        $jml_rapor_mutu += $item->$count_rapor_mutu;
                        $jml_waiting += $item->$count_waiting;
                        $jml_proses += $item->$count_proses;
                        $jml_terima += $item->$count_terima;
                        $persen_instrumen = ($item->$count_instrumen) ? $item->$count_instrumen / $item->$count_smk_coe * 100 : 0;
                        $persen_pakta_integritas = ($item->$count_pakta_integritas) ? $item->$count_pakta_integritas / $item->$count_smk_coe * 100 : 0;
                        $persen_rapor_mutu = ($item->$count_rapor_mutu) ? $item->$count_rapor_mutu / $item->$count_smk_coe * 100 : 0;
                        $persen_waiting = ($item->$count_waiting) ? $item->$count_waiting / $item->$count_smk_coe * 100 : 0;
                        $persen_proses = ($item->$count_proses) ? $item->$count_proses / $item->$count_smk_coe * 100 : 0;
                        $persen_terima = ($item->$count_terima) ? $item->$count_terima / $item->$count_smk_coe * 100 : 0;
                        //$total_instrumen += 1;//$nilai2_instrumen;
                        //$total_rapor_mutu += 1;//$nilai2_nilai_akhir;
                        //$total_pakta_integritas += 1;//$nilai2_pakta_integritas;
                        //$total_waiting += 1;//$nilai2_waiting;
                        //$total_proses += 1;//$nilai2_proses;
                        //$total_terima += 1;//$nilai2_terima;
                        ?>
                        <tr>
                            <td>
                                @if($next_level_wilayah == 4)
                                <a href="{{route('page', ['query' => 'progres', 'id_level_wilayah' => $next_level_wilayah, 'kode_wilayah' => $item->kode_wilayah])}}">{{$item->nama}}</a>
                                @else
                                <a href="{{route('page', ['query' => 'progres-data', 'id_level_wilayah' => $next_level_wilayah, 'kode_wilayah' => $item->kode_wilayah])}}">{{$item->nama}}</a>
                                @endif
                            </td>
                            <td class="text-center">{{$item->$count_smk}}</td>
                            <td class="text-center">{{$item->$count_smk_coe}}</td>
                            <td class="text-center">{{$item->$count_instrumen}}</td>
                            <td class="text-center">{{($persen_instrumen) ? number_format($persen_instrumen,0).'%' : '0%'}}</td>
                            <td class="text-center">{{$item->$count_rapor_mutu}}</td>
                            <td class="text-center">{{($persen_rapor_mutu) ? number_format($persen_rapor_mutu,0).'%' : '0%'}}</td>
                            <td class="text-center">{{$item->$count_pakta_integritas}}</td>
                            <td class="text-center">{{($persen_pakta_integritas) ? number_format($persen_pakta_integritas,0).'%' : '0%'}}</td>
                            <td class="text-center">{{$item->$count_waiting}}</td>
                            <td class="text-center">{{($persen_waiting) ? number_format($persen_waiting,0).'%' : '0%'}}</td>
                            <td class="text-center">{{$item->$count_proses}}</td>
                            <td class="text-center">{{($persen_proses) ? number_format($persen_proses,0).'%' : '0%'}}</td>
                            <td class="text-center">{{$item->$count_terima}}</td>
                            <td class="text-center">{{($persen_terima) ? number_format($persen_terima,0).'%' : '0%'}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="14">Tidak ada data untuk ditampilkan</td>
                        </tr>
                        @endforelse
                        <?php
                        $jml_persen_instrumen = ($jml_instrumen) ? $jml_instrumen / $jml_sekolah_coe * 100 : 0;
                        $jml_persen_rapor_mutu = ($jml_rapor_mutu) ? $jml_rapor_mutu / $jml_sekolah_coe * 100 : 0;
                        $jml_persen_pakta_integritas = ($jml_pakta_integritas) ? $jml_pakta_integritas / $jml_sekolah_coe * 100 : 0;
                        $jml_persen_waiting = ($jml_waiting) ? $jml_waiting / $jml_sekolah_coe * 100 : 0;
                        $jml_persen_proses = ($jml_proses) ? $jml_proses / $jml_sekolah_coe * 100 : 0;
                        $jml_persen_terima = ($jml_terima) ? $jml_terima / $jml_sekolah_coe * 100 : 0;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center"><strong>Jumlah</strong></td>
                            <td class="text-center"><strong>{{$jml_sekolah_nasional}}</strong></td>
                            <td class="text-center"><strong>{{$jml_sekolah_coe}}</strong></td>
                            <td class="text-center"><strong>{{$jml_instrumen}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_instrumen) ? number_format($jml_persen_instrumen,0).'%' : '0%'}}</strong></td>
                            <td class="text-center"><strong>{{$jml_rapor_mutu}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_rapor_mutu) ? number_format($jml_persen_rapor_mutu,0).'%' : '0%'}}</strong></td>
                            <td class="text-center"><strong>{{$jml_pakta_integritas}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_pakta_integritas) ? number_format($jml_persen_pakta_integritas,0).'%' : '0%'}}</strong></td>
                            <td class="text-center"><strong>{{$jml_waiting}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_waiting) ? number_format($jml_persen_waiting,0).'%' : '0%'}}</strong></td>
                            <td class="text-center"><strong>{{$jml_proses}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_proses) ? number_format($jml_persen_proses,0).'%' : '0%'}}</strong></td>
                            <td class="text-center"><strong>{{$jml_terima}}</strong></td>
                            <td class="text-center"><strong>{{($jml_persen_terima) ? number_format($jml_persen_terima,0).'%' : '0%'}}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $('#datatable_test').DataTable( {
        responsive: true
    } );
    var table = $('#datatable').DataTable( {
        lengthMenu: [[-1, 10, 25, 50], ["Semua", 10, 25, 50]],
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('api.wilayah') }}',
            data: function(d){
                d.id_level_wilayah = {{$id_level_wilayah}};
            },
        },
        responsive: true,
        columns: [
            {data: 'nama', name: 'nama', orderable: false},
            {data: 'count_sekolah', name: 'count_sekolah', className: 'text-center', orderable: false},
            {data: 'instrumen', name: 'instrumen', className: 'text-center', orderable: false},
            {data: 'rapor_mutu', name: 'rapor_mutu', className: 'text-center', orderable: false},
            {data: 'pakta_integritas', name: 'pakta_integritas', className: 'text-center', orderable: false},
            {data: 'verval', name: 'verval', className: 'text-center', orderable: false},
            {data: 'verifikasi', name: 'verifikasi', className: 'text-center', orderable: false},
            {data: 'pengesahan', name: 'pengesahan', className: 'text-center', orderable: false},
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
