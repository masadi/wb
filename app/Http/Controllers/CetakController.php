<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use App\Laporan;
use App\Program;
class CetakController extends Controller
{
    public function monev($laporan_id){
        $laporan = Laporan::with(['sekolah.pendamping'])->find($laporan_id);
        /*$nilai_afirmasi = Nilai_afirmasi::where(function($query) use ($laporan){
            $query->where('sekolah_sasaran_id', $laporan->sekolah_sasaran_id);
            $query->where('pendamping_id', $laporan->pendamping_id);
        })->get();*/
        $monev = Program::with(['dokumen_program.nilai_afirmasi' => function($query) use ($laporan){
            $query->where('sekolah_sasaran_id', $laporan->sekolah_sasaran_id);
        }])->orderBy('id')->get();
        $data = [
            'now' => Carbon::now(),
            'laporan' => $laporan,
            'monev' => $monev,
        ];
        //return view('cetak.berita_acara', $data);
        $pdf = PDF::loadView('cetak.monev', $data, [], [
            'format' => [220, 330],
            'orientation' => 'L',
        ]);
        $pdf->getMpdf()->SetFooter('|{PAGENO}|Dicetak dari Aplikasi APM SMK v.1.0.0');
        return $pdf->stream('Monev Hasil SMK CoE Tahun 2020.pdf');
    }
}
