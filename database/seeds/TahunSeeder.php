<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Tahun_pendataan;
use App\Jenis_rapor;
use App\Status_rapor;
use App\Jenis_dokumen;
use App\Jenis_berita_acara;
use App\User;
use App\Role;
use App\Sekolah;
use App\Sekolah_sasaran;
class TahunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tahun_pendataan = Tahun_pendataan::updateOrCreate([
            'tahun_pendataan_id' => 2020,
            'nama' => 'Tahun Ajaran 2020/2021',
            'periode_aktif' => 1,
        ]);
        $jenis_rapor = [
            'pakta' => 'Pakta Integritas',
            'verval' => 'Hasil Verifikasi dan Validasi',
            'supervisi' => 'Hasil Supervisi',
            'validasi' => 'Validasi Pusat',
            'pengesahan' => 'Pengesahan Pusat',
        ];
        $status_rapor = [
            'terkirim' => 'Terkirim',
            'waiting' => 'Menunggu',
            'proses' => 'Telah di Proses',
            'terima' => 'Diterima',
            'tolak' => 'Ditolak',
            'afirmasi' => 'Afirmasi',
        ];
        $jenis_berita = [
            'verifikasi' => 'Verifikasi',
            'pendukung' => 'Pendukung',
        ];
        $jenis_dokumen = [
            'berita_acara' => 'Berita Acara',
            'pendukung' => 'Pendukung',
        ];
        foreach($jenis_rapor as $jenis => $nama){
            Jenis_rapor::create([
                'jenis' => $jenis,
                'nama' => $nama,
            ]);
        }
        foreach($status_rapor as $status => $nama){
            Status_rapor::create([
                'status' => $status,
                'nama' => $nama,
            ]);
        }
        foreach($jenis_berita as $berita => $acara){
            Jenis_berita_acara::create([
                'jenis' => $berita,
                'nama' => $acara,
            ]);
        }
        foreach($jenis_dokumen as $jenis => $dokumen){
            Jenis_dokumen::create([
                'jenis' => $jenis,
                'nama' => $dokumen,
            ]);
        }
        $komponen = (new FastExcel)->import('public/template_peserta.xlsx', function ($item) use ($tahun_pendataan){
            $username = strtolower(str_replace(' ', '_', $item['nama_lengkap']));
            $username = str_replace(',', '_', $username);
            $username = str_replace('.', '', $username);
            $username = str_replace('__', '_', $username);
            $verifikator = \App\User::create([
                'name' => $item['nama_lengkap'],
                'username' => $username,
                'email' => $item['email'],
                'password' => bcrypt('12345678'),
                'asal_institusi' => $item['asal_institusi'],
                'alamat_institusi' => $item['alamat_institusi'],
                'nomor_hp' => $item['nomor_hp'],
            ]);
            $role = Role::where('name', 'penjamin_mutu')->first();
            $verifikator->attachRole($role);
            $sekolah = Sekolah::updateOrCreate(
                ['sekolah_id' => Str::uuid()],
                [
                    'npsn' => $item['npsn'],
                    'nama' => 'SEKOLAH CONTOH',
                    'status_sekolah' => 2,
                    'alamat' => $item['alamat_institusi'],
                    'kabupaten' => $item['kabupaten'],
                    'provinsi' => $item['provinsi'],
                ]
            );
            Sekolah_sasaran::updateOrCreate([
                'sekolah_id' => $sekolah->sekolah_id,
                'verifikator_id' => $verifikator->user_id,
                'tahun_pendataan_id' => $tahun_pendataan->tahun_pendataan_id,
            ]);
            $user_sekolah = User::updateOrCreate(
                ['email' => $item['npsn'].'@apmsmk.net'],
                [
                    'sekolah_id' => $sekolah->sekolah_id,
                    'username' => $sekolah->npsn,
                    'name' => $sekolah->nama,
                    'password' => bcrypt(12345678)
                ]
            );
            if(!$user_sekolah->hasRole('sekolah')){
                $role = Role::where('name', 'sekolah')->first();
                $user_sekolah->attachRole($role);
            }

        });
    }
}
