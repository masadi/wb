<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Instrumen;
use App\Sekolah;
use App\Komponen;

class AspekExport implements FromView
{
    private $limit;

    public function __construct($limit) 
    {
        $this->limit = $limit;
    }
    public function view(): View
    {
        $sekolah = Sekolah::has('smk_coe')->has('sekolah_sasaran')->with(['rekap_pd', 'user' => function($query){
            $query->with(['nilai_aspek_sekolah', 'nilai_aspek_verifikasi']);
            //$query->with(['jawaban', 'jawaban_sekolah']);
        }, 'sekolah_sasaran' => function($query){
            $query->with(['verifikator', 'sektor']);
        }])->orderBy('provinsi_id')->orderBy('kabupaten_id')->paginate($this->limit);
        $instrumens = Instrumen::where('urut', 0)->orderBy('indikator_id')->get();
        $komponen = Komponen::with(['aspek' => function($query){
            $query->with(['instrumen' => function($query){
                $query->where('urut', 0);
                $query->with('subs');
                $query->orderBy('indikator_id');
            }]);
        }])->withCount('aspek')->get();
        return view('exports.nilai_aspek', [
            'sekolah' => $sekolah,
            'instrumens' => $instrumens,
            'komponen' => $komponen,
        ]);
    }
}
