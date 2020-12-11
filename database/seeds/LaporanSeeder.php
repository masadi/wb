<?php

use Illuminate\Database\Seeder;
use App\Jenis_laporan;
class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_laporan = ['LAPORAN PELAKSANAAN PENDAMPINGAN', 'LAPORAN DOKUMENTASI PENDAMPINGAN', 'LAPORAN HASIL VERIFIKASI', 'LAPORAN DOKUMENTASI VERIFIKASI', 'LAPORAN MONITORING DAN EVALUASI', 'LAPORAN DOKUMENTASI MONITORING DAN EVALUASI'];
        foreach($jenis_laporan as $laporan){
            Jenis_laporan::updateOrCreate([
                'nama' => $laporan,
            ]);
        }
    }
}
