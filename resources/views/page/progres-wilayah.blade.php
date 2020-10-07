@extends('layouts.app_rapor')
@section('title', 'Progres Data Penjaminan Mutu SMK')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-refresh mr-1"></i>
                <?php
                $wilayah = NULL;
                if($id_level_wilayah == 1){
                    $wilayah = 'Semua Provinsi';
                } else {
                    $wilayah = $nama_wilayah->nama;
                }
                ?>
                Progres Data Penjaminan Mutu SMK - {{$wilayah}}
            </h3></div>
            <div class="card-body table-responsive p-0">
                <table id="datatable_test" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Wilayah</th>
                            <th class="text-center" colspan="2">Jml SMK</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Pengisian Instrumen</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Hitung Rapor Mutu</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Pakta Integritas</th>
                            <th rowspan="2" class="text-center" colspan="2" style="vertical-align: middle;">Verval Verifikator</th>
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
                        $jml_sekolah_nasional += $item->$count_smk;
                        $jml_sekolah_coe += $item->$count_smk_coe;
                        $jml_instrumen += $item->$count_instrumen;
                        $jml_pakta_integritas += $item->$count_pakta_integritas;
                        $jml_rapor_mutu += $item->$count_rapor_mutu;
                        $jml_waiting += $item->$count_waiting;
                        $jml_proses += $item->$count_proses;
                        $jml_terima += $item->$count_terima;
                        $persen_instrumen = ($item->$count_instrumen > 0 && $item->$count_smk_coe > 0) ? $item->$count_instrumen / $item->$count_smk_coe * 100 : 0;
                        $persen_pakta_integritas = ($item->$count_pakta_integritas > 0 && $item->$count_smk_coe > 0) ? $item->$count_pakta_integritas / $item->$count_smk_coe * 100 : 0;
                        $persen_rapor_mutu = ($item->$count_rapor_mutu > 0 && $item->$count_smk_coe > 0) ? $item->$count_rapor_mutu / $item->$count_smk_coe * 100 : 0;
                        $persen_waiting = ($item->$count_waiting > 0 && $item->$count_smk_coe > 0) ? $item->$count_waiting / $item->$count_smk_coe * 100 : 0;
                        $persen_proses = ($item->$count_proses > 0 && $item->$count_smk_coe > 0) ? $item->$count_proses / $item->$count_smk_coe * 100 : 0;
                        $persen_terima = ($item->$count_terima > 0 && $item->$count_smk_coe > 0) ? $item->$count_terima / $item->$count_smk_coe * 100 : 0;
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
