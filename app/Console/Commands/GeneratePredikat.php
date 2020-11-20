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
        $nilai_aspek = Nilai_aspek::all();
        foreach($nilai_aspek as $aspek){
            $predikat_aspek = HelperModel::predikat($aspek->total_nilai, true);
            $aspek->predikat = $predikat_aspek;
            $aspek->save();
        }
        $nilai_komponen = Nilai_komponen::all();
        foreach($nilai_komponen as $komponen){
            $predikat_komponen = HelperModel::predikat($komponen->total_nilai, true);
            $komponen->predikat = $predikat_komponen;
            $komponen->save();
        }
        $nilai_akhir = Nilai_akhir::all();
        foreach($nilai_akhir as $akhir){
            $predikat_akhir = HelperModel::predikat($akhir->nilai, true);
            $akhir->predikat = $predikat_akhir;
            $akhir->save();
        }
        $this->info('Proses Generate Predikat Selesai');
    }
}
