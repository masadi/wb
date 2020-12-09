<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Program;
use App\Dokumen_program;
class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref.dokumen_program')->truncate();
        DB::table('ref.program')->truncate();
        $komponen = (new FastExcel)->import('public/template_afirmasi.xlsx', function ($item){
            $program = Program::updateOrCreate(
                [
                    'nama' => $item['nama'],
                    'kegiatan' => $item['kegiatan'],
                    'indikator' => $item['indikator'],
                ]
            );
            $dokumen = Dokumen_program::updateOrCreate([
                'urut' => $item['urut'],
                'program_id' => $program->id,
                'nama' => $item['nama_dokumen'],
                'poin' => $item['poin'],
            ]);
        });
    }
}
