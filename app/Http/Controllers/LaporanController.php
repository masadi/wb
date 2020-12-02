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
        $pendamping = Pendamping::find($request->pendamping_id);
        $sekolah = Sekolah::with('sekolah_sasaran')->find($request->sekolah_id);
        $jenis_laporan = $request->jenis_laporan_id;
        $laporan = Laporan::where('jenis_laporan_id', $request->jenis_laporan_id)->where('pendamping_id', $request->pendamping_id)->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->first();
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
                $key => $request->pendamping_id,
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
}
