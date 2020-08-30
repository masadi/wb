<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Komponen;
use App\Instrumen;
use App\HelperModel;
use App\Nilai_akhir;
use App\Nilai_instrumen;
use App\Pakta_integritas;
use App\Verval;
use App\Verifikasi;
use Carbon\Carbon;
use PDF;
class RaporController extends Controller
{
    public function index(Request $request){
        $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'aspek' => function($query) use ($request){
            $query->with(['atribut' => function($query) use ($request){
                $query->with(['indikator' => function($query) use ($request){
                    $query->with(['atribut.aspek.komponen', 'instrumen' => function($query) use ($request){
                        $query->where('urut', 0);
                    }]);
                }]);
            }, 'nilai_aspek' => function($query) use ($request){
                $query->where('user_id', $request->user_id);
            }, 'instrumen' => function($query) use ($request){
                $query->where('urut', 0);
                $query->with(['nilai_instrumen' => function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                    $query->whereNull('verifikator_id');
                }]);
            }]);
        }])->get();
        $user = User::withCount(['nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }])->with(['sekolah.sekolah_sasaran.pakta_integritas', 'nilai_akhir' => function($query){
            $query->whereNull('verifikator_id');
        }])->find($request->user_id);
        /*$output_aspek = $komponen->pluck('aspek')->flatten();
        $output_atribut = $output_aspek->pluck('atribut')->flatten();
        $output_indikator = $output_atribut->pluck('indikator')->flatten();
        $output_instrumen = $output_indikator->pluck('instrumen')->flatten();
        $output_nilai_instrumen = $output_instrumen->pluck('nilai_instrumen')->flatten()->filter();
        $output_nilai_instrumen = $output_nilai_instrumen->whereNull('verifikator_id')->flatten()->filter();
        $output_nilai_instrumen = $output_nilai_instrumen->where('user_id', $request->user_id)->sortByDesc('updated_at');
        $kuisioner = $output_nilai_instrumen->first();*/
        $jml_instrumen = Instrumen::where('urut', 0)->count();
        $kuisioner = Nilai_instrumen::where(function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        })->orderBy('updated_at', 'DESC')->first();
        $hitung = Nilai_akhir::where('user_id', $request->user_id)->first();
        //$pakta_integritas = Pakta_integritas::where('user_id', $request->user_id)->first();
        $pakta_integritas = NULL;
        if($user->sekolah->sekolah_sasaran){
            $pakta_integritas = $user->sekolah->sekolah_sasaran->pakta_integritas;
        }
        $verval = Verval::where('sekolah_id', $user->sekolah_id)->first();
        $verifikasi = Verifikasi::where('sekolah_id', $user->sekolah_id)->first();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            'rapor' => [
                'jml_instrumen' => $jml_instrumen, 
                'kuisioner' => ($kuisioner) ? HelperModel::TanggalIndo($kuisioner->updated_at) : NULL,
                'hitung' => ($hitung) ? HelperModel::TanggalIndo($hitung->updated_at) : NULL,
                'pakta_integritas' => ($pakta_integritas) ? HelperModel::TanggalIndo($pakta_integritas->updated_at) : NULL,
                'pakta' => ($pakta_integritas) ? HelperModel::TanggalIndo($pakta_integritas->updated_at) : NULL,
                'verval' => ($verval) ? HelperModel::TanggalIndo($verval->updated_at) : NULL,
                'verifikasi' => ($verifikasi) ? HelperModel::TanggalIndo($verifikasi->created_at) : NULL,
                'pengesahan' => ($verifikasi) ? ($verifikasi->verifikasi) ? HelperModel::TanggalIndo($pengesahan->updated_at) : NULL : NULL,
            ],
            'rapor_mutu' => HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
    public function pakta(Request $request){
        $respone = [
            'user' => User::with(['sekolah.sekolah_sasaran.pakta_integritas'])->with(['nilai_akhir' => function($query){
                $query->whereNull('verifikator_id');
            }])->find($request->user_id),
            'tahun_pendataan' => HelperModel::tahun_pendataan(),
        ];
        return response()->json($respone);
    }
    public function pra_cetak_pakta(Request $request){
        $user = User::with(['sekolah.sekolah_sasaran'])->find($request->user_id);
        Pakta_integritas::updateOrCreate([
            'sekolah_sasaran_id' => $user->sekolah->sekolah_sasaran->sekolah_sasaran_id,
        ]);
    }
    public function cetak_pakta(Request $request){
        $user = User::with(['sekolah', 'nilai_akhir'])->find($request->user_id);
        /*$data['sekolah'] = $user->sekolah;
        $data['now'] = HelperModel::TanggalIndo(Carbon::now());
        $data['tahun_pendataan'] = HelperModel::tahun_pendataan();*/
        $data = [
            'sekolah' => $user->sekolah,
            'now' => HelperModel::TanggalIndo(Carbon::now()),
            'tahun_pendataan' => HelperModel::tahun_pendataan(),
            'nilai_rapor_mutu' => ($user->nilai_akhir) ? $user->nilai_akhir->nilai : 0,
            'predikat_sekolah' => ($user->nilai_akhir) ? $user->nilai_akhir->predikat : '-',
            'nilai_komponen' => $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
                $query->where('user_id', $request->user_id);
            }, 'aspek' => function($query) use ($request){
                $query->with(['atribut' => function($query) use ($request){
                    $query->with(['indikator' => function($query) use ($request){
                        $query->with(['atribut.aspek.komponen', 'instrumen' => function($query) use ($request){
                            $query->where('urut', 0);
                        }]);
                    }]);
                }, 'nilai_aspek' => function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                }, 'instrumen' => function($query) use ($request){
                    $query->where('urut', 0);
                    $query->with(['nilai_instrumen' => function($query) use ($request){
                        $query->where('user_id', $request->user_id);
                        $query->whereNull('verifikator_id');
                    }]);
                }]);
            }])->get(),
        ];
        //return view('cetak.pakta_integritas', $data);
        $pdf = PDF::loadView('cetak.pakta_integritas', $data);
        return $pdf->stream('pakta-integritas.pdf');
		//return $pdf->download('pakta-integritas.pdf');
    }
    public function batal_pakta(Request $request){
        $delete = Pakta_integritas::whereHas('sekolah_sasaran.sekolah.user', function($query) use ($request){
            $query->where('user_id', $request->user_id);
        })->first();
        if($delete){
            if($delete->delete()){
                $respone = [
                    'title' => 'Berhasil',
                    'text' => 'Pakta Integritas dibatalkan',
                    'icon' => 'success',
                ];
            } else {
                $respone = [
                    'title' => 'Gagal',
                    'text' => 'Pakta Integritas gagal dibatalkan. Silahkan coba beberapa saat lagi!',
                    'icon' => 'error',
                ];
            }
        } else {
            $respone = [
                'title' => 'Gagal',
                'text' => 'Pakta Integritas tidak ditemukan di database!',
                'icon' => 'error',
            ];
        }
        return response()->json($respone);
    }
    public function kirim(Request $request){
        $pakta_integritas = Pakta_integritas::find($request->pakta_integritas_id);
        $pakta_integritas->terkirim = 1;
        if($pakta_integritas->save()){
            $respone = [
                'title' => 'Berhasil',
                'text' => 'Rapor Mutu terkirim',
                'icon' => 'success',
            ];
        } else {
            $respone = [
                'title' => 'Gagal',
                'text' => 'Rapor Mutu gagal dikirim. Silahkan coba beberapa saat lagi!',
                'icon' => 'error',
            ];
        }
        return response()->json($respone);
    }
    public function cetak_rapor(Request $request){
        $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'aspek' => function($query) use ($request){
            $query->with(['atribut' => function($query) use ($request){
                $query->with(['indikator' => function($query) use ($request){
                    $query->with(['atribut.aspek.komponen', 'instrumen' => function($query) use ($request){
                        $query->where('urut', 0);
                    }]);
                }]);
            }, 'nilai_aspek' => function($query) use ($request){
                $query->where('user_id', $request->user_id);
            }, 'instrumen' => function($query) use ($request){
                $query->where('urut', 0);
                $query->with(['nilai_instrumen' => function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                    $query->whereNull('verifikator_id');
                }]);
            }]);
        }])->get();
        $user = User::withCount(['nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }])->with(['sekolah.sekolah_sasaran.pakta_integritas', 'nilai_akhir' => function($query){
            $query->whereNull('verifikator_id');
        }])->find($request->user_id);
        $hitung = Nilai_akhir::where('user_id', $request->user_id)->first();
        dd($request->all());
    }
}
