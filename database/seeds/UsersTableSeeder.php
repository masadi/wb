<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->where('email', 'masadi.com@gmail.com')->delete();

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'masadi.com@gmail.com',
            'password' => bcrypt('12345678'),
            'type' => 'admin',
        ]);
        DB::table('settings')->insert(
            [
                'key' => 'dollar',
                'value' => 13500,
            ]
        );
    }
}
