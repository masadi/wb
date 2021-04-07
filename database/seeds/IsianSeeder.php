<?php

use Illuminate\Database\Seeder;
use App\HelperModel;
use App\Nilai_instrumen;
use App\Jawaban;
use App\Nilai_answer;
use App\Instrumen;
use App\User;
use App\Komponen;

class IsianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::with(['sekolah.sekolah_sasaran.verifikator'])->whereRoleIs('sekolah')->first();
        $output = Komponen::withCount(['jawaban' => function($query) use ($user){
            $query->where('user_id', $user->user_id);
            $query->whereNull('verifikator_id');
        }, 'indikator'])->with(['aspek'])->get();
        $sekolah = User::with(['sekolah' => function($query){
            $query->with(['smk_coe']);
        }])->find($user->user_id);
        $callback_jawaban = function($query) use ($user){
            $query->where('user_id', $user->user_id);
            $query->whereNull('verifikator_id');
        };
        foreach($output as $komponen){
            foreach($komponen->aspek as $aspek){
                $callback = function($query) use ($aspek){
                    $query->where('id', $aspek->id);
                };
                $instrumens = Instrumen::where('urut', 0)->whereHas('indikator.atribut.aspek', $callback)->with(['jawaban' => $callback_jawaban, 'subs', 'indikator.atribut.aspek' => $callback, 'breakdown.question.answer.nilai_answer' => function($query) use ($user){
                    $query->where('user_id', $user->user_id);
                }])->get();
                foreach($instrumens as $instrumen){
                    foreach($instrumen->breakdown as $breakdown){
                        foreach($breakdown->question as $question){
                            foreach($question->answer as $answer){
                                $nilai = ($answer->type == 'number') ? 10 : 1;
                                Nilai_answer::updateOrCreate(
                                    [
                                        'answer_id' => $answer->answer_id,
                                        'user_id' => $user->user_id,
                                    ],
                                    [
                                        'answer' => ($nilai) ? $nilai : 0,
                                    ]
                                );
                            }
                        }
                    }
                    $instrumen_id = $instrumen->instrumen_id;
                    $sub = rand(0,4);
                    $jawaban = $instrumen->subs[$sub];
                    $del_sekolah = Jawaban::where(function($query) use ($user, $instrumen_id){
                        $query->where('user_id',$user->user_id);
                        $query->where('instrumen_id', $instrumen_id);
                    })->delete();
                    $del_instrumen = Nilai_instrumen::where(function($query) use ($user, $instrumen_id){
                        $query->where('user_id',$user->user_id);
                        $query->where('instrumen_id', $instrumen_id);
                    })->delete();
                    Jawaban::updateOrCreate(
                        [
                            'user_id' => $user->user_id,
                            'instrumen_id' => $instrumen_id,
                            'verifikator_id' => NULL,
                        ],
                        [
                            'indikator_id' => $instrumen->indikator->id,
                            'atribut_id' => $instrumen->indikator->atribut->id,
                            'aspek_id' => $instrumen->indikator->atribut->aspek->id,
                            'komponen_id' => $komponen->id,
                            'nilai' => $jawaban->urut,
                        ]
                    );
                    Nilai_instrumen::updateOrCreate(
                        [
                            'user_id' => $user->user_id,
                            'instrumen_id' => $instrumen_id,
                            'verifikator_id' => NULL,
                        ],
                        [
                            'nilai' => $jawaban->urut,
                            'predikat' => HelperModel::predikat($jawaban->urut),
                        ]
                    );
                    Jawaban::updateOrCreate(
                        [
                            'user_id' => $user->user_id,
                            'instrumen_id' => $instrumen_id,
                            'verifikator_id' => $user->sekolah->sekolah_sasaran->verifikator_id,
                        ],
                        [
                            'indikator_id' => $instrumen->indikator->id,
                            'atribut_id' => $instrumen->indikator->atribut->id,
                            'aspek_id' => $instrumen->indikator->atribut->aspek->id,
                            'komponen_id' => $komponen->id,
                            'nilai' => $jawaban->urut,
                        ]
                    );
                    Nilai_instrumen::updateOrCreate(
                        [
                            'user_id' => $user->user_id,
                            'instrumen_id' => $instrumen_id,
                            'verifikator_id' => $user->sekolah->sekolah_sasaran->verifikator_id,
                        ],
                        [
                            'nilai' => $jawaban->urut,
                            'predikat' => HelperModel::predikat($jawaban->urut),
                        ]
                    );
                }
            }
        }
        $this->command->getOutput()->writeln($user->username);
    }
}
