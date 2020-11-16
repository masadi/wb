<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Nilai_dokumen;
use App\Nilai_instrumen;
use App\HelperModel;
use App\Jawaban;
use App\Sekolah;
use App\Status_rapor;
use App\Rapor_mutu;
use App\Jenis_rapor;
use App\Telaah_dokumen;
class ProsesVerifikasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proses:verifikasi';

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
        $path = storage_path('app/verifikasi');
        $files = File::files($path);
        foreach($files as $file){
            $content = json_decode($file->getContents());
            $sekolah = Sekolah::with(['user', 'sekolah_sasaran' => function($query){
                $query->with(['verifikator', 'sektor']);
            }])->find($content->sekolah_id);
            foreach($content->instrumen_id as $instrumen_id){
                foreach($content->ada->{$instrumen_id} as $key => $ada){
                    $telaah_dokumen = Telaah_dokumen::find($key);
                    $all_keterangan[$instrumen_id] = [];
                    if($telaah_dokumen){
                        Nilai_dokumen::updateOrCreate(
                            [
                                'sekolah_sasaran_id' => $content->sekolah_sasaran_id,
                                'instrumen_id' => $instrumen_id,
                                'dok_id' => $key,
                            ],
                            [
                                'ada' => $ada,
                                'keterangan' => $content->keterangan->{$instrumen_id}->{$key}
                            ]
                        );
                        $all_keterangan[$instrumen_id][] = $content->keterangan->{$instrumen_id}->{$key};
                    }
                }
                $keterangan = array_filter($all_keterangan[$instrumen_id]);
                if($content->verifikasi->{$instrumen_id}){
                    $save = Nilai_instrumen::updateOrCreate(
                        [
                            'user_id' => $sekolah->user->user_id,
                            'verifikator_id' => $content->verifikator_id,
                            'instrumen_id' => $instrumen_id,
                        ],
                        [
                            'nilai' => $content->verifikasi->{$instrumen_id},
                            'predikat' => HelperModel::predikat($content->verifikasi->{$instrumen_id}   ),
                            'keterangan' => ($keterangan) ? implode('. ', $keterangan) : NULL,
                        ]
                    );
                    Jawaban::updateOrCreate(
                        [
                            'user_id' => $sekolah->user->user_id,
                            'verifikator_id' => $content->verifikator_id,
                            'komponen_id' => $content->komponen_id->{$instrumen_id},
                            'aspek_id' => $content->aspek_id->{$instrumen_id},
                            'atribut_id' => $content->atribut_id->{$instrumen_id},
                            'indikator_id' => $content->indikator_id->{$instrumen_id},
                            'instrumen_id' => $instrumen_id,
                        ],
                        [
                            'nilai' => $content->verifikasi->{$instrumen_id},
                        ]
                    );
                }
                HelperModel::generate_nilai($sekolah->user->user_id, $content->verifikator_id);
            }
            $status = Status_rapor::where('status', 'waiting')->first();
            $jenis = Jenis_rapor::where('jenis', 'verval')->first();
            Rapor_mutu::updateOrCreate([
                'verifikator_id' => $content->verifikator_id,
                'sekolah_sasaran_id' => $sekolah->sekolah_sasaran->sekolah_sasaran_id,
                'status_rapor_id' => $status->id,
                'jenis_rapor_id' => $jenis->id,
                'keterangan' => 'Sudah di proses verifikasi',
            ]);
            File::delete($file->getrealPath());
        }
        $this->info('Proses Verifikasi Selesai');
    }
}
