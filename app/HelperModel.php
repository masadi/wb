<?php

namespace App;
use App\Komponen;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Nilai_akhir;
use App\Tahun_pendataan;
use App\User;
use App\Instrumen;
use App\Status_rapor;
use App\Jenis_rapor;
class HelperModel
{
    public static function nilai_komponen($komponen_id, $user_id = NULL, $verifikator_id = NULL){
        return Nilai_komponen::where(function($query) use ($komponen_id, $user_id, $verifikator_id){
            $query->where('komponen_id', $komponen_id);
            $query->where('user_id', $user_id);
            if($verifikator_id){
                $query->where('verifikator_id', $verifikator_id);
            } else {
                $query->whereNull('verifikator_id');
            }
        })->first();
    }
    public static function bobot_komponen($komponen_id){
        return Aspek::where('komponen_id', $komponen_id)->sum('bobot');
    }
    public static function status_rapor_mutu($status){
        $status_rapor = Status_rapor::where('status', $status)->first();
        return $status_rapor->id;
    }
    public static function jenis_rapor_mutu($jenis){
        $jenis_rapor = Jenis_rapor::where('jenis', $jenis)->first();
        return $jenis_rapor->id;
    }
    public static function bintang_icon($nilai, $color){
        $html = '';
        if ($nilai > 0 && $nilai <= 20){
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
        } elseif($nilai >= 21 && $nilai <= 40) {
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
        } elseif($nilai >= 41 && $nilai <= 60) {
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
        } elseif($nilai >= 61 && $nilai <= 80) {
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="far fa-star"></i>';
        } elseif($nilai >= 81) {
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
            $html .= '<i class="fas fa-star text-'.$color.'"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
            $html .= '<i class="far fa-star"></i>';
        }
        return $html;
    }
    public static function bintang_pdf($nilai, $satuan = false){
        $html = '';
        if($satuan){
            if($nilai == 1){
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai == 2) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai == 3) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai == 4) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } else {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
            }
        } else {
            if($nilai < 20){
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai < 40) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai < 60) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } elseif($nilai < 80) {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_white.png').'" alt="">';
            } else {
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
                $html .= '<img src="'.asset('vendor/img/star_color.png').'" alt="">';
            }
        }
        return $html;
    }
    public static function clean($string) {
        $string = trim($string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    public static function rapor_mutu($user_id){
        $user = User::withCount(['nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }])->with(['nilai_akhir', 'last_nilai_instrumen' => function($query){
            $query->whereNull('verifikator_id');
        }, 'sekolah' => function($query){
            $query->with(['smk_coe', 'sekolah_sasaran' => function($query){
                $query->with(['pakta_integritas', 'waiting', 'proses', 'terima', 'tolak']);
            }]);
        }])->find($user_id);
        if($user->hasRole('sekolah')){
            $instrumen = Instrumen::where('urut', 0)->count();
            $smk_coe = $user->sekolah->smk_coe;
            $pakta = NULL;
            $verval = NULL;
            $proses = NULL;
            $terima = NULL;
            $tolak = NULL;
            $sasaran = $user->sekolah->sekolah_sasaran;
            if($sasaran){
                $pakta = $sasaran->pakta_integritas;
                $verval = $sasaran->waiting;
                $proses = $sasaran->proses;
                $terima = $sasaran->terima;
                $tolak = $sasaran->tolak;
            }
            //$obj1 = new \stdClass;
            $obj = (object) [
                'data' => [$user->nilai_instrumen_count, ($instrumen - $user->nilai_instrumen_count)],
                'backgroundColor' => ['#28a745', '#dc3545'],
                'borderWidth' => 3,
                'label' => 'Progres Kemajuan'
            ];
            $obj_maksimum_nilai = (object) [
                'label' => 'Nilai Maksimal',
                'backgroundColor' => '#dc3545',
                'data' => [100],
            ];
            $nilai = 0;
            $nilai_rapor_mutu_tercapai = 0;
            $nilai_rapor_mutu_belum_tercapai = 0;
            $backgroundColor = '#dc3545';
            if($user->nilai_akhir){
                $nilai_rapor_mutu_tercapai = $user->nilai_akhir->nilai;
                $nilai_rapor_mutu_belum_tercapai = (100 - $nilai_rapor_mutu_tercapai);
                if($nilai_rapor_mutu_tercapai < 21){
                    $backgroundColor = '#dc3545';
                } elseif($nilai_rapor_mutu_tercapai < 41){
                    $backgroundColor = '#ffc107';
                } elseif($nilai_rapor_mutu_tercapai < 61){
                    $backgroundColor = '#ff851b';
                } elseif($nilai_rapor_mutu_tercapai < 81){
                    $backgroundColor = '#39cccc';
                } elseif($nilai_rapor_mutu_tercapai >= 81){
                    $backgroundColor = '#28a745';
                }
            }
            $obj_nilai_rapor = (object) [
                /*'barPercentage' => 10,
                'barThickness' => 100,
                'maxBarThickness' => 108,
                'minBarLength' => 2,*/
                'label' => 'Nilai Tercapai',
                'backgroundColor' => $backgroundColor,
                'data' => [($user->nilai_akhir) ? $user->nilai_akhir->nilai : 0],
            ];
            $komponen = Komponen::get();
            foreach($komponen as $k){
                $bobot_belum_tercapai = 0;
                if(self::nilai_komponen($k->id, $user->user_id)){
                    $bobot_belum_tercapai = (self::bobot_komponen($k->id) - self::nilai_komponen($k->id, $user->user_id)->nilai);
                }
                $nilai_belum_tercapai = 0;
                if(self::nilai_komponen($k->id, $user->user_id)){
                    $nilai_belum_tercapai = (100 - self::nilai_komponen($k->id, $user->user_id)->total_nilai);
                }
                $values['nama'][] = $k->nama;
                $values['nilai_tercapai'][] = (self::nilai_komponen($k->id, $user->user_id)) ? self::nilai_komponen($k->id, $user->user_id)->total_nilai : 0;
                //$values['nilai_belum_tercapai'][] = (self::nilai_komponen($k->id, $user->user_id)) ? (self::nilai_komponen($k->id, $user->user_id)->total_nilai - (self::bobot_komponen($k->id) * 100 / self::bobot_komponen($k->id))) : 0;
                $bobot_tercapai = (self::nilai_komponen($k->id, $user->user_id)) ? self::nilai_komponen($k->id, $user->user_id)->nilai : 0;
                $nilai_komponen = (self::nilai_komponen($k->id, $user->user_id)) ? self::nilai_komponen($k->id, $user->user_id)->nilai : 0;
                $values['nilai_belum_tercapai'][] = number_format($nilai_belum_tercapai,2,'.','.');
                $values['bobot_tercapai'][] = number_format($bobot_tercapai,2,'.','.');
                $values['bobot_belum_tercapai'][] = number_format($bobot_belum_tercapai,2,'.','.');
                $values['nilai_komponen'][] = number_format($nilai_komponen,2,'.','.');
                $values['bobot_komponen'][] = self::bobot_komponen($k->id);
            }
            $data = [
                'user' => $user,
                'smk_coe' => $smk_coe,
                'instrumen' => ($instrumen == $user->nilai_instrumen_count) ? self::TanggalIndo($user->last_nilai_instrumen->updated_at) : NULL,
                'hitung' => ($user->nilai_akhir) ? self::TanggalIndo($user->nilai_akhir->updated_at) : NULL,
                'pakta' => ($pakta) ? self::TanggalIndo($pakta->updated_at) : NULL,
                'verval' => ($verval) ? self::TanggalIndo($verval->updated_at) : NULL,
                'proses' => ($proses) ? self::TanggalIndo($proses->created_at) : NULL,
                'terima' => ($terima) ? self::TanggalIndo($terima->updated_at) : NULL, 
                'tolak' => ($tolak) ? self::TanggalIndo($tolak->updated_at) : NULL, 
                'kemajuan' => [
                    'type' => 'doughnut',
                    'data' => [
                        'labels' => ['Terjawab', 'Belum Terjawab'],
                        'datasets' => [
                            $obj
                        ]
                    ],
                ],
                'nilai_rapor' => [
                    'type' => 'bar',
                    'data' => [
                        'labels' => ['Nilai Rapor Mutu Sekolah'],
                        'datasets' => [
                            $obj_nilai_rapor
                        ]
                    ],
                    'options' => [
                        'tooltips' => (object) [
                            'mode' =>  'index',
                            'intersect' => false
                        ],
                        'responsive' => true,
                        'scales' => (object) [
                            'xAxes' => [ 
                                (object) ['stacked' => true]
                            ],
                            'yAxes' => [
                                (object) [
                                    'stacked' => true,
                                    'ticks' => (object) [
                                        'suggestedMin' => 50,
                                        'suggestedMax' => 100
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                /*'nilai_komponen' => [
                    'type' => 'radar',
                    'data' => [
                        'labels' =>  $values['nama'],
                        'datasets' => [
                            (object) [
                                'label' => 'Nilai Komponen',
                                'backgroundColor' => "transparent",
                                'borderColor' => "rgba(200,0,0,0.6)",
                                'fill' => true,
                                'radius' => 6,
                                'pointRadius' => 6,
                                'pointBorderWidth' => 3,
                                'pointBackgroundColor' => "orange",
                                'pointBorderColor' => "rgba(200,0,0,0.6)",
                                'pointHoverRadius' => 10,
                                'data' => $values['nilai']
                            ]
                        ],
                    ],
                    'options' => [
                        'scale' => (object)[
                            'ticks' => (object)[
                                'beginAtZero' => true,
                                'min' => 0,
                                'max' => 100,
                                'stepSize' => 20
                            ],
                        ],
                    ],
                ],*/
                'nilai_komponen' => [
                    'nilai_tercapai' => $values['nilai_tercapai'],
                    'nilai_belum_tercapai' => $values['nilai_belum_tercapai'],
                    'labels' =>  $values['nama'],
                    'bobot_tercapai' =>  $values['bobot_tercapai'],
                    'bobot_belum_tercapai' =>  $values['bobot_belum_tercapai'],
                ],
                'nilai_rapor_mutu' => [
                    //'nilai_tercapai' => array_merge($values['nilai_tercapai'], [$nilai_rapor_mutu_tercapai]),
                    //'nilai_belum_tercapai' => array_merge($values['nilai_belum_tercapai'], [$nilai_rapor_mutu_belum_tercapai]),
                    //'labels' =>  array_merge($values['nama'], ['Rapor Mutu Sekolah']),
                    //'bobot_tercapai' =>  array_merge($values['bobot_tercapai'], [$nilai_rapor_mutu_tercapai]),
                    //'bobot_belum_tercapai' =>  array_merge($values['bobot_belum_tercapai'], [$nilai_rapor_mutu_belum_tercapai]),
                    //'varian' => ['success', 'warning', 'danger', 'indigo', 'fuchsia', 'primary'],
                    'nilai_tercapai' => $values['nilai_tercapai'],
                    'nilai_belum_tercapai' => $values['nilai_belum_tercapai'],
                    'labels' =>  $values['nama'],
                    'bobot_tercapai' =>  $values['bobot_tercapai'],
                    'bobot_belum_tercapai' =>  $values['bobot_belum_tercapai'],
                    'varian' => ['success', 'warning', 'danger', 'indigo', 'fuchsia'],
                ],
            ];
            //dd($data);
        } elseif($user->hasRole('penjamin_mutu')){
            $data = [];
        } elseif($user->hasRole('direktorat')){
            $data = [];
        } elseif($user->hasRole('admin')){
            $data = [];
        }
        return $data;
    }
	public static function tahun_pendataan(){
		$tahun = Tahun_pendataan::where('periode_aktif', 1)->first();
		return ($tahun) ? $tahun->tahun_pendataan_id : NULL;
	}
    public static function TanggalIndo($date, $berita_acara = false){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
        $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun; 
        if($berita_acara){
            $result = "tanggal " . self::terbilang($tgl) . " bulan " . $BulanIndo[(int)$bulan-1] . " tahun ". self::terbilang($tahun); 
        }
		return($result);
    }
    public static function hari_ini(){
        $hari = date ("D");
        switch($hari){
            case 'Sun':
                $hari_ini = "Minggu";
            break;
            case 'Mon':			
                $hari_ini = "Senin";
            break;
            case 'Tue':
                $hari_ini = "Selasa";
            break;
            case 'Wed':
                $hari_ini = "Rabu";
            break;
            case 'Thu':
                $hari_ini = "Kamis";
            break;
            case 'Fri':
                $hari_ini = "Jumat";
            break;
            case 'Sat':
                $hari_ini = "Sabtu";
            break;
            default:
                $hari_ini = "Tidak di ketahui";		
            break;
        }
        return $hari_ini;
    }
    public static function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = self::penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = self::penyebut($nilai/10)." puluh". self::penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . self::penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = self::penyebut($nilai/100) . " ratus" . self::penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . self::penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = self::penyebut($nilai/1000) . " ribu" . self::penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = self::penyebut($nilai/1000000) . " juta" . self::penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = self::penyebut($nilai/1000000000) . " milyar" . self::penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = self::penyebut($nilai/1000000000000) . " trilyun" . self::penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	public static function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(self::penyebut($nilai));
		} else {
			$hasil = trim(self::penyebut($nilai));
		}     		
		return ucwords($hasil);
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
                $total_nilai_komponen = ($nilai_komponen * $all_bobot) / 100;
                $nilai_komponen = number_format($nilai_komponen,2,'.','.');
                $total_nilai_komponen = number_format($total_nilai_komponen,2,'.','.');
                Nilai_komponen::updateOrCreate(
                    [
                        'user_id' => $user_id,
                        'komponen_id' => $komponen->id,
                        'verifikator_id' => $verifikator_id,
                    ],
                    [
                        'nilai' => $total_nilai_komponen,
                        'total_nilai' => $nilai_komponen,
                        'predikat' => self::predikat($nilai_komponen, true),
                    ]
                );
            }
            $total_nilai += $total_nilai_komponen;
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
