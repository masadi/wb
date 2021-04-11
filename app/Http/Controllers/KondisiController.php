<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kondisi_ruang;
use App\Kondisi_bangunan;
use App\HelperModel;
use Validator;

class KondisiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_bangunan($request){
        $data = Kondisi_bangunan::where('bangunan_id', $request->bangunan_id)->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->first();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_data(Request $request){
        if($request->route('query') == 'bangunan'){
            /*
    bangunan_id	"ef075531-6020-434f-b2a2-04ebf444427c"
    ket_kudakuda_atap	"b"
    	"c"
    ket_pondasi	"Tidak ada kerusakan"
    ket_sloop_kolom_balok	"a"
    	"BUKAN DAK"
    rusak_kudakuda_atap	"2"
    rusak_plester_struktur	"3"
    rusak_pondasi	0
    rusak_sloop_kolom_balok	"1"
    rusak_tutup_atap	"4"

*/
            $messages = [
                'bangunan_id.required'	=> 'ID Bangunan tidak boleh kosong',
                'rusak_pondasi.required'	=> 'Kerusakan Pondasi tidak boleh kosong',
                'rusak_sloop_kolom_balok.required'	=> 'Kerusakan Kolom tidak boleh kosong',
                'rusak_kudakuda_atap.required'	=> 'Kerusakan Balok tidak boleh kosong',
                'rusak_plester_struktur.required'	=> 'Kerusakan Pelat Lantai tidak boleh kosong',
                'rusak_tutup_atap.required'	=> 'Kerusakan Atap tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'bangunan_id' => 'required',
                'rusak_pondasi' => 'required',
                'rusak_sloop_kolom_balok' => 'required',
                'rusak_kudakuda_atap' => 'required',
                'rusak_plester_struktur' => 'required',
                'rusak_tutup_atap' => 'required',
            ],
            $messages
            )->validate();
            $insert_data = Kondisi_bangunan::updateOrcreate(
                [
                    'bangunan_id' => $request->bangunan_id,
                    'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
                ],
                [
                    'rusak_pondasi' => $request->rusak_pondasi,
                    'ket_pondasi' => (is_array($request->ket_pondasi)) ? $request->ket_pondasi['label'] : $request->ket_pondasi,
                    'rusak_sloop_kolom_balok' => $request->rusak_sloop_kolom_balok,
                    'ket_sloop_kolom_balok' => $request->ket_sloop_kolom_balok,
                    'rusak_kudakuda_atap' => $request->rusak_kudakuda_atap,
                    'ket_kudakuda_atap' => $request->ket_kudakuda_atap,
                    'rusak_plester_struktur' => $request->rusak_plester_struktur,
                    'ket_plester_struktur' => $request->ket_plester_struktur,
                    'rusak_tutup_atap' => $request->rusak_tutup_atap,
                    'ket_tutup_atap' => $request->ket_tutup_atap,
                ]
            );
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        }
        return response()->json(['status' => 'failed', 'data' => NULL]);
    }
}
