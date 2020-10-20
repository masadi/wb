<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sekolah_sasaran;
use App\Pendamping;
use App\HelperModel;
class PendampingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('pendamping')->delete();
        $komponen = (new FastExcel)->import('public/template_pendamping.xlsx', function ($item){
            $sekolah = Sekolah_sasaran::whereHas('sekolah', function($query) use ($item){
                $query->where('npsn', $item['npsn']);
            })->where('tahun_pendataan_id', HelperModel::tahun_pendataan())->first();
            if($sekolah){
                if($item['nama']){
                    $pendamping = Pendamping::updateOrCreate(
                        [
                            'nama' => $item['nama'],
                            'instansi' => $item['instansi'],
                        ]
                    );
                    $sekolah->pendamping_id = $pendamping->pendamping_id;
                    $sekolah->save();
                }
            }
        });
    }
}
