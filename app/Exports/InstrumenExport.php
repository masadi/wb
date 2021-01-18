<?php

namespace App\Exports;

use App\Instrumen;
use App\Sekolah;
use App\Komponen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InstrumenExport implements FromView
{
    private $limit;

    public function __construct($limit) 
    {
        $this->limit = $limit;
    }
    public function view(): View
    {
        $sekolah = Sekolah::has('smk_coe')->has('sekolah_sasaran')->with(['rekap_pd', 'user' => function($query){
            $query->with(['jawaban_sekolah']);
            //$query->with(['jawaban', 'jawaban_sekolah']);
        }, 'sekolah_sasaran' => function($query){
            $query->with(['verifikator', 'sektor']);
        }])->orderBy('provinsi_id')->orderBy('kabupaten_id')->orderBy('npsn')->paginate($this->limit);
        $instrumens = Instrumen::where('urut', 0)->orderBy('indikator_id')->get();
        $komponen = Komponen::with(['aspek' => function($query){
            $query->with(['instrumen' => function($query){
                $query->where('urut', 0);
                $query->with('subs');
                $query->orderBy('indikator_id');
            }]);
        }])->get();
        return view('exports.nilai_instrumen_all', [
            'sekolah' => $sekolah,
            'instrumens' => $instrumens,
            'komponen' => $komponen,
        ]);
    }
}
