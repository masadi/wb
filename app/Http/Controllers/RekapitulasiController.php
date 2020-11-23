<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Sekolah;

class RekapitulasiController extends Controller
{
    public function index(Request $request){
        $query = Sekolah::query()->has('sekolah_sasaran')->with(['pendamping', 'sekolah_sasaran' => function($query){
            $query->with(['rapor_mutu', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak', 'verifikator']);
        }])->with(['user.nilai_akhir'])->withCount('nilai_instrumen')->where(function($query){
            if(request()->kode_wilayah){
                $query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
                });
            }
        })->get();
        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('nama', function ($item) {
            $links = $item->nama;
            return $links;
        })
        ->addColumn('npsn', function ($item) {
            $links = $item->npsn;
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
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('rapor_mutu', function ($item) {
            if($item->user->nilai_akhir){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('pakta_integritas', function ($item) {
            if($item->sekolah_sasaran->pakta_integritas){
                if($item->sekolah_sasaran->pakta_integritas->terkirim){
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
            if($item->sekolah_sasaran->waiting){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('verifikasi', function ($item) {
            if($item->sekolah_sasaran->proses){
                $links = '<div class="text-center"><i class="fas fa-check text-success"></i></a></div>';
            } else {
                $links = '<div class="text-center"><i class="fas fa-times text-danger"></i></a></div>';
            }
            return $links;
        })
        ->addColumn('pengesahan', function ($item) {
            if($item->sekolah_sasaran->terima){
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
