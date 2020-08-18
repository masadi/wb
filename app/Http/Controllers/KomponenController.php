<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Komponen;
use App\Aspek;
use App\Atribut;
use App\Indikator;
use App\Instrumen;
use Carbon\Carbon;
use File;
use Validator;
class KomponenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data = Komponen::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'nama.required'         => 'Nama Komponen tidak boleh kosong',
            'nama.unique'         => 'Nama Komponen terdeteksi existing',
		];
		$validator = Validator::make(request()->all(), [
			'nama'          => 'required|unique:komponen,nama',
		],
		$messages
        )->validate();
        Komponen::create([
            'nama'        => $request->nama,
        ]);
        return response()->json(['message' => 'Komponen berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'nama.required'         => 'Nama Komponen tidak boleh kosong',
            'nama.unique'         => 'Nama Komponen terdeteksi existing',
		];
		$validator = Validator::make(request()->all(), [
			'nama'          => 'required|unique:komponen,nama,'.$id.',id',
		],
		$messages
        )->validate();
        $data = Komponen::find($id);
        $data->nama = $request->nama;
        $data->save();
        return response()->json(['message' => 'Komponen berhasil diperbaharui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Komponen::find($id);
        $data->delete();
        return response()->json(['message' => 'Komponen berhasil dihapus']);
    }
    public function upload(Request $request){
        $messages = [
            'file.required'	=> 'File Upload tidak boleh kosong',
            //'file.mimes'	=> 'File Upload harus berekstensi .XLSX',
        ];
        $validator = Validator::make(request()->all(), [
            //'file' => 'required|mimes:xlsx,csv',
            'file' => 'required',
        ],
        $messages
        )->validate();
        $file = $request->file('file');
        $fileExcel = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads', $fileExcel);
        $insert = 0;
        $komponen = (new FastExcel)->import('uploads/'.$fileExcel, function ($item) use ($request, &$insert){
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
            $insert++;
        });
        File::delete(public_path('uploads/'.$fileExcel));
        if($insert){
            $data = [
                'title' => 'Berhasil',
                'text' => 'Data Instrumen berhasil disimpan',
                'icon' => 'success',
            ];
        } else {
            $data = [
                'title' => 'Gagal',
                'text' => 'Data Instrumen gagal disimpan',
                'icon' => 'error',
            ];
        }
        return response()->json($data);
    }
}
