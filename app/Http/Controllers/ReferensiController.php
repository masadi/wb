<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Komponen;
use App\Aspek;
use App\Atribut;
use App\Indikator;
use App\Instrumen;
use App\Sekolah;
use App\Ptk;
use App\Peserta_didik;
use App\Nilai_instrumen;
use App\Nilai_akhir;
use App\Pakta_integritas;
use App\Verval;
use App\Verifikasi;
use App\User;
use App\HelperModel;
use App\Sekolah_sasaran;
use App\Tahun_pendataan;
use App\Smk_coe;
use App\Pendamping;
use Carbon\Carbon;
use File;
use Validator;
use PDF;
use Artisan;
class ReferensiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_komponen($request)
    {
        $all_data = Komponen::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_aspek($request)
    {
        $all_data = Aspek::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_atribut($request)
    {
        $all_data = Atribut::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_indikator($request)
    {
        $all_data = Indikator::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
                    if(request()->verifikator_id){
                        if(request()->permintaan == 'add'){
                            $query->doesntHave('sekolah_sasaran');
                        } else {
                            $query->whereHas('sekolah_sasaran', function ($query){
                                $query->where('verifikator_id', request()->verifikator_id);
                            });
                        }
                    }
                    if(request()->pendamping_id){
                        if(request()->permintaan == 'add'){
                            $query->doesntHave('pendamping');
                        } else {
                            $query->whereHas('pendamping', function ($query){
                                $query->where('pendamping_id', request()->pendamping_id);
                            });
                        }
                    }
                    if(request()->verifikasi_id){
                        $query->whereHas('sekolah_sasaran', function ($query) {
                            $query->where('verifikator_id', request()->verifikasi_id);
                            $query->with('pakta_integritas');
                        });
                    }
                });
                $query->orWhere(function($query){
                    $query->where('npsn', 'ilike', '%' . request()->q . '%');
                    if(request()->sekolah_id){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                    if(request()->verifikator_id){
                        if(request()->permintaan == 'add'){
                            $query->doesntHave('sekolah_sasaran');
                        } else {
                            $query->whereHas('sekolah_sasaran', function ($query){
                                $query->where('verifikator_id', request()->verifikator_id);
                            });
                        }
                    }
                    if(request()->pendamping_id){
                        if(request()->permintaan == 'add'){
                            $query->doesntHave('pendamping');
                        } else {
                            $query->whereHas('pendamping', function ($query){
                                $query->where('pendamping_id', request()->pendamping_id);
                            });
                        }
                    }
                    if(request()->verifikasi_id){
                        $query->whereHas('sekolah_sasaran', function ($query) {
                            $query->where('verifikator_id', request()->verifikasi_id);
                            $query->with('pakta_integritas');
                        });
                    }
                });
                //$query->orWhere('npsn', 'ilike', '%' . request()->q . '%');
                //$query->orWhere('kecamatan', 'ilike', '%' . request()->q . '%');
                //$query->orWhere('kabupaten', 'ilike', '%' . request()->q . '%');
                //$query->orWhere('provinsi', 'ilike', '%' . request()->q . '%');
            } else {
                if(request()->sekolah_id){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
                if(request()->verifikator_id){
                    if(request()->permintaan == 'add'){
                        $query->doesntHave('sekolah_sasaran');
                    } else {
                        $query->whereHas('sekolah_sasaran', function ($query){
                            $query->where('verifikator_id', request()->verifikator_id);
                        });
                    }
                }
                if(request()->pendamping_id){
                    $query->has('smk_coe');
                    $query->has('sekolah_sasaran');
                    if(request()->permintaan == 'add'){
                        $query->doesntHave('pendamping');
                    } else {
                        $query->whereHas('pendamping', function ($query){
                            $query->where('pendamping.pendamping_id', request()->pendamping_id);
                        });
                    }
                }
                if(request()->verifikasi_id){
                    $query->whereHas('sekolah_sasaran', function ($query) {
                        $query->where('verifikator_id', request()->verifikasi_id);
                        $query->with('pakta_integritas');
                    });
                }
            }
        })->with(['smk_coe', 'user', 'sekolah_sasaran' => function($query){
            $query->with(['pakta_integritas', 'verifikator']);
        }])->orderBy($sortBy, request()->sortbydesc)
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
        } else {
            $text = 'Sekolah berhasil digagalkan sebagai SMK CoE';
            $coe = Smk_coe::where('sekolah_id', $request->sekolah_id)->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->delete();
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
        })->has('sekolah_sasaran')->withCount('sekolah_sasaran')->orderBy(request()->sortby, request()->sortbydesc)
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
        if($request->route('query') == 'pendamping'){
            $messages = [
                'nama.required'	=> 'Nama tidak boleh kosong',
                'instansi.required'	=> 'Asal Instansi tidak boleh kosong',
                'email.required'	=> 'Email tidak boleh kosong',
                'email.email'	=> 'Email tidak valid',
                'nomor_hp.required'	=> 'Nomor Handphone tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'nama' => 'required',
                'instansi' => 'required',
                'email' => 'required|email',
                'nomor_hp' => 'required',
            ],
            $messages
            )->validate();
            $pendamping = Pendamping::create([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'nuptk' => $request->nuptk,
                'instansi' => $request->instansi,
                'email' => $request->email,
                'nomor_hp' => $request->nomor_hp,
            ]);
            return response()->json(['status' => 'success', 'data' => $pendamping]);
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
            ];
            $validator = Validator::make(request()->all(), [
                'nama' => 'required',
                'instansi' => 'required',
                'email' => 'required|email',
                'nomor_hp' => 'required',
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
}
