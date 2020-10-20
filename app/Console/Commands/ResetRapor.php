<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Nilai_instrumen;
use App\Sekolah;
use Illuminate\Support\Facades\DB;
class ResetRapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:rapor {npsn?}';

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
        $npsn = $this->argument('npsn');
        if($npsn){
            $sekolah = Sekolah::with(['sekolah_sasaran', 'user'])->where('npsn', $npsn)->first();
            if($sekolah){
                DB::table('rapor_mutu')->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->delete();
                DB::table('pakta_integritas')->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id)->delete();
                DB::table('nilai_akhir')->where('user_id', $sekolah->user->user_id)->delete();
                DB::table('nilai_instrumen')->where('user_id', $sekolah->user->user_id)->delete();
                DB::table('nilai_komponen')->where('user_id', $sekolah->user->user_id)->delete();
                DB::table('nilai_aspek')->where('user_id', $sekolah->user->user_id)->delete();
                DB::table('jawaban')->where('user_id', $sekolah->user->user_id)->delete();
            }
        } else {
            DB::table('rapor_mutu')->delete();
            DB::table('pakta_integritas')->delete();
            DB::table('nilai_akhir')->delete();
            DB::table('nilai_instrumen')->delete();
            DB::table('nilai_komponen')->delete();
            DB::table('nilai_aspek')->delete();
            DB::table('jawaban')->delete();
        }
    }
}
