<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Role;
class GenerateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:user';

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
        $users = [
            [
                'name' => 'Direktur SMK',
                'username' => 'direktursmk',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'kasubbag tata usaha',
                'username' => 'kataus',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'koordinator bidang penilaian',
                'username' => 'koorbidpenilaian',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'koordinator bidang tata kelola',
                'username' => 'koorbidtakola',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'koordinator bidang program dan evaluasi',
                'username' => 'koorbidprogrev',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'koordinator bidang sarana dan prasarana',
                'username' => 'koorbidsarpras',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'koordinator bidang peserta didik',
                'username' => 'koorbidpesdik',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 1 bidang penilaian',
                'username' => 'skoorbidpenilaian1',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 2 bidang penilaian',
                'username' => 'skoorbidpenilaian2',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 1 bidang tata kelola',
                'username' => 'skoorbidtakola1',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 2 bidang tata kelola',
                'username' => 'skoorbidtakola2',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 1 bidang program dan evaluasi',
                'username' => 'skoorbidprogrev1',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 2 bidang program dan evaluasi',
                'username' => 'skoorbidprogrev2',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 1 bidang sarana dan prasarana',
                'username' => 'skoorbidsarpras1',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 2 bidang sarana dan prasarana',
                'username' => 'skoorbidsarpras2',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 1 bidang peserta didik',
                'username' => 'skoorbidpesdik1',
                'password' => 'smkbisahebat',
            ],
            [
                'name' => 'subkoordinator 2 bidang peserta didik',
                'username' => 'skoorbidpesdik2',
                'password' => 'smkbisahebat',
            ],
        ];
        foreach($users as $user){
            $new_user = User::updateOrCreate(
                ['username' => $user['username']],
                [
                    'email' => $user['username'].'@psmk.kemdikbud.go.id',
                    'name' => ($user['username'] == 'direktursmk') ? $user['name'] : ucwords($user['name']),
                    'password' => bcrypt($user['password'])
                ]
            );
            if(!$new_user->hasRole('direktorat')){
                $role = Role::where('name', 'direktorat')->first();
                $new_user->attachRole($role);
            }
        }
    }
}
