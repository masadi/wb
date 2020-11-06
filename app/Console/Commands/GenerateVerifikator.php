<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Sekolah;
use App\User;
use App\Role;
class GenerateVerifikator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:verifikator';

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
        $komponen = (new FastExcel)->import('public/template_verifikator.xlsx', function ($item){
            $username = str_replace(' ', '', $item['username']);
            $username = strtolower($username);
            $sekolah = Sekolah::has('sekolah_sasaran')->with('sekolah_sasaran')->where('npsn', $item['npsn'])->first();
            $new_user = User::updateOrCreate(
                [
                    'username' => $username,
                ],
                [
                    'email' => $username.'@psmk.kemdikbud.go.id',
                    'name' => $item['name'],
                    'password' => bcrypt($username),
                    'token' => $username,
                ]
            );
            if(!$new_user->hasRole('penjamin_mutu')){
                $role = Role::where('name', 'penjamin_mutu')->first();
                $new_user->attachRole($role);
            }
            if($sekolah){
                $sekolah->sekolah_sasaran->verifikator_id = $new_user->user_id;
                $sekolah->sekolah_sasaran->save();
            }
        });
    }
}
