<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Sekolah;

class RekapitulasiController extends Controller
{
    public function index(Request $request){
        $query = Sekolah::query()->has('sekolah_sasaran')->with(['nilai_input' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_proses' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_output' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_outcome' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_impact' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_kinerja' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_dampak' => function($query){
            $query->whereNotNull('verifikator_id');
        }, 'nilai_akhir' => function($query){
            $query->whereNotNull('verifikator_id');
        }])->where(function($query){
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
        ->rawColumns(['nama', 'npsn', 'nilai_input', 'nilai_proses', 'nilai_output', 'nilai_kinerja', 'afirmasi', 'nilai_outcome', 'nilai_impact', 'nilai_dampak', 'nilai_akhir'])
        ->make(true);
    }
}
