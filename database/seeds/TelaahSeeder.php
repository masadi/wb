<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Telaah_dokumen;
class TelaahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $komponen = (new FastExcel)->import('public/template_telaah_dokumen.xlsx', function ($item){
            Telaah_dokumen::updateOrCreate(
                [
                'instrumen_id' => $item['instrumen_id'],
                'urut' => $item['urut'],
                ],
                [
                    'nama' => trim($item['nama']),
                ]
            );
        });
    }
}
