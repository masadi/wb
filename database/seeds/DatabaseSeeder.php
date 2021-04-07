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
        //$this->call(InstrumenSeeder::class);
        $this->call(TahunSeeder::class);
        $this->call(ActiveSeeder::class);
        //$this->call(SekolahSeeder::class);
        $this->call(WilayahSeeder::class);
        //$this->call(WilayahSekolahSeeder::class);
    }
}
