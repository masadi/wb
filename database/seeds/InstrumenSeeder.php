<?php

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Komponen;
use App\Aspek;
use App\Atribut;
use App\Indikator;
use App\Instrumen;
use App\Standar;
use App\Isi_standar;
use App\Breakdown;
use App\Breakdown_standar;
use App\Question;
use App\Answer;
use App\Instrumen_standar;
use Carbon\Carbon;
class InstrumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nilai_akhir')->truncate();
        DB::table('nilai_instrumen')->truncate();
        DB::table('jawaban')->truncate();
        DB::table('nilai_aspek')->truncate();
        DB::table('nilai_komponen')->truncate();
        DB::table('nilai_answer')->truncate();
        DB::table('instrumen')->truncate();
        DB::table('aspek')->truncate();
        DB::table('komponen')->truncate();
        DB::table('questions')->truncate();
        DB::table('answers')->truncate();
        DB::table('breakdown_standars')->truncate();
        DB::table('breakdowns')->truncate();
        DB::table('ref.isi_standar')->truncate();
        DB::table('ref.standar')->truncate();
        $snp = Standar::updateOrCreate(['kode' => 'snp', 'nama' => 'Standar Nasional Pendidikan']);
        $bsc = Standar::updateOrCreate(['kode' => 'bsc', 'nama' => 'Balanced Scorecard']);
        $link_match = Standar::updateOrCreate(['kode' => 'link match', 'nama' => 'Link & Match']);
        $renstra = Standar::updateOrCreate(['kode' => 'renstra', 'nama' => 'Rencana Strategis']);
        $data_snp = (new FastExcel)->sheet(1)->import('public/new_template.xlsx');
        foreach($data_snp as $isi_snp){
            Isi_standar::updateOrCreate([
                'standar_id' => $snp->id,
                'kode' => $isi_snp['kode'],
                'nama' => $isi_snp['nama'],
            ]);
        }
        $data_bsc = (new FastExcel)->sheet(2)->import('public/new_template.xlsx');
        foreach($data_bsc as $isi_bsc){
            $induk = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'kode' => $isi_bsc['kode_induk'],
                'nama' => $isi_bsc['isi'],
            ]);
            $sub = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'isi_standar_id' => $induk->id,
                'kode' => $isi_bsc['kode_sub'],
                'nama' => $isi_bsc['isi_sub'],
            ]);
            $sub_sub = Isi_standar::updateOrCreate([
                'standar_id' => $bsc->id,
                'isi_standar_id' => $sub->id,
                'kode' => $isi_bsc['kode_sub_sub'],
                'nama' => $isi_bsc['isi_sub_sub'],
            ]);
        }
        $data_link_match = (new FastExcel)->sheet(3)->import('public/new_template.xlsx');
        foreach($data_link_match as $isi_link_match){
            Isi_standar::updateOrCreate([
                'standar_id' => $link_match->id,
                'kode' => $isi_link_match['kode'],
                'nama' => $isi_link_match['nama'],
            ]);
        }
        $data_renstra = (new FastExcel)->sheet(4)->import('public/new_template.xlsx');
        foreach($data_renstra as $isi_renstra){
            $induk_renstra = Isi_standar::updateOrCreate([
                'standar_id' => $renstra->id,
                'kode' => $isi_renstra['kode'],
                'nama' => $isi_renstra['renstra'],
            ]);
            Isi_standar::updateOrCreate([
                'standar_id' => $renstra->id,
                'isi_standar_id' => $induk_renstra->id,
                'kode' => $isi_renstra['kode'],
                'nama' => $isi_renstra['sub_renstra'],
            ]);
        }
        $data_instrumen = (new FastExcel)->sheet(5)->import('public/new_template.xlsx');
        foreach($data_instrumen as $item){
            if($item['Komponen']){
                $komponen = Komponen::updateOrCreate([
                    'nama' => $item['Komponen'],
                ]);
                $aspek = Aspek::updateOrCreate([
                    'komponen_id' => $komponen->id,
                    'nama' => $item['Aspek'],
                    'bobot' => $item['Bobot'],
                ]);
                $atribut = Atribut::updateOrCreate([
                    'aspek_id' => $aspek->id,
                    'nama' => $item['Atribut'],
                ]);
                $indikator = Indikator::updateOrCreate([
                    'atribut_id' => $atribut->id,
                    'nama' => $item['Indikator Kinerja'],
                ]);
                $instrumen = Instrumen::updateOrCreate([
                    'indikator_id' => $indikator->id,
                    'urut' => 0,
                    'pertanyaan' => $item['Rumusan Pertanyaan'],
                    'petunjuk_pengisian' => $item['petunjuk pengisian'],
                    'skor' => 5,
                ]);
                for($i=1;$i<=5;$i++){
                    Instrumen::updateOrCreate([
                        'indikator_id' => $indikator->id,
                        'ins_id' => $instrumen->instrumen_id,
                        'urut' => $i,
                        'pertanyaan' => $item['Capaian '.$i],
                        'petunjuk_pengisian' => $item['petunjuk pengisian'],
                        'skor' => 5,
                    ]);
                }
                $find = Instrumen::where(function($query) use ($item){
                    $query->where('indikator_id', $item['No']);
                    $query->where('urut', 0);
                })->first();
                if($find && $item['no_breakdown']){
                    $breakdown = Breakdown::updateOrCreate([
                        'instrumen_id' => $find->instrumen_id,
                        'urut' => $item['no_breakdown'],
                        'breakdown' => $item['breakdown'],
                    ]);
                    if($item['renstra']){
                        $renstra_a = explode(";",$item['renstra']);
                        foreach($renstra_a as $aa){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'renstra');
                            })->where('kode', $aa)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Instrumen_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'instrumen_id' => $find->instrumen_id,
                                    'standar_id' => $renstra->id,
                                ]);
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['link match']){
                        $link_match_a = explode(";",$item['link match']);
                        foreach($link_match_a as $ab){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'link match');
                            })->where('kode', $ab)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Instrumen_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'instrumen_id' => $find->instrumen_id,
                                    'standar_id' => $link_match->id,
                                ]);
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['bsc']){
                        $bsc_a = explode(";",$item['bsc']);
                        foreach($bsc_a as $ac){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'bsc');
                            })->where('kode', $ac)->whereNull('isi_standar_id')->first();
                            //$find_standar = Standar::where('kode', 'link match')->where('kode', $ac)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Instrumen_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'instrumen_id' => $find->instrumen_id,
                                    'standar_id' => $bsc->id,
                                ]);
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    if($item['snp']){
                        $snp_a = explode(";",$item['snp']);
                        foreach($snp_a as $ad){
                            $find_standar = Isi_standar::whereHas('standar', function($query){
                                $query->where('kode', 'snp');
                            })->where('kode', $ad)->whereNull('isi_standar_id')->first();
                            //$find_standar = Standar::where('kode', 'link match')->where('kode', $ad)->whereNull('isi_standar_id')->first();
                            if($find_standar){
                                Instrumen_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'instrumen_id' => $find->instrumen_id,
                                    'standar_id' => $snp->id,
                                ]);            
                                Breakdown_standar::updateOrCreate([
                                    'isi_standar_id' => $find_standar->id,
                                    'breakdown_id' => $breakdown->breakdown_id,
                                ]);
                            }
                        }
                    }
                    $question = Question::updateOrCreate([
                        'breakdown_id' => $breakdown->breakdown_id,
                        'urut' => $item['no_isian'],
                        'question' => $item['question'],
                        //'answer' => $item['answer'],
                    ]);
                    $answer = Answer::updateOrCreate([
                        'question_id' => $question->question_id,
                        'urut' => $item['urut_answer'],
                        'answer' => $item['answer'],
                        'type' => $item['type'],
                    ]);
                }
            }
        }
        /*$komponen = (new FastExcel)->import('public/new.xlsx', function ($item){
            if($item['Komponen']){
                $komponen = Komponen::updateOrCreate([
                    'nama' => $item['Komponen'],
                ]);
                $aspek = Aspek::updateOrCreate([
                    'komponen_id' => $komponen->id,
                    'nama' => $item['Aspek'],
                    'bobot' => $item['Bobot'],
                ]);
                $atribut = Atribut::updateOrCreate([
                    'aspek_id' => $aspek->id,
                    'nama' => $item['Atribut'],
                ]);
                $indikator = Indikator::updateOrCreate([
                    'atribut_id' => $atribut->id,
                    'nama' => $item['Indikator Kinerja'],
                ]);
                $instrumen = Instrumen::updateOrCreate([
                    'indikator_id' => $indikator->id,
                    'urut' => 0,
                    'pertanyaan' => $item['Rumusan Pertanyaan'],
                    'petunjuk_pengisian' => $item['Petunjuk Pengisian'],
                    'skor' => 5,
                ]);
                for($i=1;$i<=5;$i++){
                    Instrumen::updateOrCreate([
                        'indikator_id' => $indikator->id,
                        'ins_id' => $instrumen->instrumen_id,
                        'urut' => $i,
                        'pertanyaan' => $item['Capaian '.$i],
                        'skor' => 5,
                    ]);
                }
            }
        });*/
    }
}
