<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Berita;
use App\Kategori;
use App\Berita_kategori;
use Validator;
class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $berita = Berita::with(['kategori', 'user'])->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($berita) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $berita = $berita->where('judul', 'LIKE', '%' . request()->q . '%')
                    ->orWhere(function($query){
                        $query->whereHas('user', function($query) {
                            $query->where('name', 'LIKE', '%' . request()->q . '%');
                        });
                    })
                    ->orWhere(function($query){
                        $query->whereHas('kategori', function($query) {
                            //$query->where('nama', 'LIKE', '%' . request()->q . '%');
                            $query->where('nama', 'asd');
                        });
                    });
                    /*->orWhere('kategori', function($query){
                        $query->where('nama', 'LIKE', '%' . request()->q . '%');
                    });*/
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $berita]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kategori(){
        return response()->json(['status' => 'success', 'data' => Kategori::select('id', 'nama')->get()->toArray()]);
    }
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
            'judul.required'         => 'Judul Berita tidak boleh kosong',
            'kategori.required'     => 'Kategori Berita tidak boleh kosong',
            'isi_berita.required'     => 'Isi Berita tidak boleh kosong',
		];
		$validator = Validator::make(request()->all(), [
			'judul'          => 'required',
            'kategori'          => 'required',
            'isi_berita'          => 'required',
		],
		$messages
        )->validate();
        $berita = Berita::create([
            'user_id'        => $request->user_id,
            'judul'   => Str::title($request->judul),
            'slug'              => Str::slug($request->judul),
            'isi_berita'           => $request->isi_berita
        ]);
        foreach($request->kategori as $kategori){
            Berita_kategori::updateOrCreate([
                'kategori_id' => $kategori['id'],
                'berita_id' => $berita->id,
            ]);
        }
        return response()->json(['message' => 'Berita berhasil disimpan']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $berita = Berita::find($id);
        $berita->delete();
        return response()->json(['message' => 'Berita berhasil dihapus']);
    }
}
