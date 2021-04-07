<?php

use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
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
		DB::table('negara')->truncate();
        DB::table('level_wilayah')->truncate();
        $sql = File::get('database/data/level_wilayah.json');
        $sql = json_decode($sql);
        foreach($sql as $data){
            DB::table('level_wilayah')->updateOrInsert(
                ['id_level_wilayah' => $data->id_level_wilayah],
                [
                    'level_wilayah' => $data->level_wilayah,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'deleted_at' => ($data->deleted_at == "null") ? NULL : $data->deleted_at,
                ]
            );
        }
        $sql = File::get('database/data/negara.json');
        $sql = json_decode($sql);
        foreach($sql as $data){
            DB::table('negara')->updateOrInsert(
                ['negara_id' => $data->negara_id],
                [
                    'nama' => $data->nama,
                    'luar_negeri' => $data->luar_negeri,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'deleted_at' => ($data->deleted_at == "null") ? NULL : $data->deleted_at,
                ]
            );
        }
        for($i=0;$i<=99;$i++){
            $sql = File::get('database/data/wilayah_'.$i.'.json');
            $sql = json_decode($sql);
            foreach($sql as $data){
                try {
                    DB::table('wilayah')->updateOrInsert(
                        ['kode_wilayah' => $data->kode_wilayah],
                        [
                            'nama' => $data->nama,
                            'id_level_wilayah' => $data->id_level_wilayah,
                            'mst_kode_wilayah' => $data->mst_kode_wilayah,
                            'negara_id' => $data->negara_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'deleted_at' => ($data->expired_date) ? now() : NULL,
                        ]
                    );
                } catch (Exception $e) {
                    echo $data->mst_kode_wilayah."\n";
                    echo $i."\n";
                }
            }
        }
        Schema::enableForeignKeyConstraints();
    }
}
