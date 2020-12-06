<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Laporan;
use App\Sekolah;
use Carbon\Carbon;
class UnduhanController extends Controller
{
    public function index(Request $request){
        $function = 'get_'.request()->route('query');
        return $this->{$function}($request);
    }
    public function get_laporan($request){
        $all_pendampingan = Laporan::with(['sekolah.sekolah_sasaran.sektor', 'pendamping'])->where('jenis_laporan_id', 1)->get();
        $no=1;
        $pendampingan = [];
        $belum_pendampingan = [];
        $verifikasi = [];
        $belum_verifikasi = [];
        foreach($all_pendampingan as $data_pendampingan){
            $tanggal_pelaksanaan = Carbon::parse($data_pendampingan->tanggal_pelaksanaan)->locale('id');
            $created_at = Carbon::parse($data_pendampingan->created_at)->locale('id');
            $pendampingan[] = [
                'No' => $no,
                'Provinsi' => $data_pendampingan->sekolah->provinsi,
                'Kabupaten/Kota' => $data_pendampingan->sekolah->kabupaten,
                'Nama Sekolah' => $data_pendampingan->sekolah->nama,
                'NPSN' => $data_pendampingan->sekolah->npsn,
                'Sektor CoE' => ($data_pendampingan->sekolah->sekolah_sasaran->sektor) ? $data_pendampingan->sekolah->sekolah_sasaran->sektor->nama : '-',
                'Nama Pendamping' => ($data_pendampingan->pendamping) ? $data_pendampingan->pendamping->nama : '-',
                'Tanggal Pelaksanaan' => $tanggal_pelaksanaan->isoFormat('Do MMMM YYYY'),
                'Tanggal Pelaporan' => $created_at->isoFormat('Do MMMM YYYY'),
                'Keterangan' => '',
            ];
            $no++;
        }
        $all_verifikasi = Laporan::with(['sekolah.sekolah_sasaran.sektor', 'verifikator'])->where('jenis_laporan_id', 3)->get();
        $no=1;
        foreach($all_verifikasi as $data_verifikasi){
            $tanggal_pelaksanaan = Carbon::parse($data_verifikasi->tanggal_pelaksanaan)->locale('id');
            $created_at = Carbon::parse($data_verifikasi->created_at)->locale('id');
            $verifikasi[] = [
                'No' => $no,
                'Provinsi' => $data_verifikasi->sekolah->provinsi,
                'Kabupaten/Kota' => $data_verifikasi->sekolah->kabupaten,
                'Nama Sekolah' => $data_verifikasi->sekolah->nama,
                'NPSN' => $data_verifikasi->sekolah->npsn,
                'Sektor CoE' => ($data_verifikasi->sekolah->sekolah_sasaran->sektor) ? $data_verifikasi->sekolah->sekolah_sasaran->sektor->nama : '-',
                'Nama Verifikator' => ($data_verifikasi->verifikator) ? $data_verifikasi->verifikator->name : '-',
                'Tanggal Pelaksanaan' => $tanggal_pelaksanaan->isoFormat('Do MMMM YYYY'),
                'Tanggal Pelaporan' => $created_at->isoFormat('Do MMMM YYYY'),
                'Keterangan' => '',
            ];
            $no++;
        }
        $sekolah_belum_pendampingan = Sekolah::has('sekolah_sasaran')->with(['sekolah_sasaran' => function($query){
            $query->with(['sektor', 'pendamping']);
        }])->whereDoesntHave('laporan', function (Builder $query) {
            $query->where('jenis_laporan_id', 1);
        })->get();
        $no=1;
        foreach($sekolah_belum_pendampingan as $data_belum_pendampingan){
            $belum_pendampingan[] = [
                'No' => $no,
                'Provinsi' => $data_belum_pendampingan->provinsi,
                'Kabupaten/Kota' => $data_belum_pendampingan->kabupaten,
                'Nama Sekolah' => $data_belum_pendampingan->nama,
                'NPSN' => $data_belum_pendampingan->npsn,
                'Sektor CoE' => ($data_belum_pendampingan->sekolah_sasaran->sektor) ? $data_belum_pendampingan->sekolah_sasaran->sektor->nama : '-',
                'Nama Verifikator' => ($data_belum_pendampingan->sekolah_sasaran->pendamping) ? $data_belum_pendampingan->sekolah_sasaran->pendamping->nama : '-',
                'Tanggal Pelaksanaan' => '-',
                'Tanggal Pelaporan' => '-',
                'Keterangan' => '',
            ];
            $no++;
        }
        $sekolah_belum_verifikasi = Sekolah::has('sekolah_sasaran')->with(['sekolah_sasaran' => function($query){
            $query->with(['sektor', 'pendamping']);
        }])->whereDoesntHave('laporan', function (Builder $query) {
            $query->where('jenis_laporan_id', 3);
        })->get();
        $no=1;
        foreach($sekolah_belum_verifikasi as $data_belum_verifikasi){
            $belum_verifikasi[] = [
                'No' => $no,
                'Provinsi' => $data_belum_verifikasi->provinsi,
                'Kabupaten/Kota' => $data_belum_verifikasi->kabupaten,
                'Nama Sekolah' => $data_belum_verifikasi->nama,
                'NPSN' => $data_belum_verifikasi->npsn,
                'Sektor CoE' => ($data_belum_verifikasi->sekolah_sasaran->sektor) ? $data_belum_verifikasi->sekolah_sasaran->sektor->nama : '-',
                'Nama Verifikator' => ($data_belum_verifikasi->sekolah_sasaran->verifikator) ? $data_belum_verifikasi->sekolah_sasaran->verifikator->name : '-',
                'Tanggal Pelaksanaan' => '-',
                'Tanggal Pelaporan' => '-',
                'Keterangan' => '',
            ];
            $no++;
        }
        $sheets = new SheetCollection([
            'Sudah Pendampingan' => $pendampingan,
            'Belum Pendampingan' => $belum_pendampingan,
            'Sudah Verifikasi' => $verifikasi,
            'Belum Verifikasi' => $belum_verifikasi,
        ]);
        return (new FastExcel($sheets))->download('Rekapitulasi Laporan Rapor Mutu SMK CoE Tahun 2020.xlsx');
    }
}
