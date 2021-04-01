<?php

use Illuminate\Database\Seeder;
use App\Sekolah;
use App\User;
class WilayahSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sekolah::has('sekolah_sasaran')->with(['smk_coe', 'sekolah_sasaran'])->chunk(200, function ($data) {
            foreach ($data as $sekolah) {
                if(!$sekolah->smk_coe){
                    $sekolah->sekolah_sasaran->delete();
                }
            }
        });
        Sekolah::with('wilayah')->orderBy('sekolah_id')->chunk(200, function ($data) {
            foreach ($data as $sekolah) {
                if($sekolah->wilayah){
                    $sekolah->kecamatan_id = $sekolah->wilayah->parrentRecursive->kode_wilayah;
                    $sekolah->kabupaten_id = $sekolah->wilayah->parrentRecursive->parrentRecursive->kode_wilayah;
                    $sekolah->provinsi_id = ($sekolah->wilayah->parrentRecursive->parrentRecursive->parrentRecursive) ? $sekolah->wilayah->parrentRecursive->parrentRecursive->parrentRecursive->kode_wilayah : NULL;
                    $sekolah->kecamatan = $sekolah->wilayah->parrentRecursive->nama;
                    $sekolah->kabupaten = $sekolah->wilayah->parrentRecursive->parrentRecursive->nama;
                    $sekolah->provinsi = ($sekolah->wilayah->parrentRecursive->parrentRecursive->parrentRecursive) ? $sekolah->wilayah->parrentRecursive->parrentRecursive->parrentRecursive->nama : NULL;
                } else {
                    dd($sekolah);
                }
                $sekolah->save();
            }
        });
    }
}
