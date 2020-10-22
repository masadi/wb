<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sekolah;
use App\Wilayah;
use File;
class PetaController extends Controller
{
    public function geojson($file){
        $json = File::get('geojson/'.$file.'.geojson');
        $json = json_decode($json);
        return response()->json(['data' => $json]);
    }
    public function index()
    {
        
        $provinsi = [
			1 => [
				'position' => "2.033693313598633, 97.388917922973633",
				'nama'	=> 'Aceh',
				'json' => '060000'
			],
			2 => [
				'position' => "-8.810595, 115.235046",
				'nama'	=> 'Bali',
				'json' => '220000'
			],
			3 => [
				'position' => "-6.973758, 105.771698",
				'nama'	=> 'Banten',
				'json' => '280000'
			],
			4 => [
				'position' => "-5.470931, 102.379631",
				'nama'	=> 'Bengkulu',
				'json' => '260000'
			],
			5 => [
				'position' => "0.438201, 121.528145",
				'nama'	=> 'Gorontalo',
				'json' => '300000'
			],
			6 => [
				'position' => "-6.10064, 106.875961",
				'nama'	=> 'D.K.I. Jakarta',
				'json'	=> '010000'
			],
			7 => [
				'position' => "-1.048483, 104.198479",
				'nama'	=> 'Jambi',
				'json' => '100000'
			],
			8 => [
				'position' => "-6.278363, 108.39109",
				'nama'	=> 'Jawa Barat',
				'json' => '020000'
			],
			9 => [
				'position' => "-7.699827, 108.779991",
				'nama'	=> 'Jawa Tengah',
				'json' => '030000'
			],
			10 => [
				'position' => "-8.619612, 113.994209",
				'nama'	=> 'Jawa Timur',
				'json' => '050000'
			],
			11 => [
				'position' => "-2.898015, 110.193603",
				'nama'	=> 'Kalimantan Barat',
				'json' => '130000'
			],
			12 => [
				'position' => "-4.370506, 115.981018",
				'nama'	=> 'Kalimantan Selatan',
				'json' => '150000'
			],
			13 => [
				'position' => "-3.509027, 112.571129",
				'nama'	=> 'Kalimantan Tengah',
				'json' => '140000'
			],
			14 => [
				'position' => "-1.166898, 116.75486",
				'nama'	=> 'Kalimantan Timur',
				'json' => '160000'
			],
			15 => [
				'position' => "2.796055, 117.648933",
				'nama'	=> 'Kalimantan Utara',
				'json' => '340000'
			],
			16 => [
				'position' => "-3.304925, 107.950012",
				'nama'	=> 'Kepulauan Bangka Belitung',
				'json' => '290000'
			],
			17 => [
				'position' => "-0.785525, 104.933334",
				'nama'	=> 'Kepulauan Riau',
				'json' => '310000'
			],
			18 => [
				'position' => "-5.924068, 105.506958",
				'nama'	=> 'Lampung',
				'json' => '120000'
			],
			19 => [
				'position' => "-8.21113062, 128.21574121",
				'nama'	=> 'Maluku',
				'json' => '210000'
			],
			20 => [
				'position' => "-1.981366, 125.924889",
				'nama'	=> 'Maluku Utara',
				'json' => '270000'
			],
			21 => [
				'position' => "-8.907871, 116.36972",
				'nama'	=> 'Nusa Tenggara Barat',
				'json' => '230000'
			],
			22 => [
				'position' => "-10.960045999999863, 122.864883",
				'nama'	=> 'Nusa Tenggara Timur',
				'json' => '240000'
			],
			23 => [
				'position' => "-8.354078, 138.48681600000032",
				'nama'	=> 'Papua',
				'json' => '250000'
			],
			24 => [
				'position' => "-4.129165, 132.90896600000031",
				'nama'	=> 'Papua Barat',
				'json' => '320000'
			],
			25 => [
				'position' => "-0.727526, 103.422699",
				'nama'	=> 'Riau',
				'json' => '090000'
			],
			26 => [
				'position' => "-3.473392, 119.416222",
				'nama'	=> 'Sulawesi Barat',
				'json' => '330000'
			],
			27 => [
				'position' => "-7.477339, 121.2603",
				'nama'	=> 'Sulawesi Selatan',
				'json' => '190000'
			],
			28 => [
				'position' => "-3.51971435546875, 122.688720703125",
				'nama'	=> 'Sulawesi Tengah',
				'json' => '180000'
			],
			29 => [
				'position' => "-6.132399, 124.61084",
				'nama'	=> 'Sulawesi Tenggara',
				'json' => '200000'
			],
			30 => [
				'position' => "0.418617, 124.35714",
				'nama'	=> 'Sulawesi Utara',
				'json' => '170000'
			],
			31 => [
				'position' => "-3.29647, 100.347321",
				'nama'	=> 'Sumatera Barat',
				'json' => '080000'
			],
			32 => [
				'position' => "-2.404385, 104.93335",
				'nama'	=> 'Sumatera Selatan',
				'json' => '110000'
			],
			33 => [
				'position' => "-0.636296, 98.510979",
				'nama'	=> 'Sumatera Utara',
				'json' => '070000'
			],
			34 => [
				'position' => "-7.695138, 110.483795",
				'nama'	=> 'D.I. Yogyakarta',
				'json' => '040000'
			]
		];
        return response()->json(['data' => $provinsi]);
    }
    public function sekolah(Request $request)
    {
        $sekolah = Sekolah::where(function($query) use ($request){
            if($request->id_level_wilayah == 1){
                $query->whereRaw("trim(provinsi_id) = '".request()->kode_wilayah."'");
            } else {
                $query->whereRaw("trim(kabupaten_id) = '".request()->kode_wilayah."'");
            }
        })->count();
        $id_level_wilayah = request()->id_level_wilayah;
        if($id_level_wilayah == 1) {
            $wilayah = '_provinsi';
        } else {
            $wilayah = '_kabupaten';
        }
        $with = 'sekolah'.$wilayah;
        $with_coe = 'sekolah_coe'.$wilayah;
        $sekolah = Wilayah::withCount([$with.' as jml_smk', $with_coe.' as smk_coe'])->whereRaw("trim(kode_wilayah) = '".request()->kode_wilayah."'")->first();
        return response()->json(['data' => $sekolah, 'wilayah' => $wilayah]);
        
    }
    public function wilayah(Request $request)
    {
        $kode_wilayah = $request->kode_wilayah;
        $wilayah = Wilayah::whereRaw("trim(mst_kode_wilayah) = '".$kode_wilayah."'")->select('kode_wilayah as json', 'nama')->get();
        return response()->json(['data' => $wilayah]);
    }
    
}
