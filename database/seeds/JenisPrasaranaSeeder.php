<?php

use Illuminate\Database\Seeder;
class JenisPrasaranaSeeder extends Seeder
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
        $sql = File::get('database/data/jenis_prasarana.json');
        $sql = json_decode($sql);
        foreach($sql as $data){
            DB::table('jenis_prasarana')->updateOrInsert(
                ['id' => $data->jenis_prasarana_id],
                [
                    'nama' => $data->nama,
                    'a_unit_organisasi' => $data->a_unit_organisasi,
                    'a_tanah' => $data->a_tanah,
                    'a_bangunan' => $data->a_bangunan,
                    'a_ruang' => $data->a_ruang,
                ]
            );
        }
        Schema::enableForeignKeyConstraints();
    }
}
