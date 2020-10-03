<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Wilayah;
use Illuminate\Support\Facades\DB;
class FrontController extends Controller
{
    public function progress(Request $request){
        $query = User::query()->whereHas('sekolah.sekolah_sasaran')->with(['sekolah' => function($query){
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
