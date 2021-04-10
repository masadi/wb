<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(JenisPrasaranaSeeder::class);
        $this->call(JenisSaranaSeeder::class);
        $this->call(TahunSeeder::class);
        $this->call(ActiveSeeder::class);
        $this->call(WilayahSeeder::class);
        $this->call(SekolahSeeder::class);
    }
}
