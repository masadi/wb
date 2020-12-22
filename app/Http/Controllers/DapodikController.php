<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Encrypt;
use App\Sekolah;
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
}
