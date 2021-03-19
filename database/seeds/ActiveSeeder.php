<?php

use Illuminate\Database\Seeder;
use App\User;
class ActiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereRoleIs(['admin', 'direktorat', 'penjamin_mutu'])->get();
        foreach($users as $user){
            $user->active = 1;
            $user->save();
        }
    }
}
