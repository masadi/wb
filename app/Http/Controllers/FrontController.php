<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Sekolah;
use App\Sekolah_sasaran;
use App\Wilayah;
use App\Komponen;
use App\HelperModel;
use Illuminate\Support\Facades\DB;
class FrontController extends Controller
{
    public function progress(Request $request){
        $query = Sekolah::query()->whereHas('sekolah_sasaran', function($query){
            if (request()->has('status_verifikasi')) {
                if (request('status_verifikasi') == 1) {
                    $query->has('waiting');
                } elseif (request('status_verifikasi') == 2) {
                    $query->doesntHave('waiting');
                }
            }
            if (request('tahap')) {
                $query->where('tahap', request('tahap'));
            }
        })->with(['pendamping', 'sekolah_sasaran' => function($query){
            $query->with(['rapor_mutu', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak', 'verifikator']);
        }])->with(['user.nilai_akhir'])->withCount('nilai_instrumen')->where(function($query){
            if(request()->kode_wilayah){
                $query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
                });
            }
        })->get();
        return DataTables::of($query)
        /*->filter(function ($query) {
            if (request()->has('status_verifikasi')) {
                if (request('status_verifikasi')) {
                    $query->whereHas('sekolah_sasaran', function($query){
                        $query->whereHas('proses');
                    });
                } else {
                    $query->whereHas('sekolah_sasaran', function($query){
                        $query->doesntHave('proses');
                    });
                }
            }
            if (request()->has('tahap')) {
                $query->whereHas('sekolah_sasaran', function($query){
                    $query->where('tahap', request('tahap'));
                });
            }
        }, true)*/
        ->addIndexColumn()
        ->addColumn('nama', function ($item) {
            $links = $item->nama;
            return $links;
        })
        ->addColumn('npsn', function ($item) {
            $links = $item->npsn;
            return $links;
        })
        ->addColumn('tahap', function ($item) {
            $links = $item->sekolah_sasaran->tahap;
            return $links;
        })
        ->addColumn('nama_pendamping', function ($item) {
            $links = ($item->pendamping) ? $item->pendamping->nama : '-';
            return $links;
        })
        ->addColumn('nama_verifikator', function ($item) {
            $links = ($item->sekolah_sasaran->verifikator) ? ($item->sekolah_sasaran->verifikator->name != 'Tim Verifikator') ? $item->sekolah_sasaran->verifikator->name : '-' : '-';
            return $links;
        })
        ->addColumn('instrumen', function ($item) {
            if($item->nilai_instrumen_count){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('rapor_mutu', function ($item) {
            if($item->user->nilai_akhir){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('pakta_integritas', function ($item) {
            if($item->sekolah_sasaran->pakta_integritas){
                if($item->sekolah_sasaran->pakta_integritas->terkirim){
                    $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
                } else {
                    $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
                }
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('verval', function ($item) {
            if($item->sekolah_sasaran->waiting){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('verifikasi', function ($item) {
            if($item->sekolah_sasaran->proses){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('pengesahan', function ($item) {
            if($item->sekolah_sasaran->terima){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></div>';
            }
            return $links;
        })
        ->addColumn('edit_tahap', function ($item) {
            $links = '<div class="text-center"><a data-sekolah_sasaran_id="'.$item->sekolah_sasaran->sekolah_sasaran_id.'" class="btn btn-sm btn-warning toggle-modal" href="'.route('api.edit_tahap', ['id' => $item->sekolah_sasaran->sekolah_sasaran_id]).'"><i class="fas fa-pencil-alt"></i></a></div>';
            return $links;
        })
        ->rawColumns(['nama', 'npsn', 'instrumen', 'rapor_mutu', 'pakta_integritas', 'verval', 'verifikasi', 'pengesahan', 'edit_tahap'])
        ->make(true);
    }
    public function filter_wilayah(Request $request)
    {
        //dd($request->all());
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->whereRaw("trim(mst_kode_wilayah) ='".$request->kode_wilayah."'")->orderBy('kode_wilayah')->get();
        $output = [];
        foreach($all_wilayah as $wilayah){
            $record= [];
			$record['value'] 	= $wilayah->kode_wilayah;
			$record['text'] 	= $wilayah->nama;
			$output['result'][] = $record;
        }
        if($request->id_level_wilayah == 2){
            $output['all_sekolah'] = Sekolah::has('smk_coe')->whereRaw("trim(kabupaten_id) ='".$request->kode_wilayah."'")->selectRaw('sekolah_id as value, nama as text')->get();
        }
        if(request()->id_level_wilayah == 1){
            $wilayah = '_provinsi';
        } elseif(request()->id_level_wilayah == 2){
            $wilayah = '_kabupaten';
        } elseif(request()->id_level_wilayah == 3){
            $wilayah = '_kecamatan';
        }
        $with = 'sekolah'.$wilayah;
        $with_coe = 'sekolah_coe'.$wilayah;
        $with_instrumen = 'sekolah_instrumen'.$wilayah;
        $with_pakta_integritas = 'sekolah_pakta_integritas'.$wilayah;
        $with_rapor_mutu = 'sekolah_rapor_mutu'.$wilayah;
        $with_waiting = 'sekolah_waiting'.$wilayah;
        $with_proses = 'sekolah_proses'.$wilayah;
        $with_terima = 'sekolah_terima'.$wilayah;
        $with_tolak = 'sekolah_tolak'.$wilayah;
        $data_count = 'sekolah'.$wilayah.'_count';
        $data_count_coe = 'sekolah_coe'.$wilayah.'_count';
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', request()->id_level_wilayah);
            if(request()->kode_wilayah){
                $query->whereRaw("trim(kode_wilayah) = '".request()->kode_wilayah."'");
            }
        })->withCount([$with, $with_coe, $with_instrumen, $with_rapor_mutu, $with_pakta_integritas, $with_waiting, $with_proses, $with_terima, $with_tolak])->orderBy('kode_wilayah')->get();
        $sekolah_coe_count = 0;
        $sekolah_instrumen_count = 0;
        $sekolah_rapor_mutu_count = 0;
        $sekolah_pakta_integritas_count = 0;
        $sekolah_waiting_count = 0;
        $sekolah_proses_count = 0;
        $sekolah_terima_count = 0;
        $sekolah_tolak_count = 0;
        $coe_count = $with_coe.'_count';
        $instrumen_count = $with_instrumen.'_count';
        $pakta_integritas_count = $with_pakta_integritas.'_count';
        $rapor_mutu_count = $with_rapor_mutu.'_count';
        $waiting_count = $with_waiting.'_count';
        $proses_count = $with_proses.'_count';
        $terima_count = $with_terima.'_count';
        $tolak_count = $with_tolak.'_count';
        foreach($all_wilayah as $wilayah){
            $sekolah_coe_count += $wilayah->$coe_count;
            $sekolah_instrumen_count += $wilayah->$instrumen_count;
            $sekolah_rapor_mutu_count += $wilayah->$rapor_mutu_count;
            $sekolah_pakta_integritas_count += $wilayah->$pakta_integritas_count;
            $sekolah_waiting_count += $wilayah->$waiting_count;
            $sekolah_proses_count += $wilayah->$proses_count;
            $sekolah_terima_count += $wilayah->$terima_count;
            $sekolah_tolak_count += $wilayah->$tolak_count;
        }
        $counting = [
            $sekolah_coe_count, 
            $sekolah_instrumen_count, 
            $sekolah_coe_count - $sekolah_instrumen_count,
            $sekolah_rapor_mutu_count, 
            $sekolah_coe_count - $sekolah_rapor_mutu_count,
            $sekolah_pakta_integritas_count, 
            $sekolah_coe_count - $sekolah_pakta_integritas_count,
            $sekolah_waiting_count, 
            $sekolah_coe_count - $sekolah_waiting_count,
            $sekolah_proses_count, 
            $sekolah_coe_count - $sekolah_proses_count,
            $sekolah_terima_count, 
            $sekolah_coe_count - $sekolah_terima_count,
        ];
        $callback = function($query){
            $query->whereHas('user.sekolah', function($query){
                $query->has('sekolah_sasaran');
                if(request()->id_level_wilayah == 1){
                    $query->whereRaw("trim(provinsi_id) ='".request()->kode_wilayah."'");
                } elseif(request()->id_level_wilayah == 2){
                    $query->whereRaw("trim(kabupaten_id) ='".request()->kode_wilayah."'");
                } elseif(request()->id_level_wilayah == 3){
                    $query->whereRaw("trim(kecamatan_id) ='".request()->kode_wilayah."'");
                }
                //$query->whereIn('kode_wilayah', function($query){
                    //$query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) ='".request()->kode_wilayah."'");
                //});
            });
        };
        $all_komponen = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->get();
        $nilai_komponen = [];
        $nilai_komponen_chart = [];
        $nilai_komponen_verifkasi = [];
        $nilai_komponen_komparasi = [];
        $nama_komponen_chart = [];
        foreach($all_komponen as $komponen){
            $record_komponen= [];
            $record_komponen['nilai'] 	= number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $record_komponen['nilai_verifikasi'] 	= number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nilai_komponen_komparasi[] = [
                'komponen' => $komponen->nama,
                'sekolah' => number_format($komponen->all_nilai_komponen->avg('total_nilai'),2),
                'verifikasi' => number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2),
            ];
            $record_komponen['bintang'] 	= HelperModel::bintang_icon(number_format($komponen->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            foreach($komponen->aspek as $aspek){
                $record_komponen['nilai_aspek'][strtolower(HelperModel::clean($aspek->nama))] = number_format($aspek->all_nilai_aspek->avg('total_nilai'),2);
            }
            $nilai_komponen[] = $record_komponen;
            $record_chart = [];
            $nilai_komponen_chart[] = number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_verifikasi[] = number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen_chart[] 	= $komponen->nama;
        }
        $komponen_kinerja = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [1,2,3])->get();
        $komponen_dampak = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [4,5])->get();
        foreach($komponen_kinerja as $kinerja){
            $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_kinerja_verifikasi[] = number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $bintang_komponen_kinerja[] 	= HelperModel::bintang_icon(number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            $bintang_komponen_kinerja_verifikasi[] 	= HelperModel::bintang_icon(number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2), 'warning');
            $nama_komponen_kinerja[] = strtolower($kinerja->nama);
        }
        foreach($komponen_dampak as $dampak){
            $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_dampak_verifikasi[] = number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $bintang_komponen_dampak[] 	= HelperModel::bintang_icon(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            $bintang_komponen_dampak_verifikasi[] 	= HelperModel::bintang_icon(number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2), 'warning');
            $nama_komponen_dampak[] = strtolower($dampak->nama);
        }
        $group_komponen = [
            'all_kinerja' => [
                'nilai' => $nilai_komponen_kinerja,
                'nilai_verifikasi' => $nilai_komponen_kinerja_verifikasi,
                'nama' => $nama_komponen_kinerja,
                'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
                'bintang' => $bintang_komponen_kinerja,
                'bintang_verifikasi' => $bintang_komponen_kinerja_verifikasi,
            ],
            'all_dampak' => [
                'nilai' => $nilai_komponen_dampak,
                'nilai_verifikasi' => $nilai_komponen_dampak_verifikasi,
                'nama' => $nama_komponen_dampak,
                'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
                'bintang' => $bintang_komponen_dampak,
                'bintang_verifikasi' => $bintang_komponen_dampak_verifikasi,
            ],
        ];
        $output = array_merge($output, $group_komponen);
        return response()->json(['output' => $output, 'counting' => $counting, 'nilai_komponen_kotak' => $nilai_komponen, 'nilai_komponen' => $nilai_komponen_chart, 'nilai_komponen_verifikasi' => $nilai_komponen_verifikasi, 'nilai_komponen_komparasi' => $nilai_komponen_komparasi, 'nama_komponen' => $nama_komponen_chart]);
    }
    public function get_wilayah(Request $request){
        if(request()->id_level_wilayah == 1){
            $with = 'sekolah_provinsi';
        } elseif(request()->id_level_wilayah == 2){
            $with = 'sekolah_kabupaten';
        } elseif(request()->id_level_wilayah == 3){
            $with = 'sekolah_kecamatan';
        }
        $query = Wilayah::query()->whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where('id_level_wilayah', request()->id_level_wilayah)->withCount($with)->with([$with => function($query){
            $query->with(['sekolah_sasaran' => function($query){
                $query->with(['terkirim', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
            $query->with(['user.nilai_akhir']);
            $query->withCount('nilai_instrumen');
        }])->orderBy('kode_wilayah');
        return DataTables::eloquent($query)
        /*->order(function ($query) {
            if (request()->has('nama')) {
                $query->orderBy('nama', 'asc');
            }
        })*/
        ->addColumn('count_sekolah', function ($item) {
            if(request()->id_level_wilayah == 1){
                $links = $item->sekolah_provinsi_count;
            } elseif(request()->id_level_wilayah == 2){
                $links = $item->sekolah_kabupaten_count;
            } elseif(request()->id_level_wilayah == 3){
                $links = $item->sekolah_kecamatan_count;
            }
            return $links;
        })
        ->addColumn('instrumen', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return $data->nilai_instrumen_count;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->addColumn('rapor_mutu', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return $data->user->nilai_akhir;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->addColumn('pakta_integritas', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->pakta_integritas : NULL;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->addColumn('verval', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->waiting : NULL;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->addColumn('verifikasi', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->proses : NULL;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->addColumn('pengesahan', function ($item) use ($with){
            $count = $item->{$with}->map(function($data){
                return ($data->sekolah_sasaran) ? $data->sekolah_sasaran->terima : NULL;
            })->toArray();
            $nilai1 = count(array_filter($count));
            $nilai2 = count($count);
            $persen= ($nilai2) ? $nilai1 / $nilai2 * 100 : 0;
            return ($persen) ? number_format($persen,0).'%' : '0%';
        })
        ->rawColumns(['nama', 'count_sekolah', 'instrumen', 'rapor_mutu', 'pakta_integritas', 'verval', 'verifikasi', 'pengesahan'])
        ->make(true);
    }
    public function smk_coe(Request $request){
        $query = Sekolah::query()->has('smk_coe')->has('sekolah_sasaran');//->orderBy('kecamatan_id')->orderBy('kabupaten_id')->orderBy('provinsi_id');
        return DataTables::eloquent($query)
        /*->orderColumn('name', function ($query, $order) {
            $query->orderBy('status', $order);
        })*/
        ->toJson();
    }
    public function edit_tahap(Request $request){
        $sekolah_sasaran_id = $request->route('id');
        if(!$sekolah_sasaran_id){
            $sekolah_sasaran_id = $request->sekolah_sasaran_id;
        }
        $sekolah_sasaran = Sekolah_sasaran::find($sekolah_sasaran_id);
        if ($request->isMethod('post')) {
            $sekolah_sasaran->tahap = $request->tahap;
            if($sekolah_sasaran->save()){
                $output = [
                    'title' => 'Berhasil',
                    'text' => 'Tahap verifikasi berhasil disimpan',
                    'icon' => 'success',
                ]; 
            } else {
                $output = [
                    'title' => 'Gagal',
                    'text' => 'Tahap verifikasi gagal disimpan',
                    'icon' => 'error',
                ];
            }
        } else {
            $output = [
                1 => 'Tahap 1',
                2 => 'Tahap 2',
                3 => 'Tahap 3',
                4 => 'Tahap 4',
                5 => 'Tahap 5',
            ];
        }
        return response()->json(['output' => $output, 'sekolah_sasaran_id' => $sekolah_sasaran_id, 'tahap' => $sekolah_sasaran->tahap]);
    }
}
