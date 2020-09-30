<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\User;
class FrontController extends Controller
{
    public function progress(Request $request){
        $query = User::query()->whereHas('sekolah.sekolah_sasaran')->with(['last_nilai_instrumen', 'sekolah.sekolah_sasaran' => function($query){
            $query->with(['rapor_mutu', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
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
            if($item->last_nilai_instrumen){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('rapor_mutu', function ($item) {
            if($item->sekolah->sekolah_sasaran->rapor_mutu){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('pakta_integritas', function ($item) {
            if($item->sekolah->sekolah_sasaran->pakta_integritas){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
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
}
