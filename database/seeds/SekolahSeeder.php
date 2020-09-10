<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Sekolah;
use App\Ptk;
use App\User;
use App\Role;
use App\Sekolah_sasaran;
use App\HelperModel;
class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$all_npsn = ['20606899', '20613916', '20606817', '20607928'];
        $all_npsn = ["69734356",
"20504482",
"20328132",
"20340348",
"20269696",
"10303913",
"40103059",
"11003119",
"20607837",
"10700287",
"20401315",
"20402815",
"20101501",
"20224136",
"20217802",
"20252030",
"20302124",
"20308417",
"20307703",
"20328108",
"20523758",
"20554606",
"20517758",
"20501715",
"20540110",
"30402083",
"11000382",
"10804184",
"69773566",
"10259665",
"69955278",
"20606250",
"20402787",
"20403275",
"20100166",
"20214822",
"20209201",
"20253861",
"20400432",
"69877402",
"20533813",
"20400446",
"20511947",
"20209208",
"20554341",
"20362773",
"20500424",
"20339040",
"20503434",
"20534398",
"20517759",
"69913857",
"20500411",
"20321847",
"20309531",
"20212921",
"60726515",
"20300690",
"20231741",
"20229659",
"20217803",
"20314900",
"20215998",
"20219144",
"69830643",
"40500181",
"20534784",
"30105230",
"30105519",
"30304604",
"30409918",
"30401782",
"10805046",
"50219959",
"50220769",
"50205626",
"69775283",
"50305267",
"60300189",
"10494319",
"10404309",
"40601491",
"40310796",
"40402775",
"10304893",
"10646355",
"10214028",
"10211592",
"69862566",
"69849370",
"50220438",
"50308344",
"10494620",
"20328962",
"20328153",
"69767776",
"10210766",
"69827649",
"20361045",
"20275815",
"20216007",
"20533814",
"30407767",
"10102277",
"20615093",
"20622268",
"20614616",
"20401194",
"20401176",
"20404182",
"20101648",
"20100294",
"20112443",
"20228510",
"69897257",
"69967704",
"20214984",
"20214797",
"20221566",
"20303942",
"20307715",
"20339029",
"20323505",
"20360536",
"20360363",
"20328981",
"20328152",
"20566563",
"20517761",
"20501720",
"20522636",
"20548745",
"20508448",
"30402596",
"50203317",
"50301399",
"50304778",
"40314105",
"40103207",
"10310877",
"10308093",
"10601343",
"10604624"];

        foreach($all_npsn as $npsn){
            $response = Http::post('http://api.erapor-smk.net/api/v1/sekolah', [
                'npsn' => $npsn,
            ]);
            if($response->status()){
                $sekolah = json_decode($response->body());
                $sekolah = $sekolah->data;
                Sekolah::updateOrCreate(
                    ['sekolah_id' => $sekolah->sekolah_id],
                    [
                        'npsn' => $sekolah->npsn,
                        'nama' => $sekolah->nama,
                        'nss' => $sekolah->nss,
                        'alamat' => $sekolah->alamat,
                        'desa_kelurahan' => $sekolah->desa_kelurahan,
                        'kecamatan' => $sekolah->kecamatan,
                        'kode_wilayah' => $sekolah->kode_wilayah,
                        'kabupaten' => $sekolah->kabupaten,
                        'provinsi' => $sekolah->provinsi,
                        'kode_pos' => $sekolah->kode_pos,
                        'lintang' => $sekolah->lintang,
                        'bujur' => $sekolah->bujur,
                        'no_telp' => $sekolah->no_telp,
                        'no_fax' => $sekolah->no_fax,
                        'email' => $sekolah->email,
                        'website' => $sekolah->website,
                        'status_sekolah' => $sekolah->status_sekolah,
                    ]
                );
                $user_sekolah = User::updateOrCreate(
                    ['email' => $sekolah->email],
                    [
                        'sekolah_id' => $sekolah->sekolah_id,
                        'username' => $sekolah->npsn,
                        'name' => $sekolah->nama,
                        'password' => bcrypt($sekolah->npsn)
                    ]
                );
                if(!$user_sekolah->hasRole('sekolah')){
                    $role = Role::where('name', 'sekolah')->first();
                    $user_sekolah->attachRole($role);
                }
                $verifikator = User::where('username', 'penjamin_mutu')->first();
                Sekolah_sasaran::updateOrCreate([
                    'sekolah_id' => $sekolah->sekolah_id,
                    'verifikator_id' => $verifikator->user_id,
                    'tahun_pendataan_id' => HelperModel::tahun_pendataan(),
                ]);
                /*foreach($sekolah->ptk as $ptk){
                    $new_ptk = Ptk::updateOrCreate(
                        ['ptk_id' => $ptk->guru_id_dapodik],
                        [
                            'sekolah_id' => $ptk->sekolah_id,
                            'nama' => $ptk->nama,
                            'nuptk' => $ptk->nuptk,
                            'nip' => $ptk->nip,
                            'jenis_kelamin' => $ptk->jenis_kelamin,
                            'tempat_lahir' => $ptk->tempat_lahir,
                            'tanggal_lahir' => $ptk->tanggal_lahir,
                            'nik' => $ptk->nik,
                            'jenis_ptk_id' => $ptk->jenis_ptk_id,
                            'agama_id' => $ptk->agama_id,
                            'status_kepegawaian_id' => $ptk->status_kepegawaian_id,
                            'alamat' => $ptk->alamat,
                            'rt' => $ptk->rt,
                            'rw' => $ptk->rw,
                            'desa_kelurahan' => $ptk->desa_kelurahan,
                            'kecamatan' => $ptk->kecamatan,
                            'kode_wilayah' => $ptk->kode_wilayah,
                            'kode_pos' => $ptk->kode_pos,
                            'no_hp' => $ptk->no_hp,
                            'email' => $ptk->email,
                        ]
                    );
                    $user = User::updateOrCreate(
                        ['email' => $ptk->email],
                        [
                            'sekolah_id' => $ptk->sekolah_id,
                            'username' => $ptk->nuptk,
                            'name' => $ptk->nama,
                            'password' => bcrypt('12345678')
                        ]
                    );
                    if(!$user->hasRole('ptk')){
                        $role = Role::where('name', 'ptk')->first();
                        $user->attachRole($role);
                    }
                }*/
            }
        }
    }
}
