<?php

namespace App;

class HelperModel
{
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
}
