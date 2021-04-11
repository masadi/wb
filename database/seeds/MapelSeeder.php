<?php

use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('mata_pelajaran')->truncate();
        DB::table('mapel')->truncate();
        $sql = File::get('database/data/mata_pelajaran.json');
        $sql = json_decode($sql);
        foreach($sql as $data){
            DB::table('mata_pelajaran')->updateOrInsert(
                [
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'nama' => $data->nama,
                ]
            );
            DB::table('mapel')->updateOrInsert(
                [
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'tingkat_pendidikan_id' => $data->tingkat_pendidikan_id,
                ]
            );
        }
        Schema::enableForeignKeyConstraints();
    }
}
