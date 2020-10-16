<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Nilai_instrumen;
use Illuminate\Support\Facades\DB;
class ResetRapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:rapor';

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
        DB::table('rapor_mutu')->delete();
        DB::table('pakta_integritas')->delete();
        DB::table('nilai_akhir')->delete();
        Nilai_instrumen::whereNotNull('verifikator_id')->delete();
    }
}
