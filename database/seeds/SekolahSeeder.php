<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Sekolah;
use App\Ptk;
use App\User;
use App\Role;
class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_npsn = ['20606899', '20613916', '20606817'];
        foreach($all_npsn as $npsn){
            $response = Http::post('http://api.erapor-smk.net/api/v1/sekolah', [
                'npsn' => $npsn,
            ]);
            if($response->status()){
                $sekolah = json_decode($response->body());
                $sekolah = $sekolah->data;
                Sekolah::updateOrCreate(
                    ['sekolah_id' => $sekolah->sekolah_id],
                    [
                        'npsn' => $sekolah->npsn,
                        'nama' => $sekolah->nama,
                        'nss' => $sekolah->nss,
                        'alamat' => $sekolah->alamat,
                        'desa_kelurahan' => $sekolah->desa_kelurahan,
                        'kecamatan' => $sekolah->kecamatan,
                        'kode_wilayah' => $sekolah->kode_wilayah,
                        'kabupaten' => $sekolah->kabupaten,
                        'provinsi' => $sekolah->provinsi,
                        'kode_pos' => $sekolah->kode_pos,
                        'lintang' => $sekolah->lintang,
                        'bujur' => $sekolah->bujur,
                        'no_telp' => $sekolah->no_telp,
                        'no_fax' => $sekolah->no_fax,
                        'email' => $sekolah->email,
                        'website' => $sekolah->website,
                        'status_sekolah' => $sekolah->status_sekolah,
                    ]
                );
                $user_sekolah = User::updateOrCreate(
                    ['email' => $sekolah->email],
                    [
                        'sekolah_id' => $sekolah->sekolah_id,
                        'username' => $sekolah->npsn,
                        'name' => $sekolah->nama,
                        'password' => bcrypt($sekolah->npsn)
                    ]
                );
                if(!$user_sekolah->hasRole('sekolah')){
                    $role = Role::where('name', 'sekolah')->first();
                    $user_sekolah->attachRole($role);
                }
                foreach($sekolah->ptk as $ptk){
                    $new_ptk = Ptk::updateOrCreate(
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
                    $user = User::updateOrCreate(
                        ['email' => $ptk->email],
                        [
                            'sekolah_id' => $ptk->sekolah_id,
                            'username' => $ptk->nuptk,
                            'name' => $ptk->nama,
                            'password' => bcrypt('12345678')
                        ]
                    );
                    if(!$user->hasRole('ptk')){
                        $role = Role::where('name', 'ptk')->first();
                        $user->attachRole($role);
                    }
                }
            }
        }
    }
}
