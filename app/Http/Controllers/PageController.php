<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wilayah;
class PageController extends Controller
{
    public function index(Request $request){
        $query = $request->route('query');
        $query = str_replace('-', '_', $query);
        return $this->{$query}($request);
    }
    public function progres($request){
        $query = $request->route('query');
        $kode_wilayah = $request->route('kode_wilayah');
        $id_level_wilayah = $request->route('id_level_wilayah');
        return view('page.'.$query)->with(['id_level_wilayah' => $id_level_wilayah, 'kode_wilayah' => $kode_wilayah]);
    }
    public function home($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function berita($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function rapor_mutu($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function galeri($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function faq($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function progres_data($request){
        if(request()->id_level_wilayah){
            $id_level_wilayah = request()->id_level_wilayah;
            if(request()->id_level_wilayah == 2) {
                $with = 'sekolah_kabupaten';
                $with_coe = 'sekolah_coe_kabupaten';
                $data_count = 'sekolah_kabupaten_count';
                $data_count_coe = 'sekolah_coe_kabupaten_count';
            } else {
                $with = 'sekolah_kecamatan';
                $with_coe = 'sekolah_coe_kecamatan';
                $data_count = 'sekolah_kecamatan_count';
                $data_count_coe = 'sekolah_coe_kecamatan_count';
            }
            
        } else{
            $with = 'sekolah_provinsi';
            $with_coe = 'sekolah_coe_provinsi';
            $data_count = 'sekolah_provinsi_count';
            $data_count_coe = 'sekolah_coe_provinsi_count';
            $id_level_wilayah = 1;
        }
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query) use ($id_level_wilayah){
            $query->where('id_level_wilayah', $id_level_wilayah);
            if(request()->kode_wilayah){
                $query->where('mst_kode_wilayah', request()->kode_wilayah);
            }
        })->withCount([$with, $with_coe])->with([$with => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran' => function($query){
                $query->with(['terkirim', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
            $query->with(['user.nilai_akhir']);
            $query->withCount('nilai_instrumen');
        }])->orderBy('kode_wilayah')->get();
        $params = [
            'id_level_wilayah' => $id_level_wilayah,
            'all_wilayah' => $all_wilayah,
            'data_count_non_coe' => $data_count,
            'data_count_coe' => $data_count_coe,
            'with' => $with,
            'next_level_wilayah' => $id_level_wilayah + 1,
        ];
        return view('page.progres-wilayah')->with($params);
    }
}
