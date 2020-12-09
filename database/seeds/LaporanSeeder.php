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
        $jenis_laporan = ['LAPORAN PELAKSANAAN PENDAMPINGAN', 'LAPORAN DOKUMENTASI PENDAMPINGAN', 'LAPORAN HASIL VERIFIKASI', 'LAPORAN DOKUMENTASI VERIFIKASI', 'LAPORAN HASIL AFIRMASI MONEV', 'LAPORAN DOKUMENTASI AFIRMASI MONEV'];
        foreach($jenis_laporan as $laporan){
            Jenis_laporan::updateOrCreate([
                'nama' => $laporan,
            ]);
        }
    }
}
