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
use App\Jenis_prasarana;
use App\Jenis_sarana;
use App\Status_kepemilikan_sarpras;
use App\Mata_pelajaran;

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
        })->with(['sekolah'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
        })->with(['tanah.sekolah'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
        })->with(['bangunan.tanah.sekolah'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
        })->with(['ruang.bangunan.tanah.sekolah', 'kepemilikan', 'jenis_sarana'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
        })->with(['sekolah', 'kepemilikan', 'jenis_sarana'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
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
        })->with(['sekolah', 'mata_pelajaran'])->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_sekolah($request){
        $all_data = Sekolah::select('sekolah_id', 'nama')->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_mapel($request){
        $all_data = Mata_pelajaran::select('mata_pelajaran_id', 'nama')->whereHas('mapel', function($query) use ($request){
            $query->where('tingkat_pendidikan_id', $request->tingkat_pendidikan_id);
        })->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_tanah($request){
        $all_data = Tanah::select('tanah_id', 'nama')->where('sekolah_id', $request->sekolah_id)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_bangunan($request){
        $all_data = Bangunan::select('bangunan_id', 'nama')->where('tanah_id', $request->tanah_id)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_ruang($request){
        $all_data = Ruang::select('ruang_id', 'nama')->where('bangunan_id', $request->bangunan_id)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_jenis_prasarana($request){
        $all_data = Jenis_prasarana::select('id', 'nama')->where('a_ruang', 1)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_jenis_sarana($request){
        $all_data = Jenis_sarana::select('id', 'nama')->where($request->data, 1)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_kepemilikan($request){
        $all_data = Status_kepemilikan_sarpras::select('kepemilikan_sarpras_id', 'nama')->get();//->pluck('nama', 'sekolah_id');
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
    public function simpan_data(Request $request){
        if($request->route('query') == 'tanah'){
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
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
                'sekolah_id' => 'required',
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
                'sekolah_id' => $request->sekolah_id['sekolah_id'],
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
            $a = $request->tahun_bangun;
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
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
                'kepemilikan_sarpras_id.required' => 'Kepemilikan tidak boleh kosong',
                'tahun_bangun.required' => 'Tahun Bangun tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'tanah_id' => 'required',
                'nama' => 'required',
                'imb' => 'required',
                'panjang' => 'required|numeric',
                'lebar' => 'required|numeric',
                'luas' => 'required|numeric',
                'kepemilikan_sarpras_id' => 'required',
                'lantai' => 'required|numeric',
                'tahun_bangun' => 'required',
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
                'kepemilikan_sarpras_id' => $request->kepemilikan_sarpras_id['kepemilikan_sarpras_id'],
                'tahun_bangun' => date('Y', strtotime($request->tahun_bangun)),
                'keterangan' => $request->keterangan,
                'tanggal_sk' => $request->tanggal_sk,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'ruang'){
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
                'tanah_id.required'	=> 'Tanah tidak boleh kosong',
                'bangunan_id.required'	=> 'Bangunan tidak boleh kosong',
                'jenis_prasarana_id.required'	=> 'Jenis Ruang tidak boleh kosong',
                'kode.required'	=> 'Panjang (m) tidak boleh kosong',
                'nama.required'	=> 'Panjang (m) harus berupa angka',
                'lantai_ke.numeric' => 'Lantai Ke- harus berupa angka',
                'panjang.numeric' => 'Panjang harus berupa angka',
                'lebar.numeric' => 'Lebar harus berupa angka',
                'luas.numeric' => 'Luas harus berupa angka',
                'luas_plester.numeric' => 'Luas Plester harus berupa angka',
                'luas_plafon.numeric' => 'Luas Plafon harus berupa angka',
                'luas_dinding.numeric' => 'Luas Dinding harus berupa angka',
                'luas_daun_jendela.numeric' => 'Luas Daun Jendela harus berupa angka',
                'luas_kusen.numeric' => 'Luas Kusen harus berupa angka',
                'luas_tutup_lantai.numeric' => 'Luas Tutup Lantai harus berupa angka',
                'jumlah_instalasi_listrik.numeric' => 'Jumlah Instalasi Listrik harus berupa angka',
                'panjang_instalasi_air.numeric' => 'Panjang Instalasi Air harus berupa angka',
                'jumlah_instalasi_air.numeric' => 'Jumlah Instalasi Air harus berupa angka',
                'panjang_drainase.numeric' => 'Panjang Drainase harus berupa angka',
                'luas_finish_struktur.numeric' => 'Luas Finish Struktur harus berupa angka',
                'luas_finish_plafon.numeric' => 'Luas Finish Plafon harus berupa angka',
                'luas_finish_dinding.numeric' => 'Luas Finish Dinding harus berupa angka',
                'luas_finish_kpj.numeric' => 'Luas Finish KPJ harus berupa angka',
            ];
            $validator = Validator::make(request()->all(), [
                'sekolah_id' => 'required',
                'tanah_id' => 'required',
                'bangunan_id' => 'required',
                'jenis_prasarana_id' => 'required',
                'kode' => 'required',
                'nama' => 'required',
                'lantai_ke' => 'numeric',
                'panjang' => 'numeric',
                'lebar' => 'numeric',
                'luas' => 'numeric',
                'luas_plester' => 'numeric',
                'luas_plafon' => 'numeric',
                'luas_dinding' => 'numeric',
                'luas_daun_jendela' => 'numeric',
                'luas_kusen' => 'numeric',
                'luas_tutup_lantai' => 'numeric',
                'jumlah_instalasi_listrik' => 'numeric',
                'panjang_instalasi_air' => 'numeric',
                'jumlah_instalasi_air' => 'numeric',
                'panjang_drainase' => 'numeric',
                'luas_finish_struktur' => 'numeric',
                'luas_finish_plafon' => 'numeric',
                'luas_finish_dinding' => 'numeric',
                'luas_finish_kpj' => 'numeric',
            ],
            $messages
            )->validate();
            $insert_data = Ruang::create([
                'jenis_prasarana_id' => $request->jenis_prasarana_id['id'],
                'bangunan_id' => $request->bangunan_id['bangunan_id'],
                'kode' => $request->kode,
                'nama' => $request->nama,
                'registrasi' => $request->registrasi,
                'lantai_ke' => $request->lantai_ke,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'luas' => $request->luas,
                'luas_plester' => $request->luas_plester,
                'luas_plafon' => $request->luas_plafon,
                'luas_dinding' => $request->luas_dinding,
                'luas_daun_jendela' => $request->luas_daun_jendela,
                'luas_kusen' => $request->luas_kusen,
                'luas_tutup_lantai' => $request->luas_tutup_lantai,
                'jumlah_instalasi_listrik' => $request->jumlah_instalasi_listrik,
                'panjang_instalasi_air' => $request->panjang_instalasi_air,
                'jumlah_instalasi_air' => $request->jumlah_instalasi_air,
                'panjang_drainase' => $request->panjang_drainase,
                'luas_finish_struktur' => $request->luas_finish_struktur,
                'luas_finish_plafon' => $request->luas_finish_plafon,
                'luas_finish_dinding' => $request->luas_finish_dinding,
                'luas_finish_kpj' => $request->luas_finish_kpj,
                'keterangan' => $request->keterangan,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'alat'){
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
                'tanah_id.required'	=> 'Tanah tidak boleh kosong',
                'bangunan_id.required'	=> 'Bangunan tidak boleh kosong',
                'ruang_id.required'	=> 'Ruang tidak boleh kosong',
                'jenis_sarana_id.required'	=> 'Jenis Sarana tidak boleh kosong',
                'nama.required'	=> 'Nama tidak boleh kosong',
                'kepemilikan_sarpras_id.required'	=> 'Kepemilikan tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'sekolah_id' => 'required',
                'tanah_id' => 'required',
                'bangunan_id' => 'required',
                'ruang_id' => 'required',
                'jenis_sarana_id' => 'required',
                'nama' => 'required',
                'kepemilikan_sarpras_id' => 'required',
            ],
            $messages
            )->validate();
            $insert_data = Alat::create([
                'jenis_sarana_id' => $request->jenis_sarana_id['id'],
                'ruang_id' => $request->ruang_id['ruang_id'],
                'nama' => $request->nama,
                'spesifikasi' => $request->spesifikasi,
                'kepemilikan_sarpras_id' => $request->kepemilikan_sarpras_id['kepemilikan_sarpras_id'],
                'keterangan' => $request->keterangan,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'angkutan'){
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
                'jenis_sarana_id.required'	=> 'Jenis Sarana tidak boleh kosong',
                'nama.required'	=> 'Nama tidak boleh kosong',
                'spesifikasi.required'	=> 'Spesifikasi tidak boleh kosong',
                'merk.required'	=> 'Merk tidak boleh kosong',
                'no_polisi.required'	=> 'Nomor Polisi tidak boleh kosong',
                'no_bpkb.required'	=> 'Nomor BPKB tidak boleh kosong',
                'kepemilikan_sarpras_id.required'	=> 'Kepemilikan tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'sekolah_id' => 'required',
                'jenis_sarana_id' => 'required',
                'nama' => 'required',
                'spesifikasi' => 'required',
                'merk' => 'required',
                'no_polisi' => 'required',
                'no_bpkb' => 'required',
                'kepemilikan_sarpras_id' => 'required',
            ],
            $messages
            )->validate();
            $insert_data = Angkutan::create([
                'sekolah_id' => $request->sekolah_id['sekolah_id'],
                'jenis_sarana_id' => $request->jenis_sarana_id['id'],
                'nama' => $request->nama,
                'spesifikasi' => $request->spesifikasi,
                'merk' => $request->merk,
                'no_polisi' => $request->no_polisi,
                'kepemilikan_sarpras_id' => $request->kepemilikan_sarpras_id['kepemilikan_sarpras_id'],
                'keterangan' => $request->keterangan,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'buku'){
            $messages = [
                'sekolah_id.required'	=> 'Sekolah tidak boleh kosong',
                'kode.required'	=> 'Kode Buku tidak boleh kosong',
                'judul.required'	=> 'Judul tidak boleh kosong',
                'mata_pelajaran_id.required'	=> 'Mata Pelajaran tidak boleh kosong',
                'nama_penerbit.required'	=> 'Nama Penerbit tidak boleh kosong',
                'isbn_issn.required'	=> 'ISBN/ISSN tidak boleh kosong',
                'kelas.required'	=> 'Kelas tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'sekolah_id' => 'required',
                'kode' => 'required',
                'judul' => 'required',
                'mata_pelajaran_id' => 'required',
                'nama_penerbit' => 'required',
                'isbn_issn' => 'required',
                'kelas' => 'required',
            ],
            $messages
            )->validate();
            $insert_data = Buku::create([
                'sekolah_id' => $request->sekolah_id['sekolah_id'],
                'kode' => $request->kode,
                'judul' => $request->judul,
                'mata_pelajaran_id' => $request->mata_pelajaran_id['mata_pelajaran_id'],
                'nama_penerbit' => $request->nama_penerbit,
                'isbn_issn' => $request->isbn_issn,
                'kelas' => $request->kelas,
                'keterangan' => $request->keterangan,
            ]);
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        }
        return response()->json(['status' => 'failed', 'data' => NULL]);
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
}
