<?php

use Illuminate\Database\Seeder;
use App\Sekolah;
class WilayahSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sekolah::with('wilayah')->chunk(200, function ($data) {
            foreach ($data as $sekolah) {
                $sekolah->kecamatan_id = $sekolah->wilayah->parrentRecursive->kode_wilayah;
                $sekolah->kabupaten_id = $sekolah->wilayah->parrentRecursive->parrentRecursive->kode_wilayah;
                $sekolah->provinsi_id = $sekolah->wilayah->parrentRecursive->parrentRecursive->parrentRecursive->kode_wilayah;
                $sekolah->save();
            }
        });
    }
}
