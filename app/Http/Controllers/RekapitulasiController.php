<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Sekolah;
use App\User;
use App\Nilai_akhir;
class RekapitulasiController extends Controller
{
    public function index(Request $request){
        $query = Sekolah::query()->has('smk_coe')->with(['nilai_input' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_proses' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_output' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_outcome' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_impact' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_kinerja' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
            $query->with('komponen');
        }, 'nilai_dampak' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_akhir' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }]/*)->where(function($query){
            if(request()->kode_wilayah){
                $query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
                });
            }
        }*/)->orderBy('provinsi_id')->orderBy('kabupaten_id');
        return DataTables::of($query)
        ->addIndexColumn()
        ->filter(function ($query) {
            if (request()->has('provinsi_id')) {
                /*$query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->provinsi_id."'");
                });*/
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            }
            if (request()->has('kabupaten_id')) {
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            }
            if (request()->has('sekolah_id')) {
                $query->where('sekolah_id', request('sekolah_id'));
            }
        }, true)
        ->addColumn('nilai_input', function ($item) {
            $links = ($item->nilai_input) ? $item->nilai_input->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_proses', function ($item) {
            $links = ($item->nilai_proses) ? $item->nilai_proses->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_output', function ($item) {
            $links = ($item->nilai_output) ? $item->nilai_output->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_kinerja', function ($item) {
            $links = ($item->nilai_kinerja) ? number_format($item->nilai_kinerja->avg('total_nilai'),2,'.','.') : 0;
            return $links;
        })
        ->addColumn('terendah', function ($item) {
            $keyed = [];
            if($item->nilai_kinerja){
                $nilai_terendah = $item->nilai_kinerja->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed = $nilai_terendah->keyBy('nilai')->toArray();
            }
            $links = ($keyed) ? ($keyed[$item->nilai_kinerja->min('total_nilai')]) ? $keyed[$item->nilai_kinerja->min('total_nilai')]['nama'] : '-' : '-';
            return $links;
        })
        ->addColumn('tertinggi', function ($item) {
            $keyed = [];
            if($item->nilai_kinerja){
                $nilai_terendah = $item->nilai_kinerja->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed = $nilai_terendah->keyBy('nilai')->toArray();
            }
            $links = ($keyed) ? ($keyed[$item->nilai_kinerja->max('total_nilai')]) ? $keyed[$item->nilai_kinerja->max('total_nilai')]['nama'] : '-' : '-';
            return $links;
        })
        ->addColumn('afirmasi', function ($item) {
            $links = '-';
            return $links;
        })
        ->addColumn('nilai_outcome', function ($item) {
            $links = ($item->nilai_outcome) ? $item->nilai_outcome->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_impact', function ($item) {
            $links = ($item->nilai_impact) ? $item->nilai_impact->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_dampak', function ($item) {
            $links = ($item->nilai_dampak) ? number_format($item->nilai_dampak->avg('total_nilai'),2,'.','.') : 0;
            return $links;
        })
        ->addColumn('nilai_akhir', function ($item) {
            $links = ($item->nilai_akhir) ? $item->nilai_akhir->nilai : 0;
            return $links;
        })
        ->addColumn('predikat', function ($item) {
            $links = ($item->nilai_akhir) ? $item->nilai_akhir->predikat : 0;
            return $links;
        })
        ->addColumn('unduh_detil', function ($item) {
            $links = '<a class="btn btn-sm btn-success" href="'.route('unduhan.instrumen', ['sekolah_id' => $item->sekolah_id]).'"><i class="fas fa-download"></i></a>';
            return $links;
        })
        ->rawColumns(['nama', 'npsn', 'nilai_input', 'nilai_proses', 'nilai_output', 'nilai_kinerja', 'terendah', 'tertinggi', 'afirmasi', 'nilai_outcome', 'nilai_impact', 'nilai_dampak', 'nilai_akhir', 'predikat', 'unduh_detil'])
        ->make(true);
    }
    public function wilayah(Request $request){
        $jenis_rapor_mutu = $request->jenis_rapor_mutu;
        $sangat_baik = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                //$query->whereBetween('nilai', [91.00, 100.00]);
                //$query->where('nilai', '>=', 91.00);
                //$query->where('nilai', '<=', 100.00);
                $query->where('peringkat', 1);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $baik = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                //$query->whereBetween('nilai', [76, 90.99]);
                //$query->where('nilai', '>=', 76.00);
                //$query->where('nilai', '<', 91.00);
                $query->where('peringkat', 2);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $cukup_baik = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                //$query->whereBetween('nilai', [61, 75.99]);
                //$query->where('nilai', '>=', 61.00);
                //$query->where('nilai', '<', 76.00);
                $query->where('peringkat', 3);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $kurang_baik = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                //$query->whereBetween('nilai', [46, 60.99]);
                //$query->where('nilai', '>=', 46.00);
                //$query->where('nilai', '<', 61.00);
                $query->where('peringkat', 4);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $tidak_baik = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                //$query->whereBetween('nilai', [0, 45.99]);
                //$query->where('nilai', '>=', 46);
                //$query->where('nilai', '<', 46.00);
                $query->where('peringkat', 5);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $sangat_baik_provinsi = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 1);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $baik_provinsi = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 2);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $cukup_baik_provinsi = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 3);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $kurang_baik_provinsi = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 4);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $tidak_baik_provinsi = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 5);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $sangat_baik_kabupaten = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 1);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $baik_kabupaten = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 2);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $cukup_baik_kabupaten = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 3);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $kurang_baik_kabupaten = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 4);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $tidak_baik_kabupaten = Sekolah::where(function($query) use ($jenis_rapor_mutu){
            $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
            $query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                $query->where('peringkat', 5);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            });
        })->count();
        $data = [
            'rekap_nasional' => [
                'sangat_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'sangat_baik', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$sangat_baik.'</a>',
                'baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'baik', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$baik.'</a>',
                'cukup_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'cukup_baik', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$cukup_baik.'</a>',
                'kurang_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'kurang_baik', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$kurang_baik.'</a>',
                'tidak_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'tidak_baik', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$tidak_baik.'</a>',
                'jumlah' => $sangat_baik + $baik + $cukup_baik + $kurang_baik + $tidak_baik,
            ],
            'rekap_provinsi' => [
                'sangat_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'sangat_baik_provinsi', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$sangat_baik_provinsi.'</a>',
                'baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'baik_provinsi', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$baik_provinsi.'</a>',
                'cukup_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'cukup_baik_provinsi', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$cukup_baik_provinsi.'</a>',
                'kurang_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'kurang_baik_provinsi', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$kurang_baik_provinsi.'</a>',
                'tidak_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'tidak_baik_provinsi', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$tidak_baik_provinsi.'</a>',
                'jumlah' => $sangat_baik_provinsi + $baik_provinsi + $cukup_baik_provinsi + $kurang_baik_provinsi + $tidak_baik_provinsi,
            ],
            'rekap_kabupaten' => [
                'sangat_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'sangat_baik_kabupaten', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$sangat_baik_kabupaten.'</a>',
                'baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'baik_kabupaten', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$baik_kabupaten.'</a>',
                'cukup_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'cukup_baik_kabupaten', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$cukup_baik_kabupaten.'</a>',
                'kurang_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'kurang_baik_kabupaten', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$kurang_baik_kabupaten.'</a>',
                'tidak_baik' => '<a class="toggle-modal" href="'.route('laporan.detil_rekap', ['query' => 'tidak_baik_kabupaten', 'jenis_rapor_mutu' => $jenis_rapor_mutu]).'">'.$tidak_baik_kabupaten.'</a>',
                'jumlah' => $sangat_baik_kabupaten + $baik_kabupaten + $cukup_baik_kabupaten + $kurang_baik_kabupaten + $tidak_baik_kabupaten,
            ],
            'chart' => [
                [
                    'komponen' => 'Sangat Baik',
                    'nasional' => $sangat_baik,
                    'provinsi' => $sangat_baik_provinsi,
                    'kabupaten' => $sangat_baik_kabupaten,
                ],
                [
                    'komponen' => 'Baik',
                    'nasional' => $baik,
                    'provinsi' => $baik_provinsi,
                    'kabupaten' => $baik_kabupaten,
                ],
                [
                    'komponen' => 'Cukup Baik',
                    'nasional' => $cukup_baik,
                    'provinsi' => $cukup_baik_provinsi,
                    'kabupaten' => $cukup_baik_kabupaten,
                ],
                [
                    'komponen' => 'Kurang Baik',
                    'nasional' => $kurang_baik,
                    'provinsi' => $kurang_baik_provinsi,
                    'kabupaten' => $kurang_baik_kabupaten,
                ],
                [
                    'komponen' => 'Tidak Baik',
                    'nasional' => $tidak_baik,
                    'provinsi' => $tidak_baik_provinsi,
                    'kabupaten' => $tidak_baik_kabupaten,
                ],
            ]
        ];
        return response()->json(['data' => $data]);
    }
    public function detil_rekap(Request $request){
        $query = $request->route('query');
        $jenis_rapor_mutu = $request->route('jenis_rapor_mutu');
        if($query == 'sangat_baik'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [91.00, 100.00]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [91.00, 100.00]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'baik'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [76, 90.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [76, 90.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'cukup_baik'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [61, 75.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [61, 75.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'kurang_baik'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [46, 60.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [46, 60.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'tidak_baik'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [0, 45.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [0, 45.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        }
        if($query == 'sangat_baik_provinsi'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [91.00, 100.00]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [91.00, 100.00]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'baik_provinsi'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [76, 90.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [76, 90.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'cukup_baik_provinsi'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [61, 75.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [61, 75.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'kurang_baik_provinsi'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [46, 60.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [46, 60.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'tidak_baik_provinsi'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [0, 45.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(provinsi_id) = '". request()->provinsi_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [0, 45.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } 
        if($query == 'sangat_baik_kabupaten'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [91.00, 100.00]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [91.00, 100.00]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'baik_kabupaten'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [76, 90.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [76, 90.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'cukup_baik_kabupaten'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [61, 75.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [61, 75.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'kurang_baik_kabupaten'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [46, 60.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [46, 60.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        } elseif($query == 'tidak_baik_kabupaten'){
            $data = Sekolah::with(['sekolah_sasaran.sektor', 'nilai_akhir' => function($query) use ($jenis_rapor_mutu){
                $query->whereBetween('nilai', [0, 45.99]);
                if($jenis_rapor_mutu == 'verifikasi'){
                    $query->whereNotNull('verifikator_id');
                    $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                } else {
                    $query->whereNull('verifikator_id');
                }
            }])->where(function($query) use ($jenis_rapor_mutu){
                $query->whereRaw("trim(kabupaten_id) = '". request()->kabupaten_id."'");
                $query->whereHas('smk_coe');
                $query->whereHas('sekolah_sasaran');
                $query->whereHas('nilai_akhir', function($query) use ($jenis_rapor_mutu){
                    $query->whereBetween('nilai', [0, 45.99]);
                    if($jenis_rapor_mutu == 'verifikasi'){
                        $query->whereNotNull('verifikator_id');
                        $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
                    } else {
                        $query->whereNull('verifikator_id');
                    }
                });
            })->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        }
        $params = [
            'data_sekolah' => $data,
            'title' => 'Detil Sekolah Predikat',
        ];
        return view('page.detil_rekap')->with($params);
    }
}
