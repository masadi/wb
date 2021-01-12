<?php

namespace App\Exports;

use App\Instrumen;
use App\Sekolah;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class NilaiExport implements FromView
{
    private $sekolah_id;

    public function __construct($sekolah_id) 
    {
        $this->sekolah_id = $sekolah_id;
    }

    public function view(): View
    {
        $sekolah = Sekolah::with(['user', 'sekolah_sasaran' => function($query){
            $query->with(['verifikator', 'sektor']);
        }])->find($this->sekolah_id);
        $callback = function($query){
            $query->whereNotNull('verifikator_id');
            $query->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
            $query->whereHas('user', function($query){
                $query->whereHas('sekolah', function($query){
                    $query->where('sekolah_id', $this->sekolah_id);
                });
                $query->where('sekolah_id', $this->sekolah_id);
            });
        };
        $callback_sekolah = function($query) {
            $query->whereHas('user', function($query) {
                $query->whereHas('sekolah', function($query) {
                    $query->where('sekolah_id', $this->sekolah_id);
                });
                $query->where('sekolah_id', $this->sekolah_id);
            });
        };
        $instrumens = Instrumen::with(['jawaban' => $callback, 'jawaban_sekolah' => $callback_sekolah, 'subs' => function($query){
            $query->orderBy('urut', 'DESC');
        }, 'telaah_dokumen.nilai_dokumen' => function($query) use ($sekolah){
            $query->where('sekolah_sasaran_id', $sekolah->sekolah_sasaran->sekolah_sasaran_id);
        }])->withCount('telaah_dokumen')->where('urut', 0)->orderBy('indikator_id')->get();
        return view('exports.nilai_instrumen', [
            'sekolah' => $sekolah,
            'dokumen_verifikasi' => NULL,
            'instrumens' => $instrumens
        ]);
    }
}
