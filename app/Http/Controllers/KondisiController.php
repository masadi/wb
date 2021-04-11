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
    public function get_ruang($request){
        $data = Kondisi_ruang::where('ruang_id', $request->ruang_id)->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->first();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_data(Request $request){
        if($request->route('query') == 'ruang'){
            $messages = [
                'ruang_id.required'	=> ' tidak boleh kosong',
                'rusak_bata_dinding.required'	=> 'Kerusakan Batu Bata/Partisi (%) tidak boleh kosong',
                'ket_bata_dinding.required'	=> 'Keterangan Batu Bata/Partisi tidak boleh kosong',
                'rusak_daun_jendela.required'	=> 'Kerusakan Kaca (%) tidak boleh kosong',
                'ket_daun_jendela.required'	=> 'Keterangan Kaca tidak boleh kosong',
                'rusak_daun_pintu.required'	=> 'Kerusakan Pintu (%) tidak boleh kosong',
                'ket_daun_pintu.required'	=> 'Keterangan Pintu tidak boleh kosong',
                'rusak_kusen.required'	=> 'Kerusakan Kusen (%) tidak boleh kosong',
                'ket_kusen.required'	=> 'Keterangan Kusen tidak boleh kosong',
                'rusak_tutup_plafon.required'	=> 'Kerusakan Plafon (%) tidak boleh kosong',
                'ket_tutup_plafon.required'	=> 'Keterangan Plafon tidak boleh kosong',
                'rusak_tutup_lantai.required'	=> 'Kerusakan Penutup Lantai (%) tidak boleh kosong',
                'ket_penutup_lantai.required'	=> 'Keterangan Penutup Lantai tidak boleh kosong',
                'ket_inst_listrik.required'	=> 'Klasifikasi Kerusakan Instalasi Listrik tidak boleh kosong',
                'rusak_inst_listrik.required'	=> 'Kerusakan Instalasi Listrik (%) tidak boleh kosong',
                'ket_inst_air.required'	=> 'Klasifikasi Kerusakan Instalasi Air tidak boleh kosong',
                'rusak_inst_air.required'	=> 'Kerusakan Instalasi Air (%) tidak boleh kosong',
                'rusak_drainase.required'	=> 'Kerusakan Drainase Limbah (%) tidak boleh kosong',
                'ket_drainase.required'	=> 'Keterangan Drainase Limbah tidak boleh kosong',
                'rusak_finish_plafon.required'	=> 'Kerusakan Finishing Langit-langit (%) tidak boleh kosong',
                'ket_finish_plafon.required'	=> 'Keterangan Finishing Langit-langit tidak boleh kosong',
                'rusak_finish_dinding.required'	=> 'Kerusakan Finishing Dinding (%) tidak boleh kosong',
                'ket_finish_dinding.required'	=> 'Keterangan Finishing Dinding tidak boleh kosong',
                'rusak_finish_kpj.required'	=> 'Kerusakan Finishing Kusen/Pintu (%) tidak boleh kosong',
                'ket_finish_kpj.required'	=> 'Keterangan Finishing Kusen/Pintu tidak boleh kosong',
            ];
            $validator = Validator::make(request()->all(), [
                'ruang_id' => 'required',
                'rusak_bata_dinding' => 'required',
                'ket_bata_dinding' => 'required',
                'rusak_daun_jendela' => 'required',
                'ket_daun_jendela' => 'required',
                'rusak_daun_pintu' => 'required',
                'ket_daun_pintu' => 'required',
                'rusak_kusen' => 'required',
                'ket_kusen' => 'required',
                'rusak_tutup_plafon' => 'required',
                'ket_tutup_plafon' => 'required',
                'rusak_tutup_lantai' => 'required',
                'ket_penutup_lantai' => 'required',
                'ket_inst_listrik' => 'required',
                'rusak_inst_listrik' => 'required',
                'ket_inst_air' => 'required',
                'rusak_inst_air' => 'required',
                'rusak_drainase' => 'required',
                'ket_drainase' => 'required',
                'rusak_finish_plafon' => 'required',
                'ket_finish_plafon' => 'required',
                'rusak_finish_dinding' => 'required',
                'ket_finish_dinding' => 'required',
                'rusak_finish_kpj' => 'required',
                'ket_finish_kpj' => 'required',
            ],
            $messages
            )->validate();
            $insert_data = Kondisi_ruang::updateOrcreate(
                [
                    'ruang_id' => $request->ruang_id,
                    'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
                ],
                [
                    'rusak_bata_dinding' => $request->rusak_bata_dinding,
                    'ket_bata_dinding' => $request->ket_bata_dinding,
                    'rusak_daun_jendela' => $request->rusak_daun_jendela,
                    'ket_daun_jendela' => $request->ket_daun_jendela,
                    'rusak_daun_pintu' => $request->rusak_daun_pintu,
                    'ket_daun_pintu' => $request->ket_daun_pintu,
                    'rusak_kusen' => $request->rusak_kusen,
                    'ket_kusen' => $request->ket_kusen,
                    'rusak_tutup_plafon' => $request->rusak_tutup_plafon,
                    'ket_tutup_plafon' => $request->ket_tutup_plafon,
                    'rusak_tutup_lantai' => $request->rusak_tutup_lantai,
                    'ket_penutup_lantai' => $request->ket_penutup_lantai,
                    'ket_inst_listrik' => (is_array($request->ket_inst_listrik)) ? $request->ket_inst_listrik['label'] : $request->ket_inst_listrik,
                    'rusak_inst_listrik' => $request->rusak_inst_listrik,
                    'ket_inst_air' => (is_array($request->ket_inst_air)) ? $request->ket_inst_air['label'] : $request->ket_inst_air,
                    'rusak_inst_air' => $request->rusak_inst_air,
                    'rusak_drainase' => $request->rusak_drainase,
                    'ket_drainase' => $request->ket_drainase,
                    'rusak_finish_plafon' => $request->rusak_finish_plafon,
                    'ket_finish_plafon' => $request->ket_finish_plafon,
                    'rusak_finish_dinding' => $request->rusak_finish_dinding,
                    'ket_finish_dinding' => $request->ket_finish_dinding,
                    'rusak_finish_kpj' => $request->rusak_finish_kpj,
                    'ket_finish_kpj' => $request->ket_finish_kpj,
                ]
            );
            return response()->json(['status' => 'success', 'data' => $insert_data]);
        } elseif($request->route('query') == 'bangunan'){
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
