<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Komponen;
use App\Aspek;
use App\Atribut;
use App\Indikator;
use App\Instrumen;
use App\Sekolah;
use App\Ptk;
use App\Peserta_didik;
use App\Nilai_instrumen;
use App\Nilai_akhir;
use App\Pakta_integritas;
use App\Verval;
use App\Verifikasi;
use App\User;
use App\HelperModel;
use App\Sekolah_sasaran;
use App\Tahun_pendataan;
use Carbon\Carbon;
use File;
use Validator;
use PDF;
class ReferensiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_komponen($request)
    {
        $all_data = Komponen::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_aspek($request)
    {
        $all_data = Aspek::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_atribut($request)
    {
        $all_data = Atribut::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_indikator($request)
    {
        $all_data = Indikator::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_sekolah($request)
    {
        $all_data = Sekolah::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
            if(request()->verifikator_id){
                $query->whereDoesntHave('sekolah_sasaran', function ($query) {
                    $query->where('verifikator_id', request()->verifikator_id);
                });
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('npsn', 'ilike', '%' . request()->q . '%')
                ->orWhere('kecamatan', 'ilike', '%' . request()->q . '%')
                ->orWhere('kabupaten', 'ilike', '%' . request()->q . '%')
                ->orWhere('provinsi', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_ptk($request)
    {
        $all_data = Ptk::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('nuptk', 'ilike', '%' . request()->q . '%')
                ->orWhere('nik', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_pd($request)
    {
        $all_data = Peserta_didik::where(function($query){
            if(request()->sekolah_id){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%')
                ->orWhere('no_induk', 'ilike', '%' . request()->q . '%')
                ->orWhere('nisn', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
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
    public function get_detil_sekolah($request){
        if($request->sekolah_id){
            $sekolah = Sekolah::withCount(['ptk', 'pd'])->find($request->sekolah_id);
            $nilai = [
                'grade_personal' => 10,
                'grade_sekolah' => 1,
            ];
            $custom = collect($nilai);
            $data = $custom->merge($sekolah);
        } else {
            $nilai = [
                'sekolah' => Sekolah::count(),
                'ptk_count' => Ptk::count(),
                'grade_personal' => 10,
                'grade_sekolah' => 1,
            ];
            $data = collect($nilai);
        }
        $user = User::find($request->user_id);
        $instrumen = Instrumen::where('urut', 0)->count();
        $nilai_instrumen = Nilai_instrumen::where('user_id', $request->user_id)->count();
        $hitung = Nilai_akhir::where('user_id', $request->user_id)->first();
        $pakta = Pakta_integritas::where('user_id', $request->user_id)->first();
        $verval = Verval::where('sekolah_id', $user->sekolah_id)->first();
        $verifikasi = Verifikasi::where('sekolah_id', $user->sekolah_id)->first();
        $progres = [
            'kuisioner' => ($instrumen == $nilai_instrumen),
            'hitung' => ($hitung) ? HelperModel::TanggalIndo($hitung->updated_at) : NULL,
            'pakta' => ($pakta) ? HelperModel::TanggalIndo($pakta->updated_at) : NULL,
            'verval' => ($verval) ? HelperModel::TanggalIndo($verval->updated_at) : NULL,
            'verifikasi' => ($verifikasi) ? HelperModel::TanggalIndo($verifikasi->created_at) : NULL,
            'pengesahan' => ($verifikasi) ? ($verifikasi->verifikasi) ? HelperModel::TanggalIndo($pengesahan->updated_at) : NULL : NULL,
        ];
        $data = $data->merge($progres);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function cetak(){
        $data['all_komponen'] = Komponen::with(['aspek.instrumen' => function($query){
            $query->with(['subs']);
            $query->where('urut', 0);
        }])->get();
        //return view('cetak.instrumen', $data);
        $pdf = PDF::loadView('cetak.instrumen', $data, [], [
            'format' => [220, 330],
        ]);
        //return $pdf->stream('instrumen.pdf');
		return $pdf->download('instrumen.pdf');
    }
    public function get_verifikator(){
        $users = User::where(function($query){
            $query->whereRoleIs('verifikator');
        })->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($posts) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $posts = $posts->where('name', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('email', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('username', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $users]);
    }
    public function sekolah_sasaran(Request $request){
        $tahun = Tahun_pendataan::where('periode_aktif', 1)->first();
        $insert = Sekolah_sasaran::updateOrCreate([
            'sekolah_id' => $request->sekolah_id,
            'verifikator_id' => $request->verifikator_id,
            'tahun_pendataan_id' => $tahun->tahun_pendataan_id,
        ]);
        if($insert){
            $response = [
                'title' => 'Berhasil',
                'text' => 'Sekolah sasaran berhasil ditambahkan',
                'icon' => 'success',
            ];
        } else {
            $response = [
                'title' => 'Gagal',
                'text' => 'Sekolah sasaran gagal ditambahkan',
                'icon' => 'error',
            ];
        }
        return response()->json($response);
    }
    public function get_sekolah_sasaran($request){
        return $this->get_sekolah($request);
    }
}
