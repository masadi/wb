<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;
use App\Sekolah;
use App\User;
use App\Komponen;
use App\Instrumen;
use App\HelperModel;
use App\Nilai_akhir;
use App\Nilai_instrumen;
use App\Pakta_integritas;
use App\Verval;
use App\Verifikasi;
use App\Jenis_rapor;
use App\Status_rapor;
use App\Rapor_mutu;
use App\Wilayah;
use App\Isi_standar;
use Carbon\Carbon;
use PDF;
class RaporController extends Controller
{
    public function snp(Request $request){
        $user = User::with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_standar' => function($query){
            $query->where('standar_id', 1);
        }])->find($request->user_id);
        $komponen = Isi_standar::where('standar_id', 1)->with(['instrumen_standar.instrumen' => function($query){
            $query->with(['jawaban', 'breakdown.question.answer.nilai_answer', 'nilai_instrumen']);
        }, 'nilai_akhir' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }])->whereNull('isi_standar_id')->get();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            'rapor_mutu' => $komponen,//HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
    public function renstra(Request $request){
        $user = User::with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_standar' => function($query){
            $query->where('standar_id', 4);
        }])->find($request->user_id);
        $komponen = Isi_standar::where('standar_id', 4)->with(['nilai_akhir' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }])->whereNull('isi_standar_id')->get();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            'rapor_mutu' => [],//HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
    public function bsc(Request $request){
        $user = User::with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_standar' => function($query){
            $query->where('standar_id', 2);
        }])->find($request->user_id);
        $komponen = Isi_standar::where('standar_id', 2)->with(['breakdown', 'sub_isi_standar' => function($query){
            $query->with(['sub_isi_standar']);
        }, 'nilai_akhir' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }])->whereNull('isi_standar_id')->get();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            'rapor_mutu' => [],//HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
    public function link_match(Request $request){
        $user = User::with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_standar' => function($query){
            $query->where('standar_id', 3);
        }])->find($request->user_id);
        $komponen = Isi_standar::where('standar_id', 3)->with(['nilai_akhir' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }])->whereNull('isi_standar_id')->get();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            'rapor_mutu' => [],//HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
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
        /*$user = User::withCount(['nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }])->with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_akhir' => function($query){
            $query->whereNull('verifikator_id');
        }])->find($request->user_id);*/
        $user = User::with(['sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
        }, 'nilai_akhir' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_akhir_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->whereDate('updated_at', '>=', '2020-11-08');
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
        /*$pakta_integritas = NULL;
        if($user->sekolah->sekolah_sasaran){
            $pakta_integritas = $user->sekolah->sekolah_sasaran->pakta_integritas;
        }*/
        $verval = Verval::where('sekolah_id', $user->sekolah_id)->first();
        $verifikasi = Verifikasi::where('sekolah_id', $user->sekolah_id)->first();
        $respone = [
            'status' => 'success', 
            'detil_user' => $user,
            'data' => $komponen, 
            /*'rapor' => [
                'jml_instrumen' => $jml_instrumen, 
                'kuisioner' => ($kuisioner) ? HelperModel::TanggalIndo($kuisioner->updated_at) : NULL,
                'hitung' => ($hitung) ? HelperModel::TanggalIndo($hitung->updated_at) : NULL,
                'pakta_integritas' => ($pakta_integritas) ? HelperModel::TanggalIndo($pakta_integritas->updated_at) : NULL,
                'pakta' => ($pakta_integritas) ? HelperModel::TanggalIndo($pakta_integritas->updated_at) : NULL,
                'verval' => ($verval) ? HelperModel::TanggalIndo($verval->updated_at) : NULL,
                'verifikasi' => ($verifikasi) ? HelperModel::TanggalIndo($verifikasi->created_at) : NULL,
                'pengesahan' => ($verifikasi) ? ($verifikasi->verifikasi) ? HelperModel::TanggalIndo($pengesahan->updated_at) : NULL : NULL,
            ],*/
            'rapor_mutu' => HelperModel::rapor_mutu($request->user_id),
        ];
        return response()->json($respone);
    }
    public function pakta(Request $request){
        $respone = [
            'user' => User::with(['sekolah' => function($query){
                $query->with(['smk_coe', 'sekolah_sasaran.pakta_integritas']);
            }, 'nilai_akhir' => function($query){
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
            /*$jenis = Jenis_rapor::where('jenis', 'pakta')->first();
            $status = Status_rapor::where('status', 'terkirim')->first();
            $kirim_verval = Rapor_mutu::updateOrCreate(
                [
                    'jenis_rapor_id' => $jenis->id,
                    'status_rapor_id' => $status->id,
                    'sekolah_sasaran_id' => $pakta_integritas->sekolah_sasaran_id
                ]
            );*/
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
    public function sekolah(Request $request){
        if($request->sekolah_id){
            $sekolah = Sekolah::with(['jurusan_sp'])->withCount(['guru', 'tendik', 'anggota_rombel', 'anggota_rombel as kelas_10_count' => function (Builder $query) {
                $query->where('tingkat', 10);
            }, 'anggota_rombel as kelas_11_count' => function (Builder $query) {
                $query->where('tingkat', 11);
            }, 'anggota_rombel as kelas_12_count' => function (Builder $query) {
                $query->where('tingkat', 12);
            }, 'anggota_rombel as kelas_13_count' => function (Builder $query) {
                $query->where('tingkat', 13);
            }])->find($request->sekolah_id);
            $callback = function($query) use ($request){
                $query->whereHas('user.sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
            };
            $all_callback = function($query) use ($request){
                $query->whereHas('user.sekolah', function($query) use ($request){
                    $query->where('sekolah_id', '<>', $request->sekolah_id);
                });
            };
            $all_komponen = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->get();
            $nilai_komponen = [];
            $nilai_komponen_chart = [];
            $nama_komponen_chart = [];
            $nilai_komponen_komparasi = [];
            foreach($all_komponen as $komponen){
                $record_komponen= [];
                $record_komponen['nilai'] 	= number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
                $record_komponen['nilai_verifikasi'] 	= number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
                $nilai_komponen_komparasi[] = [
                    'komponen' => $komponen->nama,
                    'sekolah' => number_format($komponen->all_nilai_komponen->avg('total_nilai'),2),
                    'verifikasi' => number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2),
                ];
                $record_komponen['bintang'] 	= HelperModel::bintang_icon(number_format($komponen->all_nilai_komponen->avg('total_nilai'),2), 'warning');
                foreach($komponen->aspek as $aspek){
                    $record_komponen['nilai_aspek'][strtolower(HelperModel::clean($aspek->nama))] = number_format($aspek->all_nilai_aspek->avg('total_nilai'),2);
                }
                $nilai_komponen[] = $record_komponen;
                $record_chart = [];
                $nilai_komponen_chart[] = number_format($komponen->all_nilai_komponen->avg('total_nilai'),2);
                $nilai_komponen_verifikasi[] = number_format($komponen->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
                $nama_komponen_chart[] 	= $komponen->nama;
            }
            $komponen_kinerja = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [1,2,3])->get();
            $komponen_dampak = Komponen::with(['all_nilai_komponen' => $callback, 'all_nilai_komponen_verifikasi' => $callback, 'aspek.all_nilai_aspek' => $callback])->whereIn('id', [4,5])->get();
            foreach($komponen_kinerja as $kinerja){
                $bobot_kinerja = 0;
                foreach($kinerja->aspek as $aspek_kinerja){
                    $bobot_kinerja += $aspek_kinerja->bobot;
                }
                $nilai_komponen_kinerja[] = number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2);
                $nilai_komponen_kinerja_verifikasi[] = number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
                $bintang_komponen_kinerja[] 	= HelperModel::bintang_icon(number_format($kinerja->all_nilai_komponen->avg('total_nilai'),2), 'warning');
                $bintang_komponen_kinerja_verifikasi[] 	= HelperModel::bintang_icon(number_format($kinerja->all_nilai_komponen_verifikasi->avg('total_nilai'),2), 'warning');
                $nama_komponen_kinerja[] = strtolower($kinerja->nama);
            }
            foreach($komponen_dampak as $dampak){
                $bobot_dampak = 0;
                foreach($dampak->aspek as $aspek_dampak){
                    $bobot_dampak += $aspek_dampak->bobot;
                }
                $nilai_komponen_dampak[] = number_format($dampak->all_nilai_komponen->avg('total_nilai'),2);
                $nilai_komponen_dampak_verifikasi[] = number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2);
                $bintang_komponen_dampak[] 	= HelperModel::bintang_icon(number_format($dampak->all_nilai_komponen->avg('total_nilai'),2), 'warning');
                $bintang_komponen_dampak_verifikasi[] 	= HelperModel::bintang_icon(number_format($dampak->all_nilai_komponen_verifikasi->avg('total_nilai'),2), 'warning');
                $nama_komponen_dampak[] = strtolower($dampak->nama);
            }
            $group_komponen = [
                'all_kinerja' => [
                    'nilai' => $nilai_komponen_kinerja,
                    'nilai_verifikasi' => $nilai_komponen_kinerja_verifikasi,
                    'nama' => $nama_komponen_kinerja,
                    'rerata' => number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2),
                    'nilai_scatter' => HelperModel::nilai_satuan(number_format(array_sum($nilai_komponen_kinerja) / count($nilai_komponen_kinerja),2)),
                    'bintang' => $bintang_komponen_kinerja,
                    'bintang_verifikasi' => $bintang_komponen_kinerja_verifikasi,
                ],
                'all_dampak' => [
                    'nilai' => $nilai_komponen_dampak,
                    'nilai_verifikasi' => $nilai_komponen_dampak_verifikasi,
                    'nama' => $nama_komponen_dampak,
                    'rerata' => number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2),
                    'nilai_scatter' => HelperModel::nilai_satuan(number_format(array_sum($nilai_komponen_dampak) / count($nilai_komponen_dampak),2)),
                    'bintang' => $bintang_komponen_dampak,
                    'bintang_verifikasi' => $bintang_komponen_dampak_verifikasi,
                ],
            ];
            $all_sekolah = [];
            if($request->sekolah_id){
                $all_sekolah = Sekolah::select('sekolah_id', 'nama', 'provinsi', 'kabupaten', 'kecamatan')->with(['nilai_kinerja' => function($query){
                    $query->whereNull('verifikator_id');
                }, 'nilai_dampak' => function($query){
                    $query->whereNull('verifikator_id');
                }])->where(function($query) use ($request){
                    $query->where('sekolah_id', '<>', $request->sekolah_id);
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
                'nilai_komponen_verifikasi' => $nilai_komponen_verifikasi, 
                'nilai_komponen_komparasi' => $nilai_komponen_komparasi,
                'nama_komponen' => $nama_komponen_chart,
            ];
        } else {
            $respone = [
                'sekolah' => NULL,
                'text' => 'Rapor Mutu terkirim',
                'icon' => 'success',
            ];
        }
        
        return response()->json($respone);
    }
    public function komparasi(Request $request){
        $query = Sekolah::query()->has('sekolah_sasaran')->with(['nilai_input' => function($query){
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
        }*/)->orderBy('provinsi_id')->orderBy('kabupaten_id');
        return DataTables::eloquent($query)
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
        ->addColumn('nilai_kinerja_verifikasi', function ($item) {
            $links = ($item->nilai_kinerja_verifikasi) ? number_format($item->nilai_kinerja_verifikasi->avg('total_nilai'),2,'.','.') : 0;
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
        ->addColumn('nilai_dampak_verifikasi', function ($item) {
            $links = ($item->nilai_dampak_verifikasi) ? number_format($item->nilai_dampak_verifikasi->avg('total_nilai'),2,'.','.') : 0;
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
        ->addColumn('nilai_input_verifikasi', function ($item) {
            $links = ($item->nilai_input_verifikasi) ? $item->nilai_input_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_proses_verifikasi', function ($item) {
            $links = ($item->nilai_proses_verifikasi) ? $item->nilai_proses_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_output_verifikasi', function ($item) {
            $links = ($item->nilai_output_verifikasi) ? $item->nilai_output_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('terendah_verifikasi', function ($item) {
            $keyed = [];
            if($item->nilai_kinerja_verifikasi){
                $nilai_terendah_verifikasi = $item->nilai_kinerja_verifikasi->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed = $nilai_terendah_verifikasi->keyBy('nilai')->toArray();
            }
            $links = ($keyed) ? ($keyed[$item->nilai_kinerja_verifikasi->min('total_nilai')]) ? $keyed[$item->nilai_kinerja_verifikasi->min('total_nilai')]['nama'] : '-' : '-';
            return $links;
        })
        ->addColumn('tertinggi_verifikasi', function ($item) {
            $keyed = [];
            if($item->nilai_kinerja_verifikasi){
                $nilai_tertinggi_verifikasi = $item->nilai_kinerja_verifikasi->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed = $nilai_tertinggi_verifikasi->keyBy('nilai')->toArray();
            }
            $links = ($keyed) ? ($keyed[$item->nilai_kinerja_verifikasi->max('total_nilai')]) ? $keyed[$item->nilai_kinerja_verifikasi->max('total_nilai')]['nama'] : '-' : '-';
            return $links;
        })
        ->addColumn('nilai_outcome_verifikasi', function ($item) {
            $links = ($item->nilai_outcome_verifikasi) ? $item->nilai_outcome_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_impact_verifikasi', function ($item) {
            $links = ($item->nilai_impact_verifikasi) ? $item->nilai_impact_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('nilai_akhir_verifikasi', function ($item) {
            $links = ($item->nilai_akhir_verifikasi) ? $item->nilai_akhir_verifikasi->total_nilai : 0;
            return $links;
        })
        ->addColumn('satu', function ($item) {
            $links = 'satu';
            return $links;
        })
        ->addColumn('dua', function ($item) {
            $links = 'dua';
            return $links;
        })
        ->addColumn('tiga', function ($item) {
            $links = 'tiga';
            return $links;
        })
        ->rawColumns(['nama', 'npsn', 'nilai_input', 'nilai_proses', 'nilai_output', 'nilai_kinerja', 'terendah', 'tertinggi', 'afirmasi', 'nilai_outcome', 'nilai_impact', 'nilai_dampak', 'nilai_akhir', 'predikat', 'nilai_input_verifikasi', 'nilai_proses_verifikasi', 'nilai_output_verifikasi', 'terendah_verifikasi', 'tertinggi_verifikasi', 'nilai_outcome_verifikasi', 'nilai_impact_verifikasi', 'nilai_akhir_verifikasi'])
        ->make(true);
    }
}
