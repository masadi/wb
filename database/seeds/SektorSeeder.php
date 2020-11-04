<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sektor;
use App\Sekolah;
class SektorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $komponen = (new FastExcel)->import('public/template_sektor.xlsx', function ($item){
            $sektor = Sektor::updateOrCreate(
                [
                    'nama' => trim($item['sektor_coe']),
                ]
            );
            $sekolah = Sekolah::with('sekolah_sasaran')->where('npsn', $item['npsn'])->first();
            if($sekolah && $sekolah->sekolah_sasaran){
                $sekolah->sekolah_sasaran->sektor_id = $sektor->id;
                $sekolah->sekolah_sasaran->save();
            }
        });
    }
}
