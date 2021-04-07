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
    }
}
