<?php

use Illuminate\Database\Seeder;
use App\Sekolah;
use App\HelperModel;
use App\Smk_coe;
use App\User;
use App\Role;
use App\Wilayah;
class DapoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sekolah::whereHas('sekolah_sasaran')->chunk(200, function ($data_sekolah) {
            foreach ($data_sekolah as $sekolah) {
                Smk_coe::updateOrCreate([
                    'sekolah_id' => $sekolah->sekolah_id,
                    'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
                ]);
            }
        });
        $path = database_path('data/dapo');
        $files = File::files($path);
        $kosong = [];
        foreach($files as $file){
            $content = json_decode($file->getContents());
            foreach($content as $item){
                $response = Http::post('http://api.erapor-smk.net/api/v1/sekolah', [
                    'npsn' => $item->npsn,
                ]);
                if($response->status() == 200){
                    $sekolah = json_decode($response->body());
                    $sekolah = $sekolah->data;
                    if($sekolah){
                        $wilayah = Wilayah::with('parrentRecursive')->withTrashed()->find($sekolah->kode_wilayah);
                        $kecamatan_id = NULL;
                        $kabupaten_id = NULL;
                        $provinsi_id = NULL;
                        if($wilayah->parrentRecursive){
                            $kecamatan_id = $wilayah->parrentRecursive->kode_wilayah;
                            if($wilayah->parrentRecursive->parrentRecursive){
                                $kabupaten_id = $wilayah->parrentRecursive->parrentRecursive->kode_wilayah;
                            }
                            if($wilayah->parrentRecursive->parrentRecursive->parrentRecursive){
                                $provinsi_id = $wilayah->parrentRecursive->parrentRecursive->parrentRecursive->kode_wilayah;
                            }
                        }
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
                                'kecamatan_id' => $kecamatan_id,
                                'kabupaten_id' => $kabupaten_id,
                                'provinsi_id' => $provinsi_id,
                            ]
                        );
                        $email = ($sekolah->email) ? $sekolah->email : $sekolah->npsn.'@apmsmk.net';
                        $user_sekolah = User::updateOrCreate(
                            ['email' => $email],
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
                    } else {
                        echo $item->npsn."\n";
                        $kosong[] = $item->npsn;
                    }
                } else {
                    echo $item->npsn."\n";
                }
            }
        }
        Storage::disk('public')->put('kosong.json', json_encode($kosong));
    }
}
