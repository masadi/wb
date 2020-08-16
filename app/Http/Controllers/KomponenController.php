<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
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
}
