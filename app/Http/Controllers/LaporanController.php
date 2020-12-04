<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Pendamping;
use App\Sekolah;
use App\Sekolah_sasaran;
use App\Jenis_laporan;
use App\Laporan;
use App\Berkas_laporan;
use App\User;
use App\Nilai_komponen;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
class LaporanController extends Controller
{
    public function index(Request $request){
        //$data_pendamping = Pendamping::has('sekolah_sasaran')->get();
        return view('laporan.index');
    }
    public function verifikasi(Request $request){
        //$data_verifikator = User::whereRoleIs('penjamin_mutu')->get();
        return view('laporan.verifikasi');
    }
    public function validasi_token_verifikator(Request $request){
        $pendamping = User::whereRaw("lower(token) = '".strtolower($request->token)."'")->first();
        $sekolah = NULL;
        if($pendamping){
            $sekolah = Sekolah::whereHas('sekolah_sasaran', function($query) use ($pendamping){
                $query->where('verifikator_id', $pendamping->user_id);
                $query->whereHas('pakta_integritas', function($query){
                    $query->where('terkirim', 1);
                });
            })->get();
        }
        $token = $request->token;//str_repeat('*', strlen($request->token));
        $jenis_laporan = Jenis_laporan::whereIn('id', [3])->get();
        $formulir = 'verifikator';
        return response()->json([
            'body' => view('laporan.sekolah', compact('sekolah', 'pendamping', 'jenis_laporan', 'formulir'))->render(),
            'token' => $token,
        ]);
    }
    public function validasi_token(Request $request){
        $pendamping = Pendamping::whereRaw("lower(token) = '".strtolower($request->token)."'")->first();
        $sekolah = NULL;
        if($pendamping){
            $sekolah = Sekolah::whereHas('sekolah_sasaran', function($query) use ($pendamping){
                $query->where('pendamping_id', $pendamping->pendamping_id);
                $query->whereHas('pakta_integritas', function($query){
                    $query->where('terkirim', 1);
                });
            })->get();
        }
        $token = $request->token;//str_repeat('*', strlen($request->token));
        $jenis_laporan = Jenis_laporan::whereIn('id', [1])->get();
        $formulir = 'pendamping';
        return response()->json([
            'body' => view('laporan.sekolah', compact('sekolah', 'pendamping', 'jenis_laporan', 'formulir'))->render(),
            'token' => $token,
        ]);
    }
    public function get_sekolah(Request $request){
        $sekolah = Sekolah::with('sekolah_sasaran')->find($request->sekolah_id);
        if($request->pendamping_id){
            $pendamping = Pendamping::find($request->pendamping_id);
            $laporan = Laporan::where('jenis_laporan_id', $request->jenis_laporan_id)->where('pendamping_id', $request->pendamping_id)->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->first();
        } else {
            $pendamping = User::find($request->verifikator_id);
            $laporan = Laporan::where('jenis_laporan_id', $request->jenis_laporan_id)->where('verifikator_id', $request->verifikator_id)->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->first();
        }
        $jenis_laporan = $request->jenis_laporan_id;
        return response()->json([
            'body' => view('laporan.form', compact('sekolah', 'pendamping', 'jenis_laporan', 'laporan'))->render(),
        ]);
    }
    public function formulir(Request $request){

    }
    public function simpan(Request $request){
        if($request->pendamping_id){
            $pendamping = Pendamping::find($request->pendamping_id);
            $pendamping->nama = $request->nama;
            $pendamping->instansi = $request->instansi;
            $key = 'pendamping_id';
        } else {
            $pendamping = User::find($request->verifikator_id);
            $pendamping->asal_institusi = $request->instansi;
            $key = 'verifikator_id';
        }
        $pendamping->nip = $request->nip;
        $pendamping->nuptk = $request->nuptk;
        $pendamping->email = $request->email;
        $pendamping->nomor_hp = $request->nomor_hp;
        $pendamping->save();
        $sekolah_sasaran = Sekolah_sasaran::where('sekolah_id', $request->sekolah_id)->first();
        $laporan = Laporan::updateOrCreate(
            [
                'jenis_laporan_id' => $request->jenis_laporan_id,
                $key => ($request->pendamping_id) ? $request->pendamping_id : $request->verifikator_id,
                'sekolah_sasaran_id' => $sekolah_sasaran->sekolah_sasaran_id,
            ],
            [
                'jumlah_iduka' => $request->jumlah_iduka,
                'lulusan' => $request->lulusan,
                'lulusan_all' => $request->lulusan_all,
                'perkembangan_smk' => $request->perkembangan_smk,
                'kesimpulan_saran' => $request->kesimpulan_saran,
                'tanggal_pelaksanaan' => date('Y-m-d', strtotime($request->tanggal_pelaksanaan)),
                'pengisian' => $request->pengisian,
                'kendala' => $request->kendala,
                'kondisi' => $request->kondisi,
            ]
        );
        if($request->berkas){
            foreach($request->berkas as $berkas){
                Berkas_laporan::create(
                    ['laporan_id' => $laporan->laporan_id,'file_path' => $berkas]
                );
            }
        }
    }
    public function upload(Request $request)
    {
        $image = $request->file('file');
   
        $imageName = time() . '-' . strtoupper(Str::random(10)) . '.' . $image->extension();
        $image->move(public_path('berkas_laporan'), $imageName);
   
        return response()->json(['success'=> $imageName]);
    }
    function rekap(Request $request){
        $all_sekolah = Sekolah::with(['nilai_input' => function($query){
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
        }])->has('sekolah_sasaran')->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        $i=1;
        foreach($all_sekolah as $sekolah){
            $nilai_sekolah[] = [
                'No' => $i,
                'Provinsi' => $sekolah->provinsi,
                'Kabupaten/Kota' => $sekolah->kabupaten,
                'Nama Sekolah' => $sekolah->nama,
                'NPSN' => $sekolah->npsn,
                'Nilai Input' => ($sekolah->nilai_input) ? $sekolah->nilai_input->total_nilai : '-',
                'Nilai Proses' => ($sekolah->nilai_proses) ? $sekolah->nilai_proses->total_nilai : '-',
                'Nilai Output' => ($sekolah->nilai_output) ? $sekolah->nilai_output->total_nilai : '-',
                'Nilai Outcome' => ($sekolah->nilai_outcome) ? $sekolah->nilai_outcome->total_nilai : '-',
                'Nilai Impact' => ($sekolah->nilai_impact) ? $sekolah->nilai_impact->total_nilai : '-',
            ];
            $nilai_verifikasi[] = [
                'No' => $i,
                'Provinsi' => $sekolah->provinsi,
                'Kabupaten/Kota' => $sekolah->kabupaten,
                'Nama Sekolah' => $sekolah->nama,
                'NPSN' => $sekolah->npsn,
                'Nilai Input' => ($sekolah->nilai_input_verifikasi) ? $sekolah->nilai_input_verifikasi->total_nilai : '-',
                'Nilai Proses' => ($sekolah->nilai_proses_verifikasi) ? $sekolah->nilai_proses_verifikasi->total_nilai : '-',
                'Nilai Output' => ($sekolah->nilai_output_verifikasi) ? $sekolah->nilai_output_verifikasi->total_nilai : '-',
                'Nilai Outcome' => ($sekolah->nilai_outcome_verifikasi) ? $sekolah->nilai_outcome_verifikasi->total_nilai : '-',
                'Nilai Impact' => ($sekolah->nilai_impact_verifikasi) ? $sekolah->nilai_impact_verifikasi->total_nilai : '-',
            ];
            $i++;
        }
        $sheets = new SheetCollection([
            'Nilai Sekolah' => $nilai_sekolah,
            'Nilai Verifikasi' => $nilai_verifikasi,
        ]);
        return (new FastExcel($sheets))->download('Rekapitulasi Rapor Mutu SMK CoE Tahun 2020.xlsx');
    }
    public function rekapitulasi(Request $request){
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
        }, 'nilai_komponen' => function($query){
            $query->whereNull('verifikator_id');
        }, 'nilai_komponen_verifikasi' => function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
        }]/*)->where(function($query){
            if(request()->kode_wilayah){
                $query->whereIn('kode_wilayah', function($query){
                    $query->select('kode_wilayah')->from('wilayah')->whereRaw("trim(mst_kode_wilayah) = '". request()->kode_wilayah."'");
                });
            }
        }*/)->orderBy('provinsi_id')->orderBy('kabupaten_id')->get();
        $i=1;
        foreach($all_sekolah as $sekolah){
            $keyed_terendah_sekolah = [];
            if($sekolah->nilai_kinerja){
                $nilai_terendah = $sekolah->nilai_kinerja->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed_terendah_sekolah = $nilai_terendah->keyBy('nilai')->toArray();
            }
            $terendah_sekolah = ($keyed_terendah_sekolah) ? ($keyed_terendah_sekolah[$sekolah->nilai_kinerja->min('total_nilai')]) ? $keyed_terendah_sekolah[$sekolah->nilai_kinerja->min('total_nilai')]['nama'] : '-' : '-';
            $keyed_tertinggi_sekolah = [];
            if($sekolah->nilai_kinerja){
                $nilai_tertinggi = $sekolah->nilai_kinerja->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed_tertinggi_sekolah = $nilai_tertinggi->keyBy('nilai')->toArray();
            }
            $tertinggi_sekolah = ($keyed_tertinggi_sekolah) ? ($keyed_tertinggi_sekolah[$sekolah->nilai_kinerja->max('total_nilai')]) ? $keyed_tertinggi_sekolah[$sekolah->nilai_kinerja->max('total_nilai')]['nama'] : '-' : '-';
            $keyed_terendah_verifikasi = [];
            if($sekolah->nilai_kinerja_verifikasi){
                $nilai_terendah_verifikasi = $sekolah->nilai_kinerja_verifikasi->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed_terendah_verifikasi = $nilai_terendah_verifikasi->keyBy('nilai')->toArray();
            }
            $terendah_verifikasi = ($keyed_terendah_verifikasi) ? ($keyed_terendah_verifikasi[$sekolah->nilai_kinerja_verifikasi->min('total_nilai')]) ? $keyed_terendah_verifikasi[$sekolah->nilai_kinerja_verifikasi->min('total_nilai')]['nama'] : '-' : '-';
            $keyed_tertinggi_verifikasi = [];
            if($sekolah->nilai_kinerja_verifikasi){
                $nilai_tertinggi_verifikasi = $sekolah->nilai_kinerja_verifikasi->map(function ($name) {
                    $return['nilai'] = $name->total_nilai;
                    $return['nama'] = $name->komponen->nama;
                    return $return;
                });
                $keyed_tertinggi_verifikasi = $nilai_tertinggi_verifikasi->keyBy('nilai')->toArray();
            }
            $tertinggi_verifikasi = ($keyed_tertinggi_verifikasi) ? ($keyed_tertinggi_verifikasi[$sekolah->nilai_kinerja_verifikasi->max('total_nilai')]) ? $keyed_tertinggi_verifikasi[$sekolah->nilai_kinerja_verifikasi->max('total_nilai')]['nama'] : '-' : '-';
            $nilai_sekolah = ($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->nilai : 0;
            $nilai_verifikasi = ($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->nilai : 0;
            $nilai_kinerja = ($sekolah->nilai_kinerja_verifikasi) ? number_format($sekolah->nilai_kinerja_verifikasi->avg('total_nilai'),2,'.','.') : 0;
            $nilai_dampak = ($sekolah->nilai_dampak_verifikasi) ? number_format($sekolah->nilai_dampak_verifikasi->avg('total_nilai'),2,'.','.') : 0;
            $afirmasi = '-';
            if($nilai_verifikasi){
                if($nilai_kinerja < 50 && $nilai_dampak < 50) {
                    $afirmasi = 'Prioritas';
                } elseif($nilai_kinerja < 50 && $nilai_dampak > 50){
                    $afirmasi = 'Rekomendasi 1';
                } elseif($nilai_kinerja > 50 && $nilai_dampak < 50){
                    $afirmasi = 'Rekomendasi 2';
                }
            }
            $status = '-';
            if($nilai_sekolah == $nilai_verifikasi){
                $status = 'Rapor Mutu Sekolah = Rapor Mutu Verifikasi';
            } elseif($nilai_sekolah < $nilai_verifikasi){
                $status = 'Rapor Mutu Sekolah < Rapor Mutu Verifikasi';
            } elseif($nilai_sekolah > $nilai_verifikasi) {
                $status = 'Rapor Mutu Sekolah > Rapor Mutu Verifikasi';
            }
            $set_nilai_sekolah[] = [
                'No' => $i,
                'Provinsi' => $sekolah->provinsi,
                'Kabupaten/Kota' => $sekolah->kabupaten,
                'Nama Sekolah' => $sekolah->nama,
                'NPSN' => $sekolah->npsn,
                'Nilai Input' => ($sekolah->nilai_input) ? $sekolah->nilai_input->total_nilai : '-',
                'Nilai Proses' => ($sekolah->nilai_proses) ? $sekolah->nilai_proses->total_nilai : '-',
                'Nilai Output' => ($sekolah->nilai_output) ? $sekolah->nilai_output->total_nilai : '-',
                'Rerata' => ($sekolah->nilai_kinerja) ? number_format($sekolah->nilai_kinerja->avg('total_nilai'),2,'.','.') : 0,
                'Nilai Terendah' => $terendah_sekolah,
                'Nilai Tertinggi' => $tertinggi_sekolah,
                'Nilai Outcome' => ($sekolah->nilai_outcome) ? $sekolah->nilai_outcome->total_nilai : '-',
                'Nilai Impact' => ($sekolah->nilai_impact) ? $sekolah->nilai_impact->total_nilai : '-',
                'Rerata' => ($sekolah->nilai_dampak) ? number_format($sekolah->nilai_dampak->avg('total_nilai'),2,'.','.') : 0,
                'Nilai Rapor' => ($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->nilai : 0,
                'Predikat' => ($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->predikat : 0,
                'Hasil Rata-rata Komponen' => ($sekolah->nilai_komponen) ? number_format($sekolah->nilai_komponen->avg('total_nilai'),2,'.','.') : 0,
            ];
            $set_nilai_verifikasi[] = [
                'No' => $i,
                'Provinsi' => $sekolah->provinsi,
                'Kabupaten/Kota' => $sekolah->kabupaten,
                'Nama Sekolah' => $sekolah->nama,
                'NPSN' => $sekolah->npsn,
                'Nilai Input' => ($sekolah->nilai_input_verifikasi) ? $sekolah->nilai_input_verifikasi->total_nilai : '-',
                'Nilai Proses' => ($sekolah->nilai_proses_verifikasi) ? $sekolah->nilai_proses_verifikasi->total_nilai : '-',
                'Nilai Output' => ($sekolah->nilai_output_verifikasi) ? $sekolah->nilai_output_verifikasi->total_nilai : '-',
                'Rerata' => ($sekolah->nilai_kinerja_verifikasi) ? number_format($sekolah->nilai_kinerja_verifikasi->avg('total_nilai'),2,'.','.') : 0,
                'Nilai Terendah' => $terendah_verifikasi,
                'Nilai Tertinggi' => $tertinggi_verifikasi,
                'Nilai Outcome' => ($sekolah->nilai_outcome_verifikasi) ? $sekolah->nilai_outcome_verifikasi->total_nilai : '-',
                'Nilai Impact' => ($sekolah->nilai_impact_verifikasi) ? $sekolah->nilai_impact_verifikasi->total_nilai : '-',
                'Rerata' => ($sekolah->nilai_dampak_verifikasi) ? number_format($sekolah->nilai_dampak_verifikasi->avg('total_nilai'),2,'.','.') : 0,
                'Nilai Rapor' => ($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->nilai : 0,
                'Predikat' => ($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->predikat : 0,
                'Afirmasi' => $afirmasi,
                'Nilai Sekolah >< Nilai Verifikasi' => $status,
            ];
            $i++;
        }
        $sheets = new SheetCollection([
            'Nilai Sekolah' => $set_nilai_sekolah,
            'Nilai Verifikasi' => $set_nilai_verifikasi,
        ]);
        return (new FastExcel($sheets))->download('Rekapitulasi Rapor Mutu SMK CoE Tahun 2020.xlsx');
        /*foreach ($all_sekolah as $sekolah){
            $rekap[] = [
                'NO' => $i++,
                'NAMA SEKOLAH' => $sekolah->nama,
                'NPSN' => $sekolah->npsn,
            ];
        }
                        <tr>
                            <td class="text-center">{{$loop->iteration + $all_sekolah->firstItem() - 1}}</td>
                            <td class="text-center">{{$sekolah->nama}}</td>
                            <td class="text-center">{{$sekolah->npsn}}</td>
                            <td class="text-center">{{($sekolah->nilai_input) ? $sekolah->nilai_input->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_proses) ? $sekolah->nilai_proses->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_output) ? $sekolah->nilai_output->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_kinerja) ? number_format($sekolah->nilai_kinerja->avg('total_nilai'),2,'.','.') : 0}}</td>
                            <td class="text-center">
                                <?php
                                $keyed_terendah_sekolah = [];
                                if($sekolah->nilai_kinerja){
                                    $nilai_terendah = $sekolah->nilai_kinerja->map(function ($name) {
                                        $return['nilai'] = $name->total_nilai;
                                        $return['nama'] = $name->komponen->nama;
                                        return $return;
                                    });
                                    $keyed_terendah_sekolah = $nilai_terendah->keyBy('nilai')->toArray();
                                }
                                $terendah_sekolah = ($keyed_terendah_sekolah) ? ($keyed_terendah_sekolah[$sekolah->nilai_kinerja->min('total_nilai')]) ? $keyed_terendah_sekolah[$sekolah->nilai_kinerja->min('total_nilai')]['nama'] : '-' : '-';
                                ?>
                                {{$terendah_sekolah}}
                            </td>
                            <td class="text-center">
                                <?php
                                $keyed_tertinggi_sekolah = [];
                                if($sekolah->nilai_kinerja){
                                    $nilai_tertinggi = $sekolah->nilai_kinerja->map(function ($name) {
                                        $return['nilai'] = $name->total_nilai;
                                        $return['nama'] = $name->komponen->nama;
                                        return $return;
                                    });
                                    $keyed_tertinggi_sekolah = $nilai_tertinggi->keyBy('nilai')->toArray();
                                }
                                $tertinggi_sekolah = ($keyed_tertinggi_sekolah) ? ($keyed_tertinggi_sekolah[$sekolah->nilai_kinerja->max('total_nilai')]) ? $keyed_tertinggi_sekolah[$sekolah->nilai_kinerja->max('total_nilai')]['nama'] : '-' : '-';
                                ?>
                                {{$tertinggi_sekolah}}
                            </td>
                            <td class="text-center">{{($sekolah->nilai_outcome) ? $sekolah->nilai_outcome->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_impact) ? $sekolah->nilai_impact->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_dampak) ? number_format($sekolah->nilai_dampak->avg('total_nilai'),2,'.','.') : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->predikat : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_input_verifikasi) ? $sekolah->nilai_input_verifikasi->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_proses_verifikasi) ? $sekolah->nilai_proses_verifikasi->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_output_verifikasi) ? $sekolah->nilai_output_verifikasi->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_kinerja_verifikasi) ? number_format($sekolah->nilai_kinerja_verifikasi->avg('total_nilai'),2,'.','.') : 0}}</td>
                            <td class="text-center">
                                <?php
                                $keyed_terendah_verifikasi = [];
                                if($sekolah->nilai_kinerja_verifikasi){
                                    $nilai_terendah_verifikasi = $sekolah->nilai_kinerja_verifikasi->map(function ($name) {
                                        $return['nilai'] = $name->total_nilai;
                                        $return['nama'] = $name->komponen->nama;
                                        return $return;
                                    });
                                    $keyed_terendah_verifikasi = $nilai_terendah_verifikasi->keyBy('nilai')->toArray();
                                }
                                $terendah_verifikasi = ($keyed_terendah_verifikasi) ? ($keyed_terendah_verifikasi[$sekolah->nilai_kinerja_verifikasi->min('total_nilai')]) ? $keyed_terendah_verifikasi[$sekolah->nilai_kinerja_verifikasi->min('total_nilai')]['nama'] : '-' : '-';
                                ?>
                                {{$terendah_verifikasi}}
                            </td>
                            <td class="text-center">
                                <?php
                                $keyed_tertinggi_verifikasi = [];
                                if($sekolah->nilai_kinerja_verifikasi){
                                    $nilai_tertinggi_verifikasi = $sekolah->nilai_kinerja_verifikasi->map(function ($name) {
                                        $return['nilai'] = $name->total_nilai;
                                        $return['nama'] = $name->komponen->nama;
                                        return $return;
                                    });
                                    $keyed_tertinggi_verifikasi = $nilai_tertinggi_verifikasi->keyBy('nilai')->toArray();
                                }
                                $tertinggi_verifikasi = ($keyed_tertinggi_verifikasi) ? ($keyed_tertinggi_verifikasi[$sekolah->nilai_kinerja_verifikasi->max('total_nilai')]) ? $keyed_tertinggi_verifikasi[$sekolah->nilai_kinerja_verifikasi->max('total_nilai')]['nama'] : '-' : '-';
                                ?>
                                {{$tertinggi_verifikasi}}
                            </td>
                            <td class="text-center">{{($sekolah->nilai_outcome_verifikasi) ? $sekolah->nilai_outcome_verifikasi->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_impact_verifikasi) ? $sekolah->nilai_impact_verifikasi->total_nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_dampak_verifikasi) ? number_format($sekolah->nilai_dampak_verifikasi->avg('total_nilai'),2,'.','.') : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->nilai : 0}}</td>
                            <td class="text-center">{{($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->predikat : 0}}</td>
                            <td class="text-center">
                                <?php
                                $nilai_sekolah = ($sekolah->nilai_akhir) ? $sekolah->nilai_akhir->predikat : 0;
                                $nilai_verifikasi = ($sekolah->nilai_akhir_verifikasi) ? $sekolah->nilai_akhir_verifikasi->nilai : 0;
                                $nilai_kinerja = ($sekolah->nilai_kinerja_verifikasi) ? number_format($sekolah->nilai_kinerja_verifikasi->avg('total_nilai'),2,'.','.') : 0;
                                $nilai_dampak = ($sekolah->nilai_dampak_verifikasi) ? number_format($sekolah->nilai_dampak_verifikasi->avg('total_nilai'),2,'.','.') : 0;
                                ?>
                                @if($nilai_verifikasi)
                                    @if($nilai_kinerja < 50 && $nilai_dampak < 50)
                                        Prioritas
                                    @elseif($nilai_kinerja < 50 && $nilai_dampak > 50)
                                        Rekomendasi 1
                                    @elseif($nilai_kinerja > 50 && $nilai_dampak < 50)
                                        Rekomendasi 2
                                    @else
                                        -
                                    @endif
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center">
                                {{--
                                    =IF(W6=L6;"Rapor Mutu Sekolah = Rapor Mutu Verifikasi";IF(L6<W6;"Rapor Mutu Sekolah < Rapor Mutu Verifikasi";"Rapor Mutu Sekolah > Rapor Mutu Verifikasi"))
                                --}}
                                @if($nilai_sekolah == $nilai_verifikasi)
                                Rapor Mutu Sekolah = Rapor Mutu Verifikasi
                                @elseif($nilai_sekolah < $nilai_verifikasi)
                                Rapor Mutu Sekolah &lt; Rapor Mutu Verifikasi
                                @elseif($nilai_sekolah > $nilai_verifikasi)
                                Rapor Mutu Sekolah &gt; Rapor Mutu Verifikasi
                                @endif
                            </td>
                        </tr>
                        @endforeach
        */
    }
}
