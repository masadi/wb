<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Sekolah;
use App\User;
use App\HelperModel;
use App\Komponen;
use App\Wilayah;
use Validator;
class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data = Sekolah::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%')
                ->orWhere('npsn', 'LIKE', '%' . request()->q . '%')
                ->orWhere('kabupaten', 'LIKE', '%' . request()->q . '%')
                ->orWhere('provinsi', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        if(!$user){
            return redirect('login');
        }
        $data = NULL;
        if($user->hasRole('sekolah')){
            $data = HelperModel::rapor_mutu($request->user_id);
        } elseif($user->hasRole('penjamin_mutu')){
            $data = [
                'jml_sekolah_sasaran' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                })->count(),
                'jml_sekolah_sasaran_instrumen' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->has('pakta_integritas');
                })->count(),
                'jml_sekolah_sasaran_no_instrumen' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->doesntHave('pakta_integritas');
                })->count(),
                'jml_sekolah_sasaran_verval' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->has('rapor_mutu');
                })->count(),
            ];
        }
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::with(['user'])->find($id);
        $sekolah->nama = $request->nama;
        $sekolah->nama_kepsek = $request->nama_kepsek;
        $sekolah->nip_kepsek = $request->nip_kepsek;
        $sekolah->nama_pengawas = $request->nama_pengawas;
        $sekolah->nip_pengawas = $request->nip_pengawas;
        $sekolah->save();
        $user = $sekolah->user;
        $user->name = $request->nama;
        $user->save();
        return response()->json(['message' => 'Data Sekolah berhasil diperbaharui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Sekolah::find($id);
        $data->delete();
        return response()->json(['message' => 'Data Sekolah berhasil dihapus']);
    }
    public function pencarian(Request $request){
        $messages = [
            'npsn.required'	=> 'NPSN tidak boleh kosong',
            'npsn.exists'	=> 'NPSN tidak ditemukan di database',
        ];
        $validator = Validator::make(request()->all(), [
            'npsn' => 'required|exists:sekolah',
            //'file' => 'required',
        ],
        $messages
        )->validate();
        /*$sekolah = Sekolah::with(['user'])->where('npsn', $request->npsn)->first();
        $komponen = Komponen::with(['nilai_komponen' => function($query) use ($sekolah){
            if($sekolah->user){
                $query->where('user_id', $sekolah->user->user_id);
            }
        }, 'aspek' => function($query) use ($sekolah){
            $query->with(['atribut' => function($query) use ($sekolah){
                $query->with(['indikator' => function($query) use ($sekolah){
                    $query->with(['atribut.aspek.komponen', 'instrumen' => function($query) use ($sekolah){
                        $query->where('urut', 0);
                    }]);
                }]);
            }, 'nilai_aspek' => function($query) use ($sekolah){
                $query->where('user_id', $sekolah->user->user_id);
            }, 'instrumen' => function($query) use ($sekolah){
                $query->where('urut', 0);
                $query->with(['nilai_instrumen' => function($query) use ($sekolah){
                    $query->where('user_id', $sekolah->user->user_id);
                    $query->whereNull('verifikator_id');
                }]);
            }]);
        }])->get();*/
        $sekolah = Sekolah::with(['jurusan_sp', 'smk_coe'])->withCount(['guru', 'tendik', 'anggota_rombel', 'anggota_rombel as kelas_10_count' => function (Builder $query) {
            $query->where('tingkat', 10);
        }, 'anggota_rombel as kelas_11_count' => function (Builder $query) {
            $query->where('tingkat', 11);
        }, 'anggota_rombel as kelas_12_count' => function (Builder $query) {
            $query->where('tingkat', 12);
        }, 'anggota_rombel as kelas_13_count' => function (Builder $query) {
            $query->where('tingkat', 13);
        }])->where('npsn', $request->npsn)->first();
        $callback = function($query) use ($sekolah){
            $query->whereHas('user.sekolah', function($query) use ($sekolah){
                $query->where('sekolah_id', $sekolah->sekolah_id);
            });
        };
        $all_callback = function($query) use ($sekolah){
            $query->whereHas('user.sekolah', function($query) use ($sekolah){
                $query->where('sekolah_id', '<>', $sekolah->sekolah_id);
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
        $komponen_kinerja = Komponen::with(['all_nilai_komponen' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [1,2,3])->get();
        $komponen_dampak = Komponen::with(['all_nilai_komponen' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [4,5])->get();
        foreach($komponen_kinerja as $kinerja){
            $bobot_kinerja = 0;
            foreach($kinerja->aspek as $aspek_kinerja){
                $bobot_kinerja += $aspek_kinerja->bobot;
            }
            $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
            $bintang_komponen_kinerja[] 	= HelperModel::bintang_icon(number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            $nama_komponen_kinerja[] = strtolower($kinerja->nama);
        }
        foreach($komponen_dampak as $dampak){
            $bobot_dampak = 0;
            foreach($dampak->aspek as $aspek_dampak){
                $bobot_dampak += $aspek_dampak->bobot;
            }
            $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
            //(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2) * $bobot_dampak) / 100;
            $bintang_komponen_dampak[] 	= HelperModel::bintang_icon(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2), 'warning');
            $nama_komponen_dampak[] = strtolower($dampak->nama);
        }
        $group_komponen = [
            'all_kinerja' => [
                'nilai' => $nilai_komponen_kinerja,
                'nama' => $nama_komponen_kinerja,
                'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
                'nilai_scatter' => HelperModel::nilai_satuan(number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2)),
                'bintang' => $bintang_komponen_kinerja,
            ],
            'all_dampak' => [
                'nilai' => $nilai_komponen_dampak,
                'nama' => $nama_komponen_dampak,
                'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
                'nilai_scatter' => HelperModel::nilai_satuan(number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2)),
                'bintang' => $bintang_komponen_dampak,
            ],
        ];
        $all_sekolah = [];
        if($request->sekolah_id){
            $all_sekolah = Sekolah::select('sekolah_id', 'nama', 'provinsi', 'kabupaten', 'kecamatan')->with(['nilai_kinerja' => function($query){
                $query->whereNull('verifikator_id');
            }, 'nilai_dampak' => function($query){
                $query->whereNull('verifikator_id');
            }])->where(function($query) use ($sekolah){
                $query->where('sekolah_id', '<>', $sekolah->sekolah_id);
                if(!$request->all_provinsi){
                    $query->whereRaw("trim(provinsi_id) = '".request()->provinsi_id."'");
                }
            })->has('smk_coe')->has('nilai_kinerja')->get();
        }
        $wilayah = Wilayah::whereRaw("trim(kode_wilayah) = '".request()->provinsi_id."'")->first();
        $nama_wilayah = ($wilayah) ? $wilayah->nama : '';
        $respone = [
            'nama_wilayah' => $nama_wilayah,
            'rerata_komponen_kinerja' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
            'rerata_komponen_dampak' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
            'group_komponen' => $group_komponen,
            'all_sekolah' => $all_sekolah,
            'sekolah' => $sekolah,
            'nilai_komponen_kotak' => $nilai_komponen, 
            'nilai_komponen' => $nilai_komponen_chart, 
            'nama_komponen' => $nama_komponen_chart,
            'komponen_kinerja' => $komponen_kinerja,
            'komponen_dampak' => $komponen_dampak,
        ];
        $user = auth()->user();
        return response()->json([
            'body' => view('page.hasil-pencarian', compact('sekolah', 'komponen_kinerja', 'komponen_dampak', 'all_komponen', 'user'))->render(),
        ]);
    }
}
