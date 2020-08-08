<?php

use Illuminate\Database\Seeder;

class InstrumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Instrumen::class, 50)->create();
    }
}
