<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rapor_mutu;
use App\User;
use App\Sekolah_sasaran;
use App\HelperModel;
use Validator;
class ValidasiController extends Controller
{
    public function index(Request $request){
        $data = Rapor_mutu::with(['status_rapor', 'sekolah' => function($query){
            $query->with(['sekolah_sasaran', 'user.nilai_akhir']);
        }, 'penjamin_mutu.nilai_akhir_penjamin_mutu'])->where(function($query){
            if(request()->data == 'validasi'){
                $query->whereHas('proses');
                //$query->orWhereHas('');
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($data) {
                $data = $data->where('name', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('email', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('username', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_data(Request $request){
        if($request->verifikator_id && !$request->sekolah_sasaran_id){
            $all_data = Sekolah_sasaran::with('sekolah.user')->where('verifikator_id', $request->verifikator_id)->get();
            if($all_data->count()){
                foreach($all_data as $data){
                    $record= [];
                    $record['value'] 	= $data->sekolah_sasaran_id;
                    $record['text'] 	= $data->sekolah->nama;
                    $record['user_id'] 	= $data->sekolah->user->user_id;
                    $output['result'][] = $record;
                }
            } else {
                $record['value'] 	= '';
                $record['text'] 	= 'Tidak ditemukan data sekolah';
                $record['user_id'] 	= '';
                $output['result'][] = $record;
            }
        } elseif($request->verifikator_id && $request->sekolah_sasaran_id){
            $output = Rapor_mutu::with(['sekolah' => function($query) use ($request){
                $query->with(['berita_acara' => function($query) use ($request){
                    $query->where('berita_acara.sekolah_sasaran_id', $request->sekolah_sasaran_id);
                    $query->where('berita_acara.verifikator_id', $request->verifikator_id);
                }]);
                $query->withCount(['nilai_instrumen' => function($query){
                    $query->whereNull('verifikator_id');
                }]);
            }, 'penjamin_mutu' => function($query) use ($request){
                $query->withCount(['isian_instrumen', 'koreksi_instrumen' => function($query) use ($request){
                    $query->whereNotIn('nilai', function($query) use ($request){
                        $query->select('nilai')->from('nilai_instrumen')->where(function($query) use ($request){
                            $query->where('user_id', $request->user_id);
                            $query->whereNull('verifikator_id');
                        });
                    });
                }]);
            }])->where(function($query) use ($request){
                $query->whereHas('waiting');
                $query->where('sekolah_sasaran_id', $request->sekolah_sasaran_id);
                $query->where('verifikator_id', $request->verifikator_id);
            })->first();
        } else {
            $penjamin_mutu = User::where(function($query){
                $query->whereRoleIs('penjamin_mutu');
            })->get();
            if($penjamin_mutu->count()){
                foreach($penjamin_mutu as $penjamin){
                    $record= [];
                    $record['value'] 	= $penjamin->user_id;
                    $record['text'] 	= $penjamin->name;
                    $output['result'][] = $record;
                }
            } else {
                $record['value'] 	= '';
                $record['text'] 	= 'Tidak ditemukan data penjamin mutu';
                $output['result'][] = $record;
            }
        }
        return response()->json(['status' => 'success', 'data' => $output]);
    }
    public function post_data(Request $request){
        $messages = [
            'berita_acara.accepted' => 'Berita Acara harus di konfirmasi',
            'instrumen_sekolah.accepted' => 'Isian Instrumen Sekolah harus di konfirmasi',
            'instrumen_penjamin_mutu.accepted' => 'Isian Instrumen Tim Penjamin Mutu harus di konfirmasi',
            'instrumen_koreksi.accepted' => 'Isian Instrumen Koreksi harus di konfirmasi',
            'laporan.accepted' => 'Laporan Hasil Supervisi harus di konfirmasi',
            'verifikator_id.required' => 'Tim Penjamin Mutu tidak boleh kosong',
            'sekolah_sasaran_id.required' => 'Sekolah tidak boleh kosong',
            'rapor_mutu_id.required' => 'Rapor Mutu tidak boleh kosong',
            'rapor_mutu_id.exists' => 'Rapor Mutu tidak tidak ditemukan di database',
		];
		$validator = Validator::make(request()->all(), [
            'berita_acara' => 'accepted',
            'instrumen_sekolah' => 'accepted',
            'instrumen_penjamin_mutu' => 'accepted',
            'instrumen_koreksi' => 'accepted',
            'laporan' => 'accepted',
            'verifikator_id' => 'required',
            'sekolah_sasaran_id' => 'required',
            'rapor_mutu_id' => 'required|exists:rapor_mutu,rapor_mutu_id',
		 ],
		$messages
        )->validate();
        $rapor_mutu = Rapor_mutu::find($request->rapor_mutu_id);
        $rapor_mutu->status_rapor_id = HelperModel::status_rapor_mutu('proses');
        $rapor_mutu->save();
        $respone = [
            'message' => 'Validasi berhasil disimpan',
            'icon' => 'success',
        ];
        return response()->json($respone);
    }
}
