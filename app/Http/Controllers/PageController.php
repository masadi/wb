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
    public function progres_wilayah($request){
        if(request()->id_level_wilayah){
            $id_level_wilayah = request()->id_level_wilayah;
            if(request()->id_level_wilayah == 2) {
                $with = 'sekolah_kabupaten';
                $data_count = 'sekolah_kabupaten_count';
            } else {
                $with = 'sekolah_kecamatan';
                $data_count = 'sekolah_kecamatan_count';
            }
            
        } else{
            $with = 'sekolah_provinsi';
            $data_count = 'sekolah_provinsi_count';
            $id_level_wilayah = 1;
        }
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where('id_level_wilayah', $id_level_wilayah)->withCount($with)->with([$with => function($query){
            $query->with(['sekolah_sasaran' => function($query){
                $query->with(['terkirim', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
            $query->with(['user.nilai_akhir']);
            $query->withCount('nilai_instrumen');
        }])->orderBy('kode_wilayah')->get();
        $params = [
            'id_level_wilayah' => $id_level_wilayah,
            'all_wilayah' => $all_wilayah,
            'data_count' => $data_count,
            'with' => $with,
        ];
        return view('page.progres-wilayah')->with($params);
    }
}
