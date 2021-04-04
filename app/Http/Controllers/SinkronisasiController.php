<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jurusan_sp;
use App\Ptk;
use App\Sekolah;
use App\Peserta_didik;
use App\Anggota_rombel;

class SinkronisasiController extends Controller
{
    public function komli(Request $request){
        $response = Http::post('http://api.erapor-smk.net/api/v1/jurusan_sp', [
            'npsn' => $request->npsn,
        ]);
        $data = json_decode($response->body());
        foreach($data->data as $jurusan_sp){
            Jurusan_sp::updateOrCreate(
                [
                    'jurusan_sp_id' => $jurusan_sp->jurusan_sp_id_dapodik,
                    'sekolah_id' => $jurusan_sp->sekolah_id,
                ],
                [
                    'jurusan_id' => $jurusan_sp->jurusan_id,
                    'nama_jurusan_sp' => $jurusan_sp->nama_jurusan_sp,
                ]
            );
        }
    }
    public function ptk(Request $request){
        $response = Http::post('http://api.erapor-smk.net/api/v1/ptk', [
            'npsn' => $request->npsn,
        ]);
        $data = json_decode($response->body());
        foreach($data->data as $ptk){
            Ptk::updateOrCreate(
                ['ptk_id' => $ptk->guru_id_dapodik],
                [
                    'sekolah_id' => $ptk->sekolah_id,
                    'nama' => $ptk->nama,
                    'nuptk' => $ptk->nuptk,
                    'nip' => $ptk->nip,
                    'jenis_kelamin' => $ptk->jenis_kelamin,
                    'tempat_lahir' => $ptk->tempat_lahir,
                    'tanggal_lahir' => $ptk->tanggal_lahir,
                    'nik' => $ptk->nik,
                    'jenis_ptk_id' => $ptk->jenis_ptk_id,
                    'agama_id' => $ptk->agama_id,
                    'status_kepegawaian_id' => $ptk->status_kepegawaian_id,
                    'alamat' => $ptk->alamat,
                    'rt' => $ptk->rt,
                    'rw' => $ptk->rw,
                    'desa_kelurahan' => $ptk->desa_kelurahan,
                    'kecamatan' => $ptk->kecamatan,
                    'kode_wilayah' => $ptk->kode_wilayah,
                    'kode_pos' => $ptk->kode_pos,
                    'no_hp' => $ptk->no_hp,
                    'email' => $ptk->email,
                ]
            );
        }
    }
    public function pd(Request $request){
        $sekolah = Sekolah::where('npsn', $request->npsn)->first();
        $response = Http::post('http://api.erapor-smk.net/api/v1/count_pd', [
            'sekolah_id' => $sekolah->sekolah_id,
        ]);
        $limit = 100;
        $data = json_decode($response->body());
        $count = $data->data;
        for ($counter = 0; $counter <= $count; $counter += $limit) {
            $response = Http::retry(3, 100)->post('http://api.erapor-smk.net/api/v1/all_pd', [
                'offset' => $counter,
                'sekolah_id' => $sekolah->sekolah_id,
                'semester_id' => 20201,
            ]);
            $data_pd = json_decode($response->body());
            foreach($data_pd->data as $pd){
                Peserta_didik::updateOrCreate(
                    ['peserta_didik_id' => $pd->guru_id_dapodik],
                    [
                        'sekolah_id' => $pd->sekolah_id,
                        'nama' => $pd->nama,
                        'no_induk' => $pd->no_induk,
                        'nisn' => $pd->nisn,
                        'nik' => $pd->nik,
                        'jenis_kelamin' => $pd->jenis_kelamin,
                        'tempat_lahir' => $pd->tempat_lahir,
                        'tanggal_lahir' => $pd->tanggal_lahir,
                        'agama_id' => $pd->agama_id,
                        'alamat' => $pd->alamat,
                        'rt' => $pd->rt,
                        'rw' => $pd->rw,
                        'desa_kelurahan' => $pd->desa_kelurahan,
                        'kecamatan' => $pd->kecamatan,
                        'kode_wilayah' => $pd->kode_wilayah,
                        'kode_pos' => $pd->kode_pos,
                        'no_hp' => $pd->no_hp,
                        'email' => $pd->email,
                        'nama_ayah' => $pd->nama_ayah,
                        'nama_ibu' => $pd->nama_ibu,
                        'kerja_ayah' => $pd->kerja_ayah,
                        'kerja_ibu' => $pd->kerja_ibu,
                    ]
                );
            }
        }
    }
    public function server(Request $request){
        $sekolah = Sekolah::where('npsn', $request->npsn)->first();
        $response = Http::withBasicAuth('admin', '1234')->withHeaders([
            'x-api-key' => $sekolah->sekolah_id,
        ])->post('http://103.40.55.242/erapor_server/api/status', [
            'username_dapo'		=> $sekolah->email,
			'password_dapo'		=> $sekolah->npsn,
			'tahun_ajaran_id'	=> 2020,
			'semester_id'		=> 20201,
			'sekolah_id'		=> $sekolah->sekolah_id,
			'npsn'				=> $sekolah->npsn
        ]);
        $data = json_decode($response->body());
        dd($data);
    }
}
