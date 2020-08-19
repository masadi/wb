<?php

use Illuminate\Database\Seeder;
use App\Tahun_pendataan;
class TahunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tahun_pendataan::updateOrCreate([
            'tahun_pendataan_id' => 2020,
            'nama' => 'Tahun Ajaran 2020/2021',
            'periode_aktif' => 1,
        ]);
    }
}
