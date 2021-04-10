<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sekolah;
use App\User;
use App\HelperModel;
use App\Tahun_pendataan;
use Carbon\Carbon;
use File;
use Validator;
use PDF;
use Artisan;
use App\Tanah;
use App\Bangunan;
use App\Ruang;
use App\Alat;
use App\Angkutan;
use App\Buku;

class ReferensiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_tanah($request){
        $all_data = Tanah::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_bangunan($request){
        $all_data = Bangunan::where(function($query){
            if(request()->sekolah_id){
                $query->whereHas('tanah', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                });
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_ruang($request){
        $all_data = Ruang::where(function($query){
            if(request()->sekolah_id){
                $query->whereHas('bangunan', function($query){
                    $query->whereHas('tanah', function($query){
                        $query->where('sekolah_id', request()->sekolah_id);
                    });
                });
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_alat($request){
        $all_data = Alat::where(function($query){
            if(request()->sekolah_id){
                $query->whereHas('ruang', function($query){
                    $query->whereHas('bangunan', function($query){
                        $query->whereHas('tanah', function($query){
                            $query->where('sekolah_id', request()->sekolah_id);
                        });
                    });
                });
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_angkutan($request){
        $all_data = Angkutan::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_buku($request){
        $all_data = Buku::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('judul', 'ilike', '%' . request()->q . '%')
                ->orWhere('kepemilikan', 'ilike', '%' . request()->q . '%')
                ->orWhere('keterangan', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_sekolah($request){
        $all_data = Sekolah::select('sekolah_id', 'nama')->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_tanah($request){
        $all_data = Tanah::select('tanah_id', 'nama')->where('sekolah_id', $request->sekolah_id)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_sekolah($request)
    {
        $sortBy = request()->sortby;
        if($sortBy == 'is_coe'){
            $sortBy = Smk_coe::select('sekolah_id')
            ->whereColumn('sekolah_id', 'sekolah.sekolah_id')
            ->limit(1);
        }
        $all_data = Sekolah::where(function($query){
            if(request()->q){
                $query->where(function($query){
                    $query->where('nama', 'ilike', '%' . request()->q . '%');
                    if(request()->sekolah_id){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                });
                $query->orWhere(function($query){
                    $query->where('npsn', 'ilike', '%' . request()->q . '%');
                    if(request()->sekolah_id){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                });
            } else {
                if(request()->sekolah_id){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
            }
        })->with(['user'])->orderBy($sortBy, request()->sortbydesc)
            /*->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
                $all_data->orWhere('npsn', 'ilike', '%' . request()->q . '%');
                $all_data->orWhere('kecamatan', 'ilike', '%' . request()->q . '%');
                $all_data->orWhere('kabupaten', 'ilike', '%' . request()->q . '%');
                $all_data->orWhere('provinsi', 'ilike', '%' . request()->q . '%');
        })*/->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_ptk($request)
    {
        $all_data = Ptk::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('nuptk', 'ilike', '%' . request()->q . '%')
                ->orWhere('nik', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_pd($request)
    {
        $all_data = Peserta_didik::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('no_induk', 'ilike', '%' . request()->q . '%')
                ->orWhere('nisn', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function upload(Request $request){
        $messages = [
            'file.required'	=> 'File Upload tidak boleh kosong',
            'file.mimes'	=> 'File Upload harus berekstensi .XLSX',
        ];
        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx',
            //'file' => 'required',
        ],
        $messages
        )->validate();
        $file = $request->file('file');
        $fileExcel = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads', $fileExcel);
        $insert = 0;
        $snp = Standar::updateOrCreate(['kode' => 'snp', 'nama' => 'Standar Nasional Pendidikan']);
        $bsc = Standar::updateOrCreate(['kode' => 'bsc', 'nama' => 'Balanced Scorecard']);
        $link_match = Standar::updateOrCreate(['kode' => 'link match', 'nama' => 'Link & Match']);
        $renstra = Standar::updateOrCreate(['kode' => 'renstra', 'nama' => 'Rencana Strategis']);
        $data_snp = (new FastExcel)->sheet(1)->import('uploads/'.$fileExcel);
        foreach($data_snp as $isi_snp){
            Isi_standar::updateOrCreate([
                'standar_id' => $snp->id,
                'kode' => $isi_snp['kode'],
                'nama' => $isi_snp['nama'],
            ]);
        }
        $data_bsc = (new FastExcel)->sheet(2)->import('uploads/'.$fileExcel);
        foreach($data_bsc as $isi_bsc){
            $induk = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'kode' => $isi_bsc['kode_induk'],
                'nama' => $isi_bsc['isi'],
            ]);
            $sub = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'isi_standar_id' => $induk->id,
                'kode' => $isi_bsc['kode_sub'],
                'nama' => $isi_bsc['isi_sub'],
            ]);
            $sub_sub = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'isi_standar_id' => $sub->id,
                'kode' => $isi_bsc['kode_sub_sub'],
                'nama' => $isi_bsc['isi_sub_sub'],
            ]);
        }
        $data_link_match = (new FastExcel)->sheet(3)->import('uploads/'.$fileExcel);
        foreach($data_link_match as $isi_link_match){
            Isi_standar::updateOrCreate([
                'standar_id' => $link_match->id,
                'kode' => $isi_link_match['kode'],
                'nama' => $isi_link_match['nama'],
            ]);
        }
        $data_renstra = (new FastExcel)->sheet(4)->import('uploads/'.$fileExcel);
        foreach($data_renstra as $isi_renstra){
            $induk_renstra = Isi_standar::updateOrCreate([
                'standar_id' => $renstra->id,
                'kode' => $isi_renstra['kode'],
                'nama' => $isi_renstra['renstra'],
            ]);
            Isi_standar::updateOrCreate([
                'standar_id' => $renstra->id,
                'isi_standar_id' => $induk_renstra->id,
                'kode' => $isi_renstra['kode'],
                'nama' => $isi_renstra['sub_renstra'],
            ]);
        }
        $data_instrumen = (new FastExcel)->sheet(5)->import('uploads/'.$fileExcel);
        foreach($data_instrumen as $item){
            if($item['Komponen']){
                $komponen = Komponen::updateOrCreate([
                    'nama' => $item['Komponen'],
                ]);
                $aspek = Aspek::updateOrCreate([
                    'komponen_id' => $komponen->id,
                    'nama' => $item['Aspek'],
                    'bobot' => $item['Bobot'],
                ]);
                $atribut = Atribut::updateOrCreate([
                    'aspek_id' => $aspek->id,
                    'nama' => $item['Atribut'],
                ]);
                $indikator = Indikator::updateOrCreate([
                    'atribut_id' => $atribut->id,
                    'nama' => $item['Indikator Kinerja'],
                ]);
                $instrumen = Instrumen::updateOrCreate([
                    'indikator_id' => $indikator->id,
                    'urut' => 0,
                    'pertanyaan' => $item['Rumusan Pertanyaan'],
                    'petunjuk_pengisian' => $item['petunjuk pengisian'],
                    'skor' => 5,
                ]);
                for($i=1;$i<=5;$i++){
                    Instrumen::updateOrCreate([
                        'indikator_id' => $indikator->id,
                        'ins_id' => $instrumen->instrumen_id,
                        'urut' => $i,
                        'pertanyaan' => $item['Capaian '.$i],
                        'petunjuk_pengisian' => $item['petunjuk pengisian'],
                        'skor' => 5,
                    ]);
                }
                $find = Instrumen::where(function($query) use ($item){
                    $query->where('indikator_id', $item['No']);
                    $query->where('urut', 0);
                })->first();
                if($find && $item['no_breakdown']){
                    $breakdown = Breakdown::updateOrCreate([
                        'instrumen_id' => $find->instrumen_id,
                        'urut' => $item['no_breakdown'],
                        'breakdown' => $item['breakdown'],
                    ]);
                    if($item['renstra']){
                        $renstra_a = explode(";",$item['renstra']);
                        foreach($renstra_a as $aa){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'renstra');
                            })->where('kode', $aa)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['link match']){
                        $link_match_a = explode(";",$item['link match']);
                        foreach($link_match_a as $ab){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'link match');
                            })->where('kode', $ab)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['bsc']){
                        $bsc_a = explode(";",$item['bsc']);
                        foreach($bsc_a as $ac){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'bsc');
                            })->where('kode', $ac)->whereNull('isi_standar_id')->first();
                            //$find_standar = Standar::where('kode', 'link match')->where('kode', $ac)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['snp']){
                        $snp_a = explode(";",$item['snp']);
                        foreach($snp_a as $ad){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'snp');
                            })->where('kode', $ad)->whereNull('isi_standar_id')->first();
                            //$find_standar = Standar::where('kode', 'link match')->where('kode', $ad)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    $question = Question::updateOrCreate([
                        'breakdown_id' => $breakdown->breakdown_id,
                        'urut' => $item['no_isian'],
                        'question' => $item['question'],
                        //'answer' => $item['answer'],
                    ]);
                    $answer = Answer::updateOrCreate([
                        'question_id' => $question->question_id,
                        'urut' => $item['urut_answer'],
                        'answer' => $item['answer'],
                        'type' => $item['type'],
                    ]);
                }
            }
        }
        if(File::delete(public_path('uploads/'.$fileExcel))){
            $data = [
                'title' => 'Berhasil',
                'text' => 'Data breakdown berhasil disimpan',
                'icon' => 'success',
            ];
        } else {
            $data = [
                'title' => 'Gagal',
                'text' => 'Data breakdown gagal disimpan',
                'icon' => 'error',
            ];
        }
        return response()->json($data);
        $sheets = (new FastExcel)->importSheets('uploads/'.$fileExcel);
        foreach($sheets as $sheet){
            dd($sheet);
        }
        dd($sheets);
    }
    public function upload_old(Request $request){
        $messages = [
            'file.required'	=> 'File Upload tidak boleh kosong',
            'file.mimes'	=> 'File Upload harus berekstensi .XLSX',
        ];
        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx',
            //'file' => 'required',
        ],
        $messages
        )->validate();
        $file = $request->file('file');
        $fileExcel = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads', $fileExcel);
        $insert = 0;
        $komponen = (new FastExcel)->import('uploads/'.$fileExcel, function ($item) use ($request, &$insert){
            if($item['Komponen']){
                $komponen = Komponen::updateOrCreate([
                    'nama' => $item['Komponen'],
                ]);
                $aspek = Aspek::updateOrCreate([
                    'komponen_id' => $komponen->id,
                    'nama' => $item['Aspek'],
                    'bobot' => $item['Bobot'],
                ]);
                $atribut = Atribut::updateOrCreate([
                    'aspek_id' => $aspek->id,
                    'nama' => $item['Atribut'],
                ]);
                $indikator = Indikator::updateOrCreate([
                    'atribut_id' => $atribut->id,
                    'nama' => $item['Indikator Kinerja'],
                ]);
                $instrumen = Instrumen::updateOrCreate([
                    'indikator_id' => $indikator->id,
                    'urut' => 0,
                    'pertanyaan' => $item['Rumusan Pertanyaan'],
                    'skor' => 5,
                ]);
                for($i=1;$i<=5;$i++){
                    Instrumen::updateOrCreate([
                        'indikator_id' => $indikator->id,
                        'ins_id' => $instrumen->instrumen_id,
                        'urut' => $i,
                        'pertanyaan' => $item['Capaian '.$i],
                        'skor' => 5,
                    ]);
                }
            }
            $insert++;
        });
        File::delete(public_path('uploads/'.$fileExcel));
        if($insert){
            $data = [
                'title' => 'Berhasil',
                'text' => 'Data Instrumen berhasil disimpan',
                'icon' => 'success',
            ];
        } else {
            $data = [
                'title' => 'Gagal',
                'text' => 'Data Instrumen gagal disimpan',
                'icon' => 'error',
            ];
        }
        return response()->json($data);
    }
    public function upload_new(Request $request){
        $messages = [
            'file.required'	=> 'File Upload tidak boleh kosong',
            'file.mimes'	=> 'File Upload harus berekstensi .XLSX',
        ];
        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx',
            //'file' => 'required',
        ],
        $messages
        )->validate();
        $file = $request->file('file');
        $fileExcel = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads', $fileExcel);
        $insert = 0;
        $komponen = (new FastExcel)->import('uploads/'.$fileExcel, function ($item) use ($request, &$insert){
            $find = Instrumen::where(function($query) use ($item){
                $query->where('indikator_id', $item['nomor']);
                $query->where('urut', 0);
            })->first();
            if($find && $item['no_breakdown']){
                $breakdown = Breakdown::updateOrCreate([
                    'instrumen_id' => $find->instrumen_id,
                    'urut' => $item['no_breakdown'],
                    'breakdown' => $item['breakdown'],
                ]);
                $question = Question::updateOrCreate([
                    'breakdown_id' => $breakdown->breakdown_id,
                    'urut' => $item['no_isian'],
                    'question' => $item['question'],
                    //'answer' => $item['answer'],
                ]);
                $answer = Answer::updateOrCreate([
                    'question_id' => $question->question_id,
                    'urut' => $item['urut_answer'],
                    'answer' => $item['answer'],
                    'type' => $item['type'],
                ]);
            }
            //dd($find);
            $insert++;
        });
        File::delete(public_path('uploads/'.$fileExcel));
        if($insert){
            $data = [
                'title' => 'Berhasil',
                'text' => 'Data breakdown berhasil disimpan',
                'icon' => 'success',
            ];
        } else {
            $data = [
                'title' => 'Gagal',
                'text' => 'Data breakdown gagal disimpan',
                'icon' => 'error',
            ];
        }
        return response()->json($data);
    }
    public function get_detil_sekolah($request){
        /*if($request->sekolah_id){
            $sekolah = Sekolah::withCount(['ptk', 'pd'])->find($request->sekolah_id);
            $nilai = [
                'grade_personal' => 10,
                'grade_sekolah' => 1,
            ];
            $custom = collect($nilai);
            $data = $custom->merge($sekolah);
        } else {
            $nilai = [
                'sekolah' => Sekolah::count(),
                'ptk_count' => Ptk::count(),
                'grade_personal' => 10,
                'grade_sekolah' => 1,
            ];
            $data = collect($nilai);
        }*/
        $user = User::withCount(['nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }])->with(['nilai_akhir', 'sekolah.sekolah_sasaran' => function($query){
            $query->with(['pakta_integritas', 'rapor_mutu.status_rapor']);
        }])->find($request->user_id);
        $instrumen = Instrumen::where('urut', 0)->count();
        /*$nilai_instrumen = Nilai_instrumen::where(function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        })->count();
        $hitung = Nilai_akhir::where('user_id', $request->user_id)->first();
        $pakta = Pakta_integritas::whereHas('sekolah_sasaran', function($query) use ($user){
            $query->where('sekolah_id', $user->sekolah_id);
        })->first();
        $verval = Verval::where('sekolah_id', $user->sekolah_id)->first();
        $verifikasi = Verifikasi::where('sekolah_id', $user->sekolah_id)->first();*/
        /*$pakta = NULL;
        $verval = NULL;
        $verifikasi = NULL;
        $pengesahan = NULL;
        $sasaran = $user->sekolah->sekolah_sasaran;
        if($sasaran){
            $pakta = $sasaran->pakta_integritas;
            $verval = $sasaran->rapor_mutu;
            if($verval){
                if($verval->status_rapor->status != 'waiting'){
                    $verifikasi = $verval;
                }
                if($verval->status_rapor->status != 'terima'){
                    $pengesahan = $verval;
                }
            }
        }
        $data = [
            'instrumen' => ($instrumen == $user->nilai_instrumen_count),
            'hitung' => ($user->nilai_akhir) ? HelperModel::TanggalIndo($user->nilai_akhir->updated_at) : NULL,
            'pakta' => ($pakta) ? HelperModel::TanggalIndo($pakta->updated_at) : NULL,
            'verval' => ($verval) ? HelperModel::TanggalIndo($verval->updated_at) : NULL,
            'verifikasi' => ($verifikasi) ? HelperModel::TanggalIndo($verifikasi->created_at) : NULL,
            'pengesahan' => ($pengesahan) ? HelperModel::TanggalIndo($pengesahan->updated_at) : NULL,
        ];*/
        $data = HelperModel::rapor_mutu($request->user_id);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function cetak(Request $request){
        $data['all_komponen'] = Komponen::with(['aspek.instrumen' => function($query){
            $query->with(['jawaban' => function($query){
                $query->where('user_id', request()->user_id);
                $query->whereNull('verifikator_id');
            }]);
            $query->with(['subs']);
            $query->where('urut', 0);
        }])->get();
        $data['user'] = User::with(['sekolah'])->find(request()->user_id); 
        //return view('cetak.instrumen', $data);
        $pdf = PDF::loadView('cetak.instrumen', $data, [], [
            'format' => [220, 330],
        ]);
        //return $pdf->stream('instrumen.pdf');
		return $pdf->download('instrumen.pdf');
    }
    public function get_penjamin_mutu(){
        $users = User::where(function($query){
            $query->whereRoleIs('penjamin_mutu');
            if(request()->sekolah_id){
                $query->whereHas('sekolah_sasaran', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                });
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($posts) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $posts = $posts->where('name', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('email', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('username', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $users]);
    }
    public function sekolah_sasaran(Request $request){
        $tahun = Tahun_pendataan::where('periode_aktif', 1)->first();
        if($request->permintaan == 'add'){
            $insert = Sekolah_sasaran::updateOrCreate([
                'sekolah_id' => $request->sekolah_id,
                'verifikator_id' => $request->verifikator_id,
                'tahun_pendataan_id' => $tahun->tahun_pendataan_id,
            ]);
            if($insert){
                $response = [
                    'title' => 'Berhasil',
                    'text' => 'Sekolah sasaran berhasil ditambahkan',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'title' => 'Gagal',
                    'text' => 'Sekolah sasaran gagal ditambahkan',
                    'icon' => 'error',
                ];
            }
        } else {
            $delete = Sekolah_sasaran::where(function($query) use ($request, $tahun){
                $query->where('sekolah_id', $request->sekolah_id);
                $query->where('verifikator_id', $request->verifikator_id);
                $query->where('tahun_pendataan_id', $tahun->tahun_pendataan_id);
            })->delete();
            if($delete){
                $response = [
                    'title' => 'Berhasil',
                    'text' => 'Sekolah sasaran berhasil dihapus',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'title' => 'Gagal',
                    'text' => 'Sekolah sasaran gagal dihapus',
                    'icon' => 'error',
                ];
            }
        }
        return response()->json($response);
    }
    public function sekolah_sasaran_pendamping(Request $request){
        $tahun = Tahun_pendataan::where('periode_aktif', 1)->first();
        $sekolah = Sekolah_sasaran::where(function($query) use ($request, $tahun){
            $query->where('sekolah_id', $request->sekolah_id);
            $query->where('tahun_pendataan_id', $tahun->tahun_pendataan_id);
        })->first();
        $insert = 0;
        $delete = 0;
        if($request->permintaan == 'add'){
            if($sekolah){
                $sekolah->pendamping_id = $request->pendamping_id;
                $insert = $sekolah->save();
            } else {
                $verifikator = User::where('username', 'verifikator')->first();
                $insert = Sekolah_sasaran::create([
                    'sekolah_id' => $request->sekolah_id,
                    'pendamping_id' => $request->pendamping_id,
                    'verifikator_id' => $verifikator->user_id,
                    'tahun_pendataan_id' => $tahun->tahun_pendataan_id,
                ]);
            }
            if($insert){
                $response = [
                    'title' => 'Berhasil',
                    'text' => 'Sekolah sasaran berhasil ditambahkan',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'title' => 'Gagal',
                    'text' => 'Sekolah sasaran gagal ditambahkan',
                    'icon' => 'error',
                ];
            }
        } elseif($request->permintaan == 'ganti'){
            $sekolah_sasaran = Sekolah_sasaran::find($request->sekolah_sasaran_id);
            $sekolah_sasaran->pendamping_id = $request->pendamping_id;
            if($sekolah_sasaran->save()){
                $response = [
                    'title' => 'Berhasil',
                    'text' => 'Pendamping berhasil diganti',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'title' => 'Gagal',
                    'text' => 'Pendamping gagal diganti',
                    'icon' => 'error',
                ];
            }
        } else {
            if($sekolah){
                $sekolah->pendamping_id = NULL;
                $delete = $sekolah->save();
            }
            if($delete){
                $response = [
                    'title' => 'Berhasil',
                    'text' => 'Sekolah sasaran berhasil dihapus',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'title' => 'Gagal',
                    'text' => 'Sekolah sasaran gagal dihapus',
                    'icon' => 'error',
                ];
            }
        }
        return response()->json($response);
    }
    public function get_sekolah_sasaran($request){
        return $this->get_sekolah($request);
    }
    public function get_sekolah_sasaran_pendamping($request){
        return $this->get_sekolah($request);
    }
    public function status_coe(Request $request){
        if($request->status_coe){
            $text = 'Sekolah berhasil ditetapkan sebagai SMK CoE';
            $coe = Smk_coe::updateOrCreate([
                'sekolah_id' => $request->sekolah_id,
                'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
            ]);
            /*$verifikator = User::where('username', 'verifikator')->first();
            Sekolah_sasaran::updateOrCreate([
                'sekolah_id' => $request->sekolah_id,
                'verifikator_id' => $verifikator->user_id,
                'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
            ]);*/
        } else {
            $text = 'Sekolah berhasil digagalkan sebagai SMK CoE';
            $coe = Smk_coe::where('sekolah_id', $request->sekolah_id)->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->delete();
            Sekolah_sasaran::where('sekolah_id', $request->sekolah_id)->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->delete();
        }
        if($coe){
            $response = [
                'title' => 'Berhasil',
                'text' => $text,
                'icon' => 'success',
            ];
        } else {
            $response = [
                'title' => 'Gagal',
                'text' => 'Permintaan gagal. Silahkan coba beberapa saat lagi!',
                'icon' => 'error',
            ];
        }
        return response()->json($response);
    }
    public function get_pendamping(){
        $users = Pendamping::where(function($query){
            if(request()->sekolah_id){
                $query->whereHas('sekolah_sasaran', function($query){
                    $query->where('sekolah_sasaran.sekolah_id', request()->sekolah_id);
                });
            }
            $query->whereNotNull('token');
        //})->has('sekolah_sasaran')->withCount('sekolah_sasaran')->orderBy(request()->sortby, request()->sortbydesc)
        })->withCount('sekolah_sasaran')->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($posts) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $posts = $posts->where('nama', 'ILIKE', '%' . request()->q . '%')
                    ->orWhere('instansi', 'ILIKE', '%' . request()->q . '%')
                    ->orWhereHas('sekolah_sasaran', function($query){
                        $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                    })
                    ->orWhereHas('sekolah_sasaran', function($query){
                        $query->where('npsn', 'LIKE', '%' . request()->q . '%');
                    });
                    //->orWhere('pendamping_id', function($query){
                        //$query->select('pendamping_id')->from('sekolah_sasaran')->join('sekolah', 'sekolah_sasaran.sekolah_id', '=', 'sekolah.sekolah_id')->where('sekolah.nama', 'ILIKE', '%' . request()->q . '%');
                    //});
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $users]);
    }
    public function simpan_data(Request $request){
        if($request->route('query') == 'tanah'){
            $messages = [
                'nama.required'	=> 'Nama tidak boleh kosong',
                'no_sertifikat_tanah.required'	=> 'Nomor Sertifikat Tanah tidak boleh kosong',
                'panjang.required'	=> 'Panjang (m) tidak boleh kosong',
                'panjang.numeric'	=> 'Panjang (m) harus berupa angka',
                'lebar.required'	=> 'Lebar (m) tidak boleh kosong',
                'lebar.numeric'	=> 'Lebar (m) harus berupa angka',
                'luas.required' => 'Luas (m<sup>2</sup>) tidak boleh kosong',
                'luas.numeric'	=> 'Luas (m) harus berupa angka',
                'kepemilikan.required' => 'Kepemilikan tidak boleh kosong',
                'luas_lahan_tersedia.required' => 'Luas Lahan Tersedia (m<sup>2</sup>) tidak boleh kosong',
                'luas_lahan_tersedia.numeric'	=> 'Luas Lahan Tersedia (m<sup>2</sup>) harus berupa angka',
            ];
            $validator = Validator::make(request()->all(), [
                'nama' => 'required',
                'no_sertifikat_tanah' => 'required',
                'panjang' => 'required|numeric',
                'lebar' => 'required|numeric',
                'luas' => 'required|numeric',
                'kepemilikan' => 'required',
                'luas_lahan_tersedia' => 'required|numeric',
            ],
            $messages
            )->validate();
            $insert_data = Tanah::create([
                'tanah_id' => $request->tanah_id['tanah_id'],
                'nama' => $request->nama,
                'no_sertifikat_tanah' => $request->no_sertifikat_tanah,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'luas' => $request->luas,
                'luas_lahan_tersedia' => $request->luas_lahan_tersedia,
                'kepemilikan' => $request->kepemilikan,
                'keterangan' => $request->keterangan,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'bangunan'){
            $messages = [
                'tanah_id.required'	=> 'Tanah tidak boleh kosong',
                'nama.required'	=> 'Nama tidak boleh kosong',
                'imb.required'	=> 'Nomor IMB tidak boleh kosong',
                'panjang.required'	=> 'Panjang (m) tidak boleh kosong',
                'panjang.numeric'	=> 'Panjang (m) harus berupa angka',
                'lebar.required'	=> 'Lebar (m) tidak boleh kosong',
                'lebar.numeric'	=> 'Lebar (m) harus berupa angka',
                'luas.required' => 'Luas (m<sup>2</sup>) tidak boleh kosong',
                'luas.numeric'	=> 'Luas (m) harus berupa angka',
                'lantai.required' => 'Jumlah Lantai tidak boleh kosong',
                'lantai.numeric'	=> 'Jumlah Lantai harus berupa angka',
                'kepemilikan.required' => 'Kepemilikan tidak boleh kosong',
                'tahun_bangun.required' => 'Tahun Bangun tidak boleh kosong',
                'tahun_bangun.numeric'	=> 'Tahun Bangun harus berupa angka',
            ];
            $validator = Validator::make(request()->all(), [
                'tanah_id' => 'required',
                'nama' => 'required',
                'imb' => 'required',
                'panjang' => 'required|numeric',
                'lebar' => 'required|numeric',
                'luas' => 'required|numeric',
                'kepemilikan' => 'required',
                'lantai' => 'required|numeric',
                'tahun_bangun' => 'required|numeric',
            ],
            $messages
            )->validate();
            $insert_data = Bangunan::create([
                'tanah_id' => $request->tanah_id['tanah_id'],
                'nama' => $request->nama,
                'imb' => $request->imb,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'luas' => $request->luas,
                'lantai' => $request->lantai,
                'kepemilikan' => $request->kepemilikan,
                'tahun_bangun' => $request->tahun_bangun,
                'keterangan' => $request->keterangan,
                //'tanggal_sk' => $request->tanggal_sk,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        }
        return response()->json(['status' => 'failed', 'data' => NULL]);
    }
    public function reset_isian_instrumen(Request $request)
    {
        return Artisan::call('reset:rapor', ['npsn' => $request->npsn]);
    }
    public function update_data(Request $request)
    {
        if($request->route('query') == 'pendamping'){
            $messages = [
                'nama.required'	=> 'Nama tidak boleh kosong',
                'instansi.required'	=> 'Asal Instansi tidak boleh kosong',
                'email.required'	=> 'Email tidak boleh kosong',
                'email.email'	=> 'Email tidak valid',
                'nomor_hp.required'	=> 'Nomor Handphone tidak boleh kosong',
                'token.required'	=> 'Token tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'nama' => 'required',
                'instansi' => 'required',
                'email' => 'required|email',
                'nomor_hp' => 'required',
                'token' => 'required',
            ],
            $messages
            )->validate();
            $pendamping = Pendamping::find($request->id);
            $pendamping->nama = $request->nama;
            $pendamping->nip = $request->nip;
            $pendamping->nuptk = $request->nuptk;
            $pendamping->instansi = $request->instansi;
            $pendamping->email = $request->email;
            $pendamping->nomor_hp = $request->nomor_hp;
            $pendamping->token = $request->token;
            if($pendamping->save()){
                return response()->json(['status' => 'success', 'message' => 'Data Pendamping berhasil diperbaharui']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Data Pendamping gagal diperbaharui']);
            }
        }
        return response()->json(['status' => 'error', 'data' => NULL]);
    }
    public function delete_data(Request $request)
    {
        if($request->route('query') == 'pendamping'){
            $id = $request->route('id');
            Sekolah_sasaran::where('pendamping_id', $id)->update(['pendamping_id' => NULL]);
            $pendamping = Pendamping::find($id);
            if($pendamping->delete()){
                return response()->json(['title' => 'Berhasil', 'status' => 'success', 'message' => 'Data Pendamping berhasil dihapus']);
            } else {
                return response()->json(['title' => 'Gagal', 'status' => 'error', 'message' => 'Data Pendamping gagal dihapus']);
            }
        }
    }
    public function get_jurusan($request){
        $data = [
            //'sekolah_sasaran' => Sekolah_sasaran::find($request->sekolah_sasaran_id),
            'jurusan' => Jurusan::where('level_bidang_id', 12)->pluck('nama_jurusan', 'jurusan_id'),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_sektor($request){
        $data = [
            //'sekolah_sasaran' => Sekolah_sasaran::find($request->sekolah_sasaran_id),
            'sektor' => Sektor::pluck('nama', 'id'),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_verifikator($request){
        //$verifikator = User::whereRoleIs('penjamin_mutu')->select('name', 'token')->get();
        $data = [
            //'sekolah_sasaran' => Sekolah_sasaran::find($request->sekolah_sasaran_id),
            'verifikator' => User::whereRoleIs('penjamin_mutu')->pluck('name', 'user_id'),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_list_pendamping($request){
        //$verifikator = User::whereRoleIs('penjamin_mutu')->select('name', 'token')->get();
        $data = [
            //'sekolah_sasaran' => Pendamping::has('sekolah_sasaran')->pluck('nama', 'pendamping_id'),
            'pendamping' => Pendamping::whereNotNull('token')->pluck('nama', 'pendamping_id'),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function sektor_coe(Request $request){
        $sekolah_sasaran = Sekolah_sasaran::find($request->sekolah_sasaran_id);
        $sekolah_sasaran->sektor_id = $request->sektor_id;
        $sektor_coe = $sekolah_sasaran->save();
        if($sektor_coe){
            $response = [
                'title' => 'Berhasil',
                'text' => 'Sektor CoE berhasil diperbaharui',
                'icon' => 'success',
            ];
        } else {
            $response = [
                'title' => 'Gagal',
                'text' => 'Permintaan gagal. Silahkan coba beberapa saat lagi!',
                'icon' => 'error',
            ];
        }
        return response()->json($response);
    }
    public function get_komli(Request $request){
        /*
        $data = [
            //'sekolah_sasaran' => Sekolah_sasaran::find($request->sekolah_sasaran_id),
            'jurusan' => Jurusan::where('level_bidang_id', 12)->pluck('nama_jurusan', 'jurusan_id'),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
        */
        $all_data = Jurusan_sp::where('sekolah_id', $request->sekolah_id)->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama_jurusan', 'ilike', '%' . request()->q . '%');
        })->withCount(['rombongan_belajar', 'peserta_didik'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
}
