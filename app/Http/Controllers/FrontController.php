<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Wilayah;
use App\Komponen;
use App\HelperModel;
use Illuminate\Support\Facades\DB;
class FrontController extends Controller
{
    public function progress(Request $request){
        $query = User::query()->whereHas('sekolah', function($query){
            //$query->whereHas('smk_coe');
            $query->whereHas('sekolah_sasaran');
            $query->whereIn('kode_wilayah', function($query){
                $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
            });
        })->with(['sekolah' => function($query){
            $query->with(['sekolah_sasaran' => function($query){
                $query->with(['rapor_mutu', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
            $query->with(['user.nilai_akhir']);
            $query->withCount('nilai_instrumen');
        }])->get();
        return DataTables::of($query)
        ->addColumn('nama', function ($item) {
            $links = $item->sekolah->nama;
            return $links;
        })
        ->addColumn('npsn', function ($item) {
            $links = $item->sekolah->npsn;
            return $links;
        })
        ->addColumn('instrumen', function ($item) {
            if($item->sekolah->nilai_instrumen_count){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('rapor_mutu', function ($item) {
            if($item->sekolah->user->nilai_akhir){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('pakta_integritas', function ($item) {
            if($item->sekolah->sekolah_sasaran->pakta_integritas){
                if($item->sekolah->sekolah_sasaran->pakta_integritas->terkirim){
                    $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
                } else {
                    $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
                }
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('verval', function ($item) {
            if($item->sekolah->sekolah_sasaran->waiting){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('verifikasi', function ($item) {
            if($item->sekolah->sekolah_sasaran->proses){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('pengesahan', function ($item) {
            if($item->sekolah->sekolah_sasaran->terima){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->rawColumns(['nama', 'npsn', 'instrumen', 'rapor_mutu', 'pakta_integritas', 'verval', 'verifikasi', 'pengesahan'])
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
        $all_komponen = Komponen::with(['all_nilai_komponen' => $callback, 'aspek.all_nilai_aspek' => $callback])->get();
        $nilai_komponen = [];
        $nilai_komponen_chart = [];
        $nama_komponen_chart = [];
        foreach($all_komponen as $komponen){
            $record_komponen= [];
			$record_komponen['nilai'] 	= number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $record_komponen['bintang'] 	= HelperModel::bintang_icon(number_format($komponen->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            foreach($komponen->aspek as $aspek){
                $record_komponen['nilai_aspek'][strtolower(HelperModel::clean($aspek->nama))] = number_format($aspek->all_nilai_aspek->avg('total_nilai'),2);
            }
            $nilai_komponen[] = $record_komponen;
            $record_chart = [];
            $nilai_komponen_chart[] = number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $nama_komponen_chart[] 	= $komponen->nama;
        }
        return response()->json(['output' => $output, 'nilai_komponen_kotak' => $nilai_komponen, 'nilai_komponen' => $nilai_komponen_chart, 'nama_komponen' => $nama_komponen_chart]);
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
}
