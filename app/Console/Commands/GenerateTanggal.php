<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Laporan;
use Carbon\Carbon;
class GenerateTanggal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tanggal';

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
        $all_laporan = Laporan::get();
        foreach($all_laporan as $laporan){
            if($this->validateDate($laporan->tanggal_pelaksanaan, 'Y-m-d')){
                $laporan->tgl_pelaksanaan = date('Y-m-d', strtotime($laporan->tanggal_pelaksanaan));
                $laporan->save();
            }
            //if($laporan->tanggal_pelaksanaan && is_date($laporan->tanggal_pelaksanaan)){

            //}
            //echo date('Y-m-d', strtotime($laporan->tanggal_pelaksanaan));
            /*echo $laporan->created_at;
            $created_at = Carbon::parse($laporan->created_at)->locale('id');
            dd($created_at->isoFormat('Do MMMM YYYY'));
            dd($laporan);*/
        }
    }
    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
