<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tahun_pendataan;
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
        Tahun_pendataan::updateOrCreate(
            [
                'tahun_pendataan_id' => 2021,
            ],
            [
                 'nama' => "Tahun Ajaran 2020/2021",
                 'periode_aktif' => 1,
            ]
        );
        Tahun_pendataan::where('tahun_pendataan_id', '2020')->update(['periode_aktif' => 0]);
    }
}
