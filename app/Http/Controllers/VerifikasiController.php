<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Aspek;
use App\Instrumen;
use App\Nilai_instrumen;
use App\Jawaban;
use App\Sekolah;
use App\HelperModel;
use App\Jenis_rapor;
use App\Status_rapor;
use App\Rapor_mutu;
use App\Jenis_berita_acara;
use App\Berita_acara;
use App\Jenis_dokumen;
use App\Dokumen;
use App\Wilayah;
use App\User;
use App\Nilai_dokumen;
use Validator;
use Carbon\Carbon;
use PDF;
class VerifikasiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function verifikasi_front(Request $request){
        if ($request->isMethod('post')) {
        } else {
            $all_wilayah = Wilayah::whereHas('negara', function($query){
                $query->where('negara_id', 'ID');
            })->where(function($query){
                $query->where('id_level_wilayah', 1);
            })->orderBy('kode_wilayah')->get();
            return view('page.verifikasi', compact('all_wilayah'));
        }
    }
    public function verifikasi_instrumen(Request $request){
        if ($request->isMethod('post')) {
        } else {
            $all_wilayah = Wilayah::whereHas('negara', function($query){
                $query->where('negara_id', 'ID');
            })->where(function($query){
                $query->where('id_level_wilayah', 1);
            })->orderBy('kode_wilayah')->get();
            return view('page.verifikasi.instrumen', compact('all_wilayah'));
        }
    }
    public function validasi_token(Request $request){
        $user = User::where('token', $request->token)->first();
        $sekolah = NULL;
        if($user){
            $sekolah = Sekolah::whereHas('sekolah_sasaran', function($query) use ($user){
                $query->where('verifikator_id', $user->user_id);
            })->get();
        }
        return response()->json([
            'body' => view('page.verifikasi.sekolah', compact('sekolah', 'user'))->render(),
        ]);
    }
    public function verifikasi_sekolah(Request $request){
        $sekolah = Sekolah::with(['user', 'sekolah_sasaran' => function($query){
            $query->with(['verifikator', 'sektor']);
        }])->find($request->sekolah_id);
        if($request->action){
            foreach($request->instrumen_id as $instrumen_id){
                foreach($request->ada[$instrumen_id] as $key => $ada){
                    Nilai_dokumen::updateOrCreate(
                        [
                            'sekolah_sasaran_id' => $request->sekolah_sasaran_id,
                            'instrumen_id' => $instrumen_id,
                            'dok_id' => $key,
                        ],
                        [
                            'ada' => $ada,
                            'keterangan' => $request->keterangan[$instrumen_id][$key]
                        ]
                    );
                    $all_keterangan[$instrumen_id][] = $request->keterangan[$instrumen_id][$key];
                }
                $keterangan = array_filter($all_keterangan[$instrumen_id]);
                $save = Nilai_instrumen::updateOrCreate(
                    [
                        'user_id' => $sekolah->user->user_id,
                        'verifikator_id' => $request->verifikator_id,
                        'instrumen_id' => $instrumen_id,
                    ],
                    [
                        'nilai' => $request->verifikasi[$instrumen_id],
                        'predikat' => HelperModel::predikat($request->verifikasi[$instrumen_id]),
                        'keterangan' => ($keterangan) ? implode('. ', $keterangan) : NULL,
                    ]
                );
                Jawaban::updateOrCreate(
                    [
                        'user_id' => $sekolah->user->user_id,
                        'verifikator_id' => $request->verifikator_id,
                        'komponen_id' => $request->komponen_id[$instrumen_id],
                        'aspek_id' => $request->aspek_id[$instrumen_id],
                        'atribut_id' => $request->atribut_id[$instrumen_id],
                        'indikator_id' => $request->indikator_id[$instrumen_id],
                        'instrumen_id' => $instrumen_id,
                    ],
                    [
                        'nilai' => $request->verifikasi[$instrumen_id],
                    ]
                );
                HelperModel::generate_nilai($sekolah->user->user_id, $request->verifikator_id);
            }
            $respone = [
                'title' => 'Berhasil',
                'text' => 'Hasil verifikasi dan validasi berhasil disimpan!',
                'icon' => 'success',
                'sekolah_id' => $request->sekolah_id,
            ];
            return response()->json($respone);
        }
        $callback = function($query) use ($request){
            $query->where('verifikator_id', $request->verifikator_id);
            $query->whereHas('user', function($query) use ($request){
                $query->whereHas('sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('sekolah_id', $request->sekolah_id);
            });
        };
        $instrumens = Instrumen::with(['jawaban' => $callback, 'subs' => function($query){
            $query->orderBy('urut', 'DESC');
        }, 'telaah_dokumen.nilai_dokumen' => function($query) use ($sekolah){
            $query->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id);
        }])->withCount('telaah_dokumen')->where('urut', 0)->get();
        /*
        $callback = function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->where('verifikator_id', $request->verifikator_id);
            $query->whereHas('user', function($query) use ($request){
                $query->whereHas('sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('sekolah_id', $request->sekolah_id);
            });
        };
        return Instrumen::with(['jawaban' => $callback, 'nilai_instrumen' => $callback, 'subs'])->find($request->instrumen_id);
        */
        return response()->json([
            'body' => view('page.verifikasi.form', compact('sekolah', 'instrumens'))->render(),
        ]);
    }
    public function get_komponen(Request $request){
        $all_data = Komponen::get();
		if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->id;
                $record['text'] 	= $data->nama;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data komponen';
			$output['result'][] = $record;
        }
        $output['user_id'] = $request->user_id;
        return response()->json($output);
    }
    public function get_aspek(Request $request){
        $all_data = Aspek::where('komponen_id', $request->komponen_id)->get();
        if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->id;
                $record['text'] 	= $data->nama;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data aspek';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
    public function get_instrumen(Request $request){
        if($request->verifikator_id){
            $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
                $query->whereHas('user', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->whereNull('verifikator_id');
            }, 'nilai_komponen_verifikasi' => function($query) use ($request){
                $query->whereHas('user', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('verifikator_id', $request->verifikator_id);
            }])->get();
            $respone = [
                'data' => $komponen,
                'sekolah' => Sekolah::with(['sekolah_sasaran' => function($query) use ($request){
                    $query->with(['rapor_mutu' => function($query){
                        $query->with(['jenis_rapor', 'status_rapor']);
                        $query->whereHas('jenis_rapor', function($query){
                            $query->where('jenis', 'verval');
                        });
                    }]);
                    $query->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
                    $query->where('verifikator_id', $request->verifikator_id);
                }])->find($request->sekolah_id),
                'tahun_pendataan' => HelperModel::tahun_pendataan(),
            ];
            return response()->json($respone);
        }
        $all_data = Instrumen::where(function($query) use ($request){
            $query->where('urut', 0);
            $query->whereHas('indikator', function($query) use ($request){
                $query->whereHas('atribut', function($query) use ($request){
                    $query->whereHas('aspek', function($query) use ($request){
                        $query->where('aspek_id', $request->aspek_id);
                    });
                });
            });
        })->get();
        if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->instrumen_id;
                $record['text'] 	= $data->pertanyaan;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data instrumen';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
    public function get_subs(Request $request){
        $callback = function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->where('verifikator_id', $request->verifikator_id);
            /*$query->whereHas('user', function($query) use ($request){
                $query->whereHas('sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('sekolah_id', $request->sekolah_id);
            });*/
        };
        return Instrumen::with(['jawaban' => $callback, 'nilai_instrumen' => $callback, 'subs'])->find($request->instrumen_id);
    }
    public function get_simpan(Request $request){
        $post = $request->except(['komponen', 'aspek', 'instrumen', 'jawaban']);
        $instrumen = Instrumen::with(['indikator.atribut'])->find($request->instrumen_id['value']);
        $save = Nilai_instrumen::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'verifikator_id' => $request->verifikator_id,
                'instrumen_id' => $request->instrumen_id['value'],
            ],
            [
                'nilai' => $request->nilai,
                'predikat' => HelperModel::predikat($request->nilai),
                'keterangan' => $request->keterangan,
            ]
        );
        if($save){
            Jawaban::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'verifikator_id' => $request->verifikator_id,
                    'komponen_id' => $request->komponen_id['value'],
                    'aspek_id' => $request->aspek_id['value'],
                    'atribut_id' => $instrumen->indikator->atribut_id,
                    'indikator_id' => $instrumen->indikator_id,
                    'instrumen_id' => $request->instrumen_id['value'],
                ],
                [
                    'nilai' => $request->nilai,
                ]
            );
            HelperModel::generate_nilai($request->user_id, $request->verifikator_id);
            $output = [
                'icon' => 'success',
                'title' => 'Jawaban berhasil diperbaharui',
            ];
        } else {
            $output = [
                'icon' => 'error',
                'title' => 'Jawaban gagal diperbaharui',
            ];
        }
        return response()->json($output);
    }
    public function get_sekolah(Request $request){
        $all_data = Sekolah::where(function($query) use ($request){
            $query->whereHas('sekolah_sasaran', function($query) use ($request){
                $query->where('verifikator_id', $request->verifikator_id);
                $query->whereHas('pakta_integritas', function($query){
                    $query->where('terkirim', 1);
                });
                if($request->supervisi){
                    $query->whereHas('rapor_mutu', function($query){
                        $query->whereHas('status_rapor', function($query){
                            $query->where('status', 'terkirim');
                            $query->orWhere('status', 'waiting');
                        });
                    });
                } else {
                    //$query->whereDoesntHave('rapor_mutu');
                }
            });
        })->with(['sekolah_sasaran' => function($query) use ($request){
            $query->where('verifikator_id', $request->verifikator_id);
        }])->get();
        if($all_data->count()){
			foreach($all_data as $data){
                $record= [];
                $record['value'] 	= $data->sekolah_id;
                $record['text'] 	= $data->nama;
                $record['sekolah_sasaran_id'] 	= $data->sekolah_sasaran->sekolah_sasaran_id;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
            $record['text'] 	= 'Tidak ditemukan data sekolah';
            $record['sekolah_sasaran_id'] 	= '';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
    public function get_kirim_verval(Request $request){
        $jenis = Jenis_rapor::where('jenis', 'verval')->first();
        $status = Status_rapor::where('status', 'terkirim')->first();
        $kirim_verval = Rapor_mutu::updateOrCreate(
            [
                'jenis_rapor_id' => $jenis->id,
                'status_rapor_id' => $status->id,
                'verifikator_id' => $request->user_id,
                'sekolah_sasaran_id' => $request->sekolah_sasaran_id
            ]
        );
        if($kirim_verval){
            $respone = [
                'title' => 'Berhasil',
                'text' => 'Hasil verifikasi dan validasi berhasil disimpan!',
                'icon' => 'success',
            ];
        } else {
            $respone = [
                'title' => 'Gagal',
                'text' => 'Hasil verifikasi dan validasi gagal disimpan. Silahkan coba beberapa saat lagi!',
                'icon' => 'error',
            ];
        }
        return response()->json($respone);
    }
    public function get_batal_verval(Request $request){
        $delete_rapor = Rapor_mutu::where(function($query) use ($request){
            $query->whereHas('status_rapor', function($query){
                $query->where('status', 'waiting');
            });
            $query->whereHas('jenis_rapor', function($query){
                $query->where('jenis', 'verval');
            });
            $query->where('verifikator_id', $request->user_id);
            $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
        })->first();
        if($delete_rapor){
            $status = Status_rapor::where('status', 'terkirim')->first();
            $delete_rapor->status_rapor_id = $status->id;
            if($delete_rapor->save()){
                $jenis_berita_acara = Jenis_berita_acara::where('jenis', 'verifikasi')->first();
                Berita_acara::where(function($query) use ($request, $jenis_berita_acara){
                    $query->where('jenis_berita_id', $jenis_berita_acara->id);
                    $query->where('verifikator_id', $request->user_id);
                    $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
                })->delete();
                $jenis_dokumen = Jenis_dokumen::where('jenis', 'berita_acara')->first();
                Dokumen::where(function($query) use ($request, $jenis_dokumen){
                    $query->where('jenis_dokumen_id', $jenis_dokumen->id);
                    $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
                })->delete();
                $respone = [
                    'title' => 'Berhasil',
                    'text' => 'Laporan hasil verifikasi dan validasi berhasil dibatalkan!',
                    'icon' => 'success',
                ];
            } else {
                $respone = [
                    'title' => 'Gagal',
                    'text' => 'Laporan verifikasi dan validasi gagal dibatalkan. Silahkan coba beberapa saat lagi!',
                    'icon' => 'error',
                ];
            }
        } else {
            $respone = [
                'title' => 'Gagal',
                'text' => 'Laporan hasil verifikasi dan validasi tidak ditemukan di database!',
                'icon' => 'error',
            ];
        }
        return response()->json($respone);
    }
    public function get_laporan(Request $request){
        /*$rapor = Rapor_mutu::with(['status_rapor', 'sekolah.tahun_pendataan'])->where(function($query) use ($request){
            $query->whereHas('status_rapor', function($query){
                $query->where('status', 'terkirim');
                $query->orWhere('status', 'waiting');
            });
            $query->whereHas('jenis_rapor', function($query){
                $query->where('jenis', 'verval');
            });
            $query->where('verifikator_id', $request->verifikator_id);
            $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
        })->first();
        $respone = [
            'status' => 'success',
            'data' => $rapor,
        ];*/
        $callback = function($query){
            $query->whereHas('jenis_rapor', function($query){
                $query->where('jenis', 'verval');
            });
        };
        $sekolah = Sekolah::with(['sekolah_sasaran' => function($query) use ($callback){
            $query->with(['terkirim' => $callback, 'waiting' => $callback, 'proses', 'terima', 'tolak']);
        }])->find($request->sekolah_id);
        $respone = [
            'status' => 'success',
            'data' => $sekolah,
        ];
        return response()->json($respone);
    }
    public function get_kirim(Request $request){
        $messages = [
            'rapor_mutu_id.exists'	=> 'Berkas berita acara tidak boleh kosong',
        ];
        $validator = Validator::make(request()->all(), [
            'rapor_mutu_id' => 'exists:berita_acara',
        ],
        $messages
        )->validate();
        $status = Status_rapor::where('status', 'waiting')->first();
        $rapor = Rapor_mutu::find($request->rapor_mutu_id);
        if($rapor){
            $rapor->keterangan = $request->keterangan;
            $rapor->status_rapor_id = $status->id;
            if($rapor->save()){
                $respone = [
                    'icon' => 'success',
                    'message' => 'Laporan hasil supervisi berhasil dikirim'
                ];
            } else {
                $respone = [
                    'icon' => 'error',
                    'message' => 'Laporan hasil supervisi gagal dikirim'
                ];
            }
        } else {
            $respone = [
                'icon' => 'error',
                'message' => 'Laporan hasil supervisi tidak ditemukan di database'
            ];
        }
        return response()->json($respone);
    }
    public function get_upload(Request $request){
        $messages = [
            'file.required'	=> 'Berkas Berita Acara tidak boleh kosong',
            'file.mimes'	=> 'Berkas Berita Acara harus berekstensi .PDF',
        ];
        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:pdf',
        ],
        $messages
        )->validate();
        $file = $request->file('file');
        $filePdf = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads', $filePdf);
        $jenis_berita_cara = Jenis_berita_acara::where('jenis', 'verifikasi')->first();
        Berita_acara::updateOrCreate([
            'jenis_berita_id' => $jenis_berita_cara->id,
            'rapor_mutu_id' => $request->rapor_mutu_id,
            'verifikator_id' => $request->verifikator_id,
            'sekolah_sasaran_id' => $request->sekolah_sasaran_id,
        ]);
        $jenis_dokumen = Jenis_dokumen::where('jenis', 'berita_acara')->first();
        Dokumen::updateOrCreate([
            'jenis_dokumen_id' => $jenis_dokumen->id,
            'verifikator_id' => $request->verifikator_id,
            'sekolah_sasaran_id' => $request->sekolah_sasaran_id,
            'file_path' => $filePdf,
        ]);
    }
    public function download(Request $request){
        //dd($request->all());
        if($request->permintaan == 'berita_acara'){
            //$dokumen = Dokumen::find($request->dokumen_id);
            //$pathToFile = public_path('uploads/'.$dokumen->file_path);
            //return response()->download($pathToFile);
            $data = [
                'now' => Carbon::now(),
                'sekolah' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
                })->first(),
                'verifikator' => User::find($request->verifikator_id),
            ];
            //return view('cetak.berita_acara', $data);
            $pdf = PDF::loadView('cetak.berita_acara', $data, [], [
                'format' => [220, 330],
                'orientation' => 'P',
            ]);
            $pdf->getMpdf()->SetFooter('|{PAGENO}|Dicetak dari Aplikasi APM SMK v.1.0.0');
            return $pdf->download('instrumen.pdf');
        }
    }
    public function cetak($sekolah_id){
        $sekolah = Sekolah::with(['sekolah_sasaran' => function($query){
            $query->with(['verifikator', 'sektor']);
        }])->find($sekolah_id);
        $instrumens = Instrumen::with(['telaah_dokumen.nilai_dokumen' => function($query) use ($sekolah){
            $query->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id);
        }])->withCount('telaah_dokumen')->where('urut', 0)->get();
        $data = [
            'sekolah' => $sekolah,
            'instrumens' => $instrumens,
        ];
        $pdf = PDF::loadView('cetak.verifikasi', $data, [], [
            'format' => [220, 330],
            'orientation' => 'P',
        ]);
        $pdf->getMpdf()->SetFooter('|{PAGENO}|Dicetak dari Aplikasi APM SMK v.1.0.0');
        return $pdf->download('Hasil Verifikasi '.$sekolah->nama.'.pdf');
    }
}
