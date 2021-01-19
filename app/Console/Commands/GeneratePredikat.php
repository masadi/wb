<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Nilai_akhir;
use App\HelperModel;
class GeneratePredikat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:predikat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nilai_aspek = Nilai_aspek::whereNotNull('verifikator_id')->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2')->get();
        foreach($nilai_aspek as $aspek){
            $predikat_aspek = HelperModel::predikat($aspek->total_nilai, true);
            $aspek->predikat = $predikat_aspek;
            $aspek->save();
        }
        $nilai_komponen = Nilai_komponen::whereNotNull('verifikator_id')->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2')->get();
        foreach($nilai_komponen as $komponen){
            $predikat_komponen = HelperModel::predikat($komponen->total_nilai, true);
            $komponen->predikat = $predikat_komponen;
            $komponen->save();
        }
        $nilai_akhir = Nilai_akhir::whereNotNull('verifikator_id')->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2')->get();
        foreach($nilai_akhir as $akhir){
            $predikat_akhir = HelperModel::predikat($akhir->nilai, true);
            $akhir->predikat = $predikat_akhir;
            $akhir->peringkat = HelperModel::peringkat($akhir->nilai);
            $akhir->save();
        }
        $this->info('Proses Generate Predikat Verifikator Selesai');
        $nilai_aspek = Nilai_aspek::whereNull('verifikator_id')->get();
        foreach($nilai_aspek as $aspek){
            $predikat_aspek = HelperModel::predikat($aspek->total_nilai, true);
            $aspek->predikat = $predikat_aspek;
            $aspek->save();
        }
        $nilai_komponen = Nilai_komponen::whereNull('verifikator_id')->get();
        foreach($nilai_komponen as $komponen){
            $predikat_komponen = HelperModel::predikat($komponen->total_nilai, true);
            $komponen->predikat = $predikat_komponen;
            $komponen->save();
        }
        $nilai_akhir = Nilai_akhir::whereNull('verifikator_id')->get();
        foreach($nilai_akhir as $akhir){
            $predikat_akhir = HelperModel::predikat($akhir->nilai, true);
            $akhir->predikat = $predikat_akhir;
            $akhir->peringkat = HelperModel::peringkat($akhir->nilai);
            $akhir->save();
        }
        $this->info('Proses Generate Predikat Sekolah Selesai');
        Nilai_akhir::where('verifikator_id', '84ff9f29-1bd0-462f-976f-4c512dc22cc2')->delete();
        Nilai_akhir::whereIn('nilai_akhir_id', ['9273c9f4-ddab-4116-97c5-7dcdedfc0f8f','a2788a02-d678-4483-9efc-facda92ac674','66f94be6-20e8-404c-b4b4-048666b2904a','9b27fd8b-f711-4a00-98c4-d67d63d8a4c5'])->delete();
        $this->info('Proses cleansing Nilai Akhir');
    }
}
