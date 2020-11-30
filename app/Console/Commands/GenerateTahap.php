<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sekolah;
class GenerateTahap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tahap';

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
        $komponen = (new FastExcel)->import('public/template_tahap.xlsx', function ($item){
            $sekolah = Sekolah::has('sekolah_sasaran')->with('sekolah_sasaran')->where('npsn', $item['npsn'])->first();
            if($sekolah){
                $sekolah->sekolah_sasaran->tahap = ($item['tahap']) ? $item['tahap'] : NULL;
                $sekolah->sekolah_sasaran->save();
            }
        });
    }
}
