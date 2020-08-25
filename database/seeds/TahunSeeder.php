<?php

use Illuminate\Database\Seeder;
use App\Tahun_pendataan;
use App\Jenis_rapor;
use App\Status_rapor;
use App\Jenis_dokumen;
use App\Jenis_berita_acara;
class TahunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tahun_pendataan::updateOrCreate([
            'tahun_pendataan_id' => 2020,
            'nama' => 'Tahun Ajaran 2020/2021',
            'periode_aktif' => 1,
        ]);
        $jenis_rapor = [
            'verval' => 'Hasil Verifikasi dan Validasi',
            'supervisi' => 'Hasil Supervisi',
            'validasi' => 'Validasi Pusat',
            'pengesahan' => 'Pengesahan Pusat',
        ];
        $status_rapor = [
            'waiting' => 'Menunggu',
            'proses' => 'Sedang Proses',
            'terima' => 'Diterima',
            'tolak' => 'Ditolak',
        ];
        $jenis_berita = [
            'verifikasi' => 'Verifikasi',
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
    }
}
