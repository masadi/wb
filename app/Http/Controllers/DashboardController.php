<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wilayah;

class DashboardController extends Controller
{
    public function index(Request $request){
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'all_wilayah' => $all_wilayah,
        ];
        return view('dashboard.rapor-mutu.sekolah')->with($params);
    }
    public function rapor_mutu(Request $request){
        $query = $request->route('query');
        $query = 'get_'.str_replace('-', '_', $query);
        if (method_exists($this, $query)) {
            return $this->{$query}($request);
        } else {
            abort(404);
        }
    }
    public function get_verifikasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'komponen' => Komponen::with('all_nilai_komponen', 'aspek.all_nilai_aspek')->get(),
            'all_wilayah' => $all_wilayah,
        ];
        return view('dashboard.rapor-mutu.'.$query)->with($params);
    }
    public function get_afirmasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'komponen' => Komponen::with('all_nilai_komponen', 'aspek.all_nilai_aspek')->get(),
            'all_wilayah' => $all_wilayah,
        ];
        return view('dashboard.rapor-mutu.'.$query)->with($params);
    }
    public function get_komparasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'komponen' => Komponen::with('all_nilai_komponen', 'aspek.all_nilai_aspek')->get(),
            'komponen_kinerja' => Komponen::whereIn('id', [1,2,3])->get(),
            'komponen_dampak' => Komponen::whereIn('id', [4,5])->get(),
            'all_wilayah' => $all_wilayah,
        ];
        return view('dashboard.rapor-mutu.'.$query)->with($params);
    }
}
