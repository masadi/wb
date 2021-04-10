<?php

use Illuminate\Database\Seeder;

class JenisSaranaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('wilayah')->truncate();
        $sql = File::get('database/data/jenis_sarana.json');
        $sql = json_decode($sql);
        foreach($sql as $data){
            DB::table('jenis_sarana')->updateOrInsert(
                ['id' => $data->jenis_sarana_id],
                [
                    'nama' => $data->nama,
                    'kelompok' => $data->kelompok,
                    'perlu_penempatan' => $data->perlu_penempatan,
                    'keterangan' => $data->keterangan,
                    'a_alat' => $data->a_alat,
                    'a_angkutan' => $data->a_angkutan,
                ]
            );
        }
        Schema::enableForeignKeyConstraints();
    }
}
