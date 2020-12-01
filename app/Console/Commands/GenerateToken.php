<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\User;
use App\Pendamping;
class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:token {query=pendamping}';

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
        $query = $this->argument('query');
        if($query == 'user'){
            $users = User::whereRoleIs('penjamin_mutu')->get();
            foreach($users as $user){
                $token = Str::random(6);
                $user->token = strtoupper($token);
                $user->save();
            }
        } else {
            $komponen = (new FastExcel)->import('public/ref_pendamping.xlsx', function ($item){
                $pendamping = Pendamping::has('sekolah_sasaran')->where('nama', $item['nama'])->first();
                if($pendamping){
                    $token = strtolower($item['token']);
                    $token = str_replace(' ', '_', trim($token));
                    $pendamping->token = $token;
                    $pendamping->save();
                }
            });
        }
    }
}
