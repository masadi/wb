<?php

namespace App;
use App\Komponen;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Nilai_akhir;
use App\Tahun_pendataan;
class HelperModel
{
	public static function tahun_pendataan(){
		$tahun = Tahun_pendataan::where('periode_aktif', 1)->first();
		return ($tahun) ? $tahun->tahun_pendataan_id : NULL;
	}
    public static function TanggalIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun; 
		return($result);
	}
	public static function predikat($nilai, $puluhan = false){
		$predikat = '-';
		if($puluhan){
			if($nilai < 21){
				$predikat = 'Sangat Kurang';
			} elseif($nilai < 41){
				$predikat = 'Kurang';
			} elseif($nilai < 61){
				$predikat = 'Cukup';
			} elseif($nilai < 81){
				$predikat = 'Baik';
			} elseif($nilai >= 81){
				$predikat = 'Sangat Baik';
			}
		} else {
			if($nilai == 1){
				$predikat = 'Sangat Kurang';
			} elseif($nilai == 2){
				$predikat = 'Kurang';
			} elseif($nilai == 3){
				$predikat = 'Cukup';
			} elseif($nilai == 4){
				$predikat = 'Baik';
			} elseif($nilai == 5){
				$predikat = 'Sangat Baik';
			}
		}
		return $predikat;
	}
	public static function generate_nilai($user_id, $verifikator_id){
		$callback = function($query) use ($user_id, $verifikator_id){
			$query->where('user_id', $user_id);
			$query->where('verifikator_id', $verifikator_id);
        };
        $all_komponen = Komponen::with(['aspek.jawaban' => $callback,'aspek.instrumen' => function($query) use ($callback){
            $query->whereNull('ins_id');
        }])->get();
        $total_nilai = 0;
        foreach($all_komponen as $komponen){
            $all_nilai_aspek = 0;
            $instrumen_id = $komponen->aspek->map(function($aspek) use ($user_id, $verifikator_id, &$all_nilai_aspek){
                $instrumen_id = $aspek->jawaban()->where(function($query) use ($user_id, $verifikator_id){
                    $query->where('user_id', $user_id);
                    $query->where('verifikator_id', $verifikator_id);
                })->select('instrumen_id')->get()->keyBy('instrumen_id')->keys()->all();
                $nilai = $aspek->jawaban()->where(function($query) use ($user_id, $verifikator_id){
                    $query->where('user_id', $user_id);
                    $query->where('verifikator_id', $verifikator_id);
                })->sum('nilai');
                if($nilai){
                    $skor = $aspek->instrumen()->whereIn('instrumen_id', $instrumen_id)->sum('skor');
                    $nilai_aspek_dibobot = $nilai * $aspek->bobot / $skor;
                    $nilai_aspek = $nilai * 100 / $skor;
                    $nilai_aspek = number_format($nilai_aspek,2,'.','.');
                    Nilai_aspek::updateOrCreate(
                        [
                            'user_id' => $user_id,
                            'aspek_id' => $aspek->id,
                            'komponen_id' => $aspek->komponen_id,
                            'verifikator_id' => $verifikator_id,
                        ],
                        [
                            'nilai' => $nilai_aspek_dibobot,
                            'total_nilai' => $nilai_aspek,
                            'predikat' => self::predikat($nilai_aspek, true),
                        ]
                    );
                }
            });
            $all_nilai_aspek = Nilai_aspek::where(function($query) use ($user_id, $verifikator_id, $komponen){
				$query->where('user_id', $user_id);
				$query->where('verifikator_id', $verifikator_id);
				$query->where('komponen_id', $komponen->id);
			})->sum('nilai');
            if($all_nilai_aspek){
                $all_bobot = $komponen->aspek()->sum('bobot');
                $nilai_komponen = ($all_nilai_aspek*100)/$all_bobot;
                $nilai_komponen = number_format($nilai_komponen,2,'.','.');
                Nilai_komponen::updateOrCreate(
                    [
                        'user_id' => $user_id,
                        'komponen_id' => $komponen->id,
                        'verifikator_id' => $verifikator_id,
                    ],
                    [
                        'nilai' => $nilai_komponen,
                        'total_nilai' => $nilai_komponen,
                        'predikat' => self::predikat($nilai_komponen, true),
                    ]
                );
            }
            $total_nilai += $nilai_komponen;
        }
        Nilai_akhir::updateOrCreate(
            [
                'user_id' => $user_id,
                'verifikator_id' => $verifikator_id,
            ],
            [
                'nilai' => $total_nilai,
                'predikat' => self::predikat($total_nilai, true),
            ]
        );
	}
}
