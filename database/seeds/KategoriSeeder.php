<?php

use Illuminate\Database\Seeder;
use App\Kategori;
class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_kategori = ['Informasi', 'Pembaharuan', 'Peraturan', 'Pedoman'];
        foreach($all_kategori as $kategori){
            Kategori::create(['nama' => $kategori]);
        }
    }
}
