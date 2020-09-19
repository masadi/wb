<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sekolah;
use App\User;
use App\HelperModel;
class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data = Sekolah::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%')
                ->orWhere('npsn', 'LIKE', '%' . request()->q . '%')
                ->orWhere('kabupaten', 'LIKE', '%' . request()->q . '%')
                ->orWhere('provinsi', 'LIKE', '%' . request()->q . '%');
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
        $user = User::find($request->user_id);
        $data = NULL;
        if($user->hasRole('sekolah')){
            $data = HelperModel::rapor_mutu($request->user_id);
        } elseif($user->hasRole('penjamin_mutu')){
            $data = [
                'jml_sekolah_sasaran' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                })->count(),
                'jml_sekolah_sasaran_instrumen' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->has('pakta_integritas');
                })->count(),
                'jml_sekolah_sasaran_no_instrumen' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->doesntHave('pakta_integritas');
                })->count(),
                'jml_sekolah_sasaran_verval' => Sekolah::whereHas('sekolah_sasaran', function($query) use ($request){
                    $query->where('verifikator_id', $request->user_id);
                    $query->has('rapor_mutu');
                })->count(),
            ];
        }
        return response()->json(['status' => 'success', 'data' => $data]);
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
        $sekolah = Sekolah::with(['user'])->find($id);
        $sekolah->nama = $request->nama;
        $sekolah->nama_kepsek = $request->nama_kepsek;
        $sekolah->nip_kepsek = $request->nip_kepsek;
        $sekolah->nama_pengawas = $request->nama_pengawas;
        $sekolah->nip_pengawas = $request->nip_pengawas;
        $sekolah->save();
        $user = $sekolah->user;
        $user->name = $request->nama;
        $user->save();
        return response()->json(['message' => 'Data Sekolah berhasil diperbaharui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Sekolah::find($id);
        $data->delete();
        return response()->json(['message' => 'Data Sekolah berhasil dihapus']);
    }
}
