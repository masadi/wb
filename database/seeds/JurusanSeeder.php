<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Jurusan;
class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $komponen = (new FastExcel)->import('public/template_jurusan.xlsx', function ($item){
            //dd($item);
            Jurusan::updateOrCreate(
                [
                'jurusan_id' => $item['jurusan_id'],
                ],
                [
                    'nama_jurusan' => $item['nama_jurusan'],
                    'untuk_sma' => $item['untuk_sma'],
                    'untuk_smk' => $item['untuk_smk'],
                    'untuk_pt' => $item['untuk_pt'],
                    'untuk_slb' => $item['untuk_slb'],
                    'untuk_smklb' => $item['untuk_smklb'],
                    'jenjang_pendidikan_id' => ($item['jenjang_pendidikan_id']) ? $item['jenjang_pendidikan_id'] : NULL,
                    'jurusan_induk' => ($item['jurusan_induk']) ? $item['jurusan_induk'] : NULL,
                    'level_bidang_id' => $item['level_bidang_id'],
                    'last_sync' => $item['last_sync'],
                ]
            );
        });
    }
}
