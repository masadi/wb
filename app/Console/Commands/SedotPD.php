<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Sekolah;
use App\Rekap_pd;

class SedotPD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sedot:pd';

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
        $a = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=0&kode_wilayah=000000&semester_id=20201');
        foreach($a as $b){
            $b = (object) $b;
            $this->info('Proses sedot PD '.$b->nama);
            $c = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=1&kode_wilayah='.trim($b->kode_wilayah).'&semester_id=20201');
            foreach($c as $d){
                $d = (object) $d;
                $this->info('Proses sedot PD '.$d->nama);
                $e = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=2&kode_wilayah='.trim($d->kode_wilayah).'&semester_id=20201');
                foreach($e as $f){
                    $f = (object) $f;
                    $this->info('Proses sedot PD '.$f->nama);
                    $g = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progresSP-smk?id_level_wilayah=3&kode_wilayah='.trim($f->kode_wilayah).'&semester_id=20201');
                    foreach($g as $h){
                        $h = (object) $h;
                        $sekolah = Sekolah::find($h->sekolah_id);
                        if($sekolah){
                            $this->info('Proses sedot PD '.$h->nama);
                            $i = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/sekolahDetail?semester_id=20201&sekolah_id='.$h->sekolah_id_enkrip);
                            foreach($i as $j){
                                $j = (object) $j;
                                Rekap_pd::updateOrCreate(
                                    [
                                        'sekolah_id' => $sekolah->sekolah_id,
                                    ],
                                    [
                                        'pd_kelas_10_laki' => $j->pd_kelas_10_laki,
                                        'pd_kelas_10_perempuan' => $j->pd_kelas_10_perempuan,
                                        'pd_kelas_11_laki' => $j->pd_kelas_11_laki,
                                        'pd_kelas_11_perempuan' => $j->pd_kelas_11_perempuan,
                                        'pd_kelas_12_laki' => $j->pd_kelas_12_laki,
                                        'pd_kelas_12_perempuan' => $j->pd_kelas_12_perempuan,
                                        'pd_kelas_13_laki' => $j->pd_kelas_13_laki,
                                        'pd_kelas_13_perempuan' => $j->pd_kelas_13_perempuan,
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
        $this->info('Proses sedot PD Selesai');
    }
    private function get_sedot($url){
        $response = Http::withOptions([
            'verify' => false,
            'connect_timeout' => 0
        ])->get($url);
        return $response->json();
    }
}
