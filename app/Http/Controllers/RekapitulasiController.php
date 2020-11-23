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
        ->rawColumns(['nama', 'npsn', 'nilai_input', 'nilai_proses', 'nilai_output', 'nilai_kinerja', 'terendah', 'tertinggi', 'afirmasi', 'nilai_outcome', 'nilai_impact', 'nilai_dampak', 'nilai_akhir', 'predikat'])
        ->make(true);
    }
}
