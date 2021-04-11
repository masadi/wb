<?php

use Illuminate\Database\Seeder;

class KepemilikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_data = [
            [
                'kepemilikan_sarpras_id' => 1,
                'nama' => "Milik",
            ],
            [
                'kepemilikan_sarpras_id' => 2,
                'nama' => "Sewa",
            ],
            [
                'kepemilikan_sarpras_id' => 3,
                'nama' => "Pinjam",
            ],
            [
                'kepemilikan_sarpras_id' => 4,
                'nama' => "Bukan Milik",
            ],
            [
                'kepemilikan_sarpras_id' => 5,
                'nama' => "Lainnya",
            ],
        ];
        foreach($all_data as $data){
            DB::table('status_kepemilikan_sarpras')->updateOrInsert([
                'kepemilikan_sarpras_id' => $data['kepemilikan_sarpras_id'],
                'nama' => $data['nama'],
            ]);
        }
    }
}
