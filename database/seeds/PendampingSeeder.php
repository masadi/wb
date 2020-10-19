<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sekolah;
use App\Pendamping;
class PendampingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $komponen = (new FastExcel)->import('public/template_pendamping.xlsx', function ($item){
            $sekolah = Sekolah::doesntHave('pendamping')->has('sekolah_sasaran')->with('sekolah_sasaran')->where('npsn', $item['npsn'])->first();
            if($sekolah){
                Pendamping::updateOrCreate(
                    [
                        'sekolah_sasaran_id' => $sekolah->sekolah_sasaran->sekolah_sasaran_id,
                    ],
                    [
                        'nama' => $item['nama'],
                        'instansi' => $item['instansi'],
                    ]
                );
            }
        });
    }
}
