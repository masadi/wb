<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wilayah;
use App\Berita;
use App\Komponen;
use App\Sekolah;
use App\Nilai_instrumen;
use App\Nilai_akhir;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Pakta_integritas;
use App\Rapor_mutu;
use App\Jawaban;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class PageController extends Controller
{
    public function index(Request $request){
        $query = $request->route('query');
        $query = str_replace('-', '_', $query);
        if (method_exists($this, $query)) {
            return $this->{$query}($request);
        } else {
            abort(404);
        }
    }
    public function laporan($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'id_level_wilayah' => 1,
            'all_wilayah' => $all_wilayah,
        ];
        return view('page.'.$query)->with($params);
    }
    public function rekapitulasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'id_level_wilayah' => 1,
            'all_wilayah' => $all_wilayah,
        ];
        return view('page.'.$query)->with($params);
    }
    public function afirmasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'id_level_wilayah' => 1,
            'all_wilayah' => $all_wilayah,
        ];
        return view('page.'.$query)->with($params);
    }
    public function progres($request){
        $query = $request->route('query');
        $kode_wilayah = $request->route('kode_wilayah');
        $id_level_wilayah = $request->route('id_level_wilayah');
        $nama_wilayah = Wilayah::find($kode_wilayah);
        return view('page.'.$query)->with(['id_level_wilayah' => $id_level_wilayah, 'kode_wilayah' => $kode_wilayah, 'nama_wilayah' => $nama_wilayah]);
    }
    public function home($request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function berita($request){
        $query = $request->route('query');
        $berita = Berita::limit(10)->orderBy('created_at', 'DESC')->get();
        return view('page.'.$query)->with(['berita' => $berita]);
    }
    public function detil_berita($slug){
        $berita = Berita::whereSlug($slug)->first();
        return view('page.detil_berita')->with(['post' => $berita]);
    }
    public function rapor_mutu_sekolah($request){
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
        return view('page.rapor-mutu.'.$query)->with($params);
    }
    public function rapor_mutu_verifikasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $params = [
            'komponen' => Komponen::with(['all_nilai_komponen_verifikasi', 'aspek.all_nilai_aspek' => function($query){
                $query->whereNotNull('verifikator_id');
                $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
            }])->get(),
            'komponen_kinerja' => Komponen::whereIn('id', [1,2,3])->get(),
            'komponen_dampak' => Komponen::whereIn('id', [4,5])->get(),
            'all_wilayah' => $all_wilayah,
        ];
        return view('page.rapor-mutu.'.$query)->with($params);
    }
    public function rapor_mutu_afirmasi($request){
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
        return view('page.rapor-mutu.'.$query)->with($params);
    }
    public function rapor_mutu_komparasi($request){
        $query = $request->route('query');
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query){
            $query->where('id_level_wilayah', 1);
        })->orderBy('kode_wilayah')->get();
        $all_sekolah = Sekolah::query()->has('sekolah_sasaran')->with(['nilai_input' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_proses' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_output' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_outcome' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_impact' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_input_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_proses_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_output_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_outcome_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_impact_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_kinerja' => function($query){
            $query->whereNull('verifikator_id');
            $query->with('komponen');
        }, 'nilai_kinerja_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
            $query->with('komponen');
        }, 'nilai_dampak' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_dampak_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }, 'nilai_akhir' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_akhir_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }]/*)->where(function($query){
            if(request()->kode_wilayah){
                $query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
                });
            }
        }*/)->orderBy('provinsi_id')->orderBy('kabupaten_id')->paginate(10);
        //dd($all_sekolah);
        $params = [
            'komponen' => Komponen::with('all_nilai_komponen', 'aspek.all_nilai_aspek')->get(),
            'komponen_kinerja' => Komponen::whereIn('id', [1,2,3])->get(),
            'komponen_dampak' => Komponen::whereIn('id', [4,5])->get(),
            'all_wilayah' => $all_wilayah,
            'all_sekolah' => $all_sekolah,
        ];
        return view('page.rapor-mutu.'.$query)->with($params);
    }
    public function get_chart()
    {
        $all_komponen = Komponen::with('all_nilai_komponen')->get();
        foreach($all_komponen as $komponen){
            $nilai_komponen[] = number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $nama_komponen[] = $komponen->nama;
        }
        $wilayah = '_provinsi';
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
            $query->where('id_level_wilayah', 1);
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
        $komponen_kinerja = Komponen::whereIn('id', [1,2,3])->get();
        $komponen_dampak = Komponen::whereIn('id', [4,5])->get();
        foreach($komponen_kinerja as $kinerja){
            $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
            $nama_komponen_kinerja[] = $kinerja->nama;
        }
        foreach($komponen_dampak as $dampak){
            $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
            $nama_komponen_dampak[] = $dampak->nama;
        }
        $all_kinerja = [
            'nilai' => $nilai_komponen_kinerja,
            'nama' => $nama_komponen_kinerja,
            'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
        ];
        $all_dampak = [
            'nilai' => $nilai_komponen_dampak,
            'nama' => $nama_komponen_dampak,
            'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
        ];
        return response()->json(['nama_komponen' => $nama_komponen, 'nilai_komponen' => $nilai_komponen, 'counting' => $counting, 'all_kinerja' => $all_kinerja, 'all_dampak' => $all_dampak]);
    }
    public function get_chart_verifikasi()
    {
        $all_komponen = Komponen::with('all_nilai_komponen_verifikasi')->get();
        foreach($all_komponen as $komponen){
            $nilai_komponen[] = number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen[] = $komponen->nama;
        }
        $wilayah = '_provinsi';
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
            $query->where('id_level_wilayah', 1);
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
        $komponen_kinerja = Komponen::whereIn('id', [1,2,3])->get();
        $komponen_dampak = Komponen::whereIn('id', [4,5])->get();
        foreach($komponen_kinerja as $kinerja){
            $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen_kinerja[] = $kinerja->nama;
        }
        foreach($komponen_dampak as $dampak){
            $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen_dampak[] = $dampak->nama;
        }
        $all_kinerja = [
            'nilai' => $nilai_komponen_kinerja,
            'nama' => $nama_komponen_kinerja,
            'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
        ];
        $all_dampak = [
            'nilai' => $nilai_komponen_dampak,
            'nama' => $nama_komponen_dampak,
            'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
        ];
        return response()->json(['nama_komponen' => $nama_komponen, 'nilai_komponen' => $nilai_komponen, 'counting' => $counting, 'all_kinerja' => $all_kinerja, 'all_dampak' => $all_dampak]);
    }
    public function get_chart_komparasi()
    {
        $all_komponen = Komponen::with(['all_nilai_komponen', 'all_nilai_komponen_verifikasi'])->get();
        foreach($all_komponen as $komponen){
            $nilai_komponen[] = number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_verifikasi[] = number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nilai_komponen_komparasi[] = [
                'komponen' => $komponen->nama,
                'sekolah' => number_format($komponen->all_nilai_komponen->avg('total_nilai'),2),
                'verifikasi' => number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2),
            ];
            $nama_komponen[] = $komponen->nama;
        }
        $wilayah = '_provinsi';
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
            $query->where('id_level_wilayah', 1);
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
        $komponen_kinerja = Komponen::whereIn('id', [1,2,3])->get();
        $komponen_dampak = Komponen::whereIn('id', [4,5])->get();
        foreach($komponen_kinerja as $kinerja){
            $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_kinerja_verifikasi[] = number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen_kinerja[] = $kinerja->nama;
        }
        foreach($komponen_dampak as $dampak){
            $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
            $nilai_komponen_dampak_verifikasi[] = number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
            $nama_komponen_dampak[] = $dampak->nama;
        }
        $all_kinerja = [
            'nilai' => $nilai_komponen_kinerja,
            'nilai_verifikasi' => $nilai_komponen_kinerja_verifikasi,
            'nama' => $nama_komponen_kinerja,
            'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
        ];
        $all_dampak = [
            'nilai' => $nilai_komponen_dampak,
            'nilai_verifikasi' => $nilai_komponen_dampak_verifikasi,
            'nama' => $nama_komponen_dampak,
            'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
        ];
        return response()->json(['nama_komponen' => $nama_komponen, 'nilai_komponen' => $nilai_komponen, 'nilai_komponen_verifikasi' => $nilai_komponen_verifikasi, 'nilai_komponen_komparasi' => $nilai_komponen_komparasi, 'counting' => $counting, 'all_kinerja' => $all_kinerja, 'all_dampak' => $all_dampak]);
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
                $wilayah = '_kabupaten';
            } else {
                $wilayah = '_kecamatan';
            }
            
        } else{
            $wilayah = '_provinsi';
            $id_level_wilayah = 1;
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
        $nama_wilayah = Wilayah::whereRaw("trim(kode_wilayah) = '".request()->kode_wilayah."'")->first();
        $all_wilayah = Wilayah::whereHas('negara', function($query){
            $query->where('negara_id', 'ID');
        })->where(function($query) use ($id_level_wilayah){
            $query->where('id_level_wilayah', $id_level_wilayah);
            if(request()->kode_wilayah){
                //$query->where('mst_kode_wilayah', request()->kode_wilayah);
                $query->whereRaw("trim(mst_kode_wilayah) = '".request()->kode_wilayah."'");
            }
        })->withCount([$with, $with_coe, $with_instrumen, $with_rapor_mutu, $with_pakta_integritas, $with_waiting, $with_proses, $with_terima, $with_tolak])->orderBy('kode_wilayah')->get();
        /*
        })->withCount([$with, $with_coe])->with([$with => function($query){
            $query->with(['sekolah_sasaran' => function($query){
                $query->with(['terkirim', 'pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
            $query->with(['user.nilai_akhir']);
            $query->withCount('nilai_instrumen');
        }])->orderBy('kode_wilayah')->get();
        */
        //dd($all_wilayah);
        $params = [
            'id_level_wilayah' => $id_level_wilayah,
            'all_wilayah' => $all_wilayah,
            'count_smk' => $with.'_count',
            'count_smk_coe' => $with_coe.'_count',
            'count_instrumen' => $with_instrumen.'_count',
            'count_rapor_mutu' => $with_rapor_mutu.'_count',
            'count_pakta_integritas' => $with_pakta_integritas.'_count',
            'count_waiting' => $with_waiting.'_count',
            'count_proses' => $with_proses.'_count',
            'count_terima' => $with_terima.'_count',
            'count_tolak' => $with_tolak.'_count',
            'with' => $with,
            'next_level_wilayah' => $id_level_wilayah + 1,
            'nama_wilayah' => $nama_wilayah,
        ];
        //dd($params);
        return view('page.progres-wilayah')->with($params);
    }
    public function get_rapor_mutu($komponen_id){
        $params = [
            'komponen_id' => $komponen_id,
            'test' => 'test',
        ];
        return view('page.rapor_mutu')->with($params);
    }
    public function smk_coe(){
        return view('page.smk_coe');
    }
    public function hitung_rapor_mutu(Request $request){
        $query = $request->route('query');
        return view('page.dashboard.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function cetak_pakta(Request $request){
        $query = $request->route('query');
        return view('page.dashboard.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function verval(Request $request){
        $query = $request->route('query');
        return view('page.dashboard.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function verifikasi(Request $request){
        $query = $request->route('query');
        return view('page.dashboard.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function pengesahan(Request $request){
        $query = $request->route('query');
        return view('page.dashboard.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function login(Request $request){
        if(Auth::check()){
            return redirect()->route('page', ['query'=> 'rapor-mutu-sekolah']);
        }
        $query = $request->route('query');
        return view('page.dashboard.'.$query);
    }
    public function login_dashboard(Request $request)
    {
        $login = request()->input('email');
		$messages = [
			'email.required' => 'Email/NPSN/Nama Pengguna tidak boleh kosong',
		];
		$validator = Validator::make(request()->all(), [
			'email' => 'required|exists:users,username',
		 ],
		$messages
        );
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $auth = request()->merge([$fieldType => $login]);
        $credentials = $auth->only($fieldType, 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('page', ['query'=> 'rapor-mutu-sekolah']);
        }
    }
    public function reset_instrumen($npsn)
    {
        $sekolah = Sekolah::with(['user', 'sekolah_sasaran'])->has('smk_coe')->where('npsn', $npsn)->first();
        if($sekolah){
            if($sekolah->user){
                Jawaban::where('user_id', $sekolah->user->user_id)->delete();
                Nilai_instrumen::where('user_id', $sekolah->user->user_id)->delete();
                Nilai_akhir::where('user_id', $sekolah->user->user_id)->delete();
                Nilai_aspek::where('user_id', $sekolah->user->user_id)->delete();
                Nilai_komponen::where('user_id', $sekolah->user->user_id)->delete();
            }
            if($sekolah->sekolah_sasaran){
                Pakta_integritas::where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->delete();
                Rapor_mutu::where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->delete();
            }
        }
    }
    public function peta_mutu(Request $request){
        $query = $request->route('query');
        $id_level_wilayah = $request->id_level_wilayah;
        if($id_level_wilayah == 2){
            $api_url_map = route('api.peta.wilayah', ['kode_wilayah' => $request->kode_wilayah]);
            $json = File::get('geojson/'.$request->kode_wilayah.'.geojson');
            $json = json_decode($json);
            $latlng = $json->features[0]->geometry->coordinates[0][0];
            $leaflet = [
                'map_center_latitude' => $latlng[0][1],
                'map_center_longitude' => $latlng[0][0],
                'zoom_level' => 9,
            ];
        } else {
            $api_url_map = route('api.peta.index');
            $leaflet = [
                'map_center_latitude' => config('leaflet.map_center_latitude'),
                'map_center_longitude' => config('leaflet.map_center_longitude'),
                'zoom_level' => config('leaflet.zoom_level'),
            ];
        }
        return view('page.'.$query)->with(['id_level_wilayah' => ($id_level_wilayah) ? $id_level_wilayah : 1, 'leaflet' => $leaflet, 'api_url_map' => $api_url_map]);
    }
    public function pencarian(Request $request){
        $query = $request->route('query');
        return view('page.'.$query)->with(['id_level_wilayah' => 1]);
    }
    public function kebijakan($request)
    {
        $query = $request->route('query');
        return view('page.'.$query);
    }
}
