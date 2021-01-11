<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Encrypt;
use App\Sekolah;
use App\Rekap_pd;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use App\User;
use App\Pendamping;
class DapodikController extends Controller
{
    public function index(){
        $verifikator = User::whereRoleIs('penjamin_mutu')->whereNotNull('token')->select('name', 'token')->get();
        return (new FastExcel($verifikator))->download('data_verifikator_apm.xlsx');
        $jurusan = DB::connection('dapodik')->table('ref.jurusan')->where(function($query){
            $query->whereNull('expired_date');
            $query->where('untuk_smk', 1);
        })->orderBy('level_bidang_id')->get();
        return (new FastExcel($jurusan))->download('template_jurusan.xlsx');
        
        $aksi = 'jurusan';
        $host_server_direktorat = 'http://103.40.55.242/erapor_server/api/'.$aksi;
        $sekolah = Sekolah::has('smk_coe')->first();
        $response = Http::get('http://103.40.55.242/erapor_server/sync/get_sekolah/'.$sekolah->npsn);
        $json = $response->json();
        $encrypt = new Encrypt();
        foreach($response->json() as $items){
            foreach($items as $item){
                $user = (object) $item;
                if($user->password){
                    $encrypt->setString($user->password);
                    $pengguna[] = [
                        'sekolah_id' => $user->sekolah_id,
                        'name' => $user->nama,
                        'email' => $user->username,
                        'password' => $encrypt->doDecrypt(),
                        'password_dapo' => $user->password,
                    ];
                }
            }
        }
        dd($pengguna);
        $data_sync = [
			'username_dapo'		=> $pengguna[0]['email'],
			'password_dapo'		=> $pengguna[0]['password'],
			'tahun_ajaran_id'	=> 2020,
			'semester_id'		=> 20201,
			'sekolah_id'		=> $sekolah->sekolah_id,
			'npsn'				=> $sekolah->npsn,
        ];
        $response = Http::withHeaders([
            'x-api-key' => $sekolah->sekolah_id,
        ])->withBasicAuth('admin', '1234')->asForm()->post($host_server_direktorat, $data_sync);
        dd($response->json());
    }
    public function get_pendamping(Request $request){
        $pendamping = Pendamping::has('sekolah_sasaran')->select('nama', 'instansi', 'nip', 'nuptk', 'email', 'nomor_hp', 'token')->get();
        return (new FastExcel($pendamping))->download('data_pendamping_apm.xlsx');
    }
    public function nama_table(){
        $schemas = DB::select("SELECT *
        FROM pg_catalog.pg_tables
        WHERE schemaname != 'pg_catalog' AND 
            schemaname != 'information_schema';");
        foreach($schemas as $schema){
            $tableName = ($schema->schemaname == 'public') ? $schema->tablename : $schema->schemaname.'.'.$schema->tablename;
            $tables = DB::select("SELECT 
            *
         FROM 
            information_schema.columns
         WHERE 
            table_name = '$tableName';");
            echo '<br>'.$tableName;
            echo '<table border=1>';
            echo '<tr>';
            echo '<td>';
            echo 'Kolom';
            echo '</td>';
            echo '<td>';
            echo 'Tipe';
            echo '</td>';
            echo '<td>';
            echo 'Relasi Table';
            echo '</td>';
            echo '<td>';
            echo 'Relasi Key';
            echo '</td>';
            echo '</tr>';
            foreach($tables as $table){
                echo '<tr>';
                echo '<td>';
                echo $table->column_name;
                echo '</td>';
                echo '<td>';
                echo $table->data_type;
                echo '</td>';
                echo '<td>';
                echo '';
                echo '</td>';
                echo '<td>';
                echo '';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    public function sedot_data(){
        $a = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=0&kode_wilayah=000000&semester_id=20201');
        foreach($a as $b){
            $b = (object) $b;
            $c = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=1&kode_wilayah='.trim($b->kode_wilayah).'&semester_id=20201');
            foreach($c as $d){
                $d = (object) $d;
                $e = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progres-smk?id_level_wilayah=2&kode_wilayah='.trim($d->kode_wilayah).'&semester_id=20201');
                foreach($e as $f){
                    $f = (object) $f;
                    $g = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/progresSP-smk?id_level_wilayah=3&kode_wilayah='.trim($f->kode_wilayah).'&semester_id=20201');
                    foreach($g as $h){
                        $h = (object) $h;
                        $sekolah = Sekolah::find($h->sekolah_id);
                        if($sekolah){
                            $i = $this->get_sedot('https://dapo.kemdikbud.go.id/rekap/sekolahDetail?semester_id=20201&sekolah_id='.$h->sekolah_id_enkrip);
                            foreach($i as $j){
                                $j = (object) $j;
                                Rekap_pd::updateOrCreate(
                                    [
                                        'sekolah_id' => $sekolah->sekolah_id,
                                    ],
                                    [
                                        'pd_kelas_10_laki' => $j->pd_kelas_10_laki,
                                        'pd_kelas_10_perempuan' => $j->pd_kelas_10_perempuan,
                                        'pd_kelas_11_laki' => $j->pd_kelas_11_laki,
                                        'pd_kelas_11_perempuan' => $j->pd_kelas_11_perempuan,
                                        'pd_kelas_12_laki' => $j->pd_kelas_12_laki,
                                        'pd_kelas_12_perempuan' => $j->pd_kelas_12_perempuan,
                                        'pd_kelas_13_laki' => $j->pd_kelas_13_laki,
                                        'pd_kelas_13_perempuan' => $j->pd_kelas_13_perempuan,
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
    }
    private function get_sedot($url){
        $response = Http::get($url);
        return $response->json();
    }
}
