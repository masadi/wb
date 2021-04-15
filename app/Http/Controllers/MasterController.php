<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Validator;
use App\Trader;
use App\Transaksi;
use App\Sub_ib;
use App\Komisi;
use App\Downline;
use App\Upline;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class MasterController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_dollar($request){
        $dollar = Setting::where('key', 'dollar')->first();
        return response()->json(['status' => 'success', 'data' => $dollar]);
    }
    public function simpan_data(Request $request){
        if($request->route('query') == 'dollar'){
            $messages = [
                'dollar.required'	=> 'Nominal tidak boleh kosong',
                'dollar.numeric'	=> 'Nominal harus berupa angka',
            ];
            $validator = Validator::make(request()->all(), [
                'dollar' => 'required|numeric',
            ],
            $messages
            )->validate();
            $data = Setting::where('key', 'dollar')->update(['value' => $request->dollar]);
            return response()->json(['status' => 'success', 'data' => $data, 'message' => 'Dollar berhasil diperbaharui']);
        } elseif($request->route('query') == 'file'){
            $messages = [
                'file.required'	=> 'File Upload tidak boleh kosong',
                'file.mimes'	=> 'File Upload harus berekstensi .XLSX',
            ];
            $validator = Validator::make(request()->all(), [
                'file' => 'required|mimes:xlsx',
                //'file' => 'required',
            ],
            $messages
            )->validate();
            $setting = Setting::where('key', 'dollar')->first();
            $file = $request->file('file');
            $fileExcel = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads', $fileExcel);
            $data = (new FastExcel)->import('uploads/'.$fileExcel, function ($item) use ($setting) {
                $trader = Trader::updateOrCreate(
                    [
                        'nama_lengkap' => $item['Klien'],
                        'nomor_akun' => $item['Akun'],
                    ],
                    [
                        'nilai_rebate' => 8,
                    ]
                );
                $tanggal = strtotime($item['Tanggal']);
                $volume = str_replace(' lots', '', $item['Volume Trading']);
                $rebate = (($volume / $trader->nilai_rebate) * $trader->nilai_rebate)  * $setting->value;
                //=((E2/F2)*F2)*14000
                Transaksi::updateOrCreate(
                    [
                        'trader_id' => $trader->id,
                        'tanggal' => $item['Tanggal'],
                    ],
                    [
                        'volume' => $volume,
                        'komisi' => $trader->nilai_rebate,
                        'dollar' => $setting->value,
                        'rebate' => $rebate,
                        'tanggal_upload' => date('Y-m-d'),
                    ]
                );
            });
            return response()->json(['status' => 'success', 'data' => $data, 'message' => 'File excel berhasil di proses']);
        }
        return response()->json(['status' => 'failed', 'data' => NULL, 'message' => NULL]);
    }
    public function update_data(Request $request){
        if($request->route('query') == 'trader'){
            $messages = [
                'nama_lengkap.required'	=> 'Nama Lengkap tidak boleh kosong',
                'nomor_akun.required'	=> 'nomor_akun tidak boleh kosong',
                'email.required'	=> 'Email tidak boleh kosong',
                'email.email'	=> 'Email tidak valid',
                'telepon.required'	=> 'Nomor HP tidak boleh kosong',
                'bank.required'	=> 'Nama Bank tidak boleh kosong',
                'nomor_rekening.required'	=> 'Nomor Rekening tidak boleh kosong',
                'sub_ib.required'	=> 'Status SUB IB tidak boleh kosong',
                'nilai_rebate.required'	=> 'Nilai Rebate tidak boleh kosong',
                'nilai_rebate.numeric'	=> 'Nilai Rebate harus berupa angka',
            ];
            $validator = Validator::make(request()->all(), [
                'nama_lengkap' => 'required',
                'nomor_akun' => 'required',
                'email' => 'required|email',
                'telepon' => 'required',
                'bank' => 'required',
                'nomor_rekening' => 'required',
                'sub_ib' => 'required',
                'nilai_rebate' => 'required|numeric',
            ],
            $messages
            )->validate();
            $data = Trader::where('id', $request->id)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nomor_akun' => $request->nomor_akun,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'bank' => $request->bank,
                'nomor_rekening' => $request->nomor_rekening,
                'sub_ib' => (is_array($request->sub_ib)) ? $request->sub_ib['key'] : strtolower($request->sub_ib),
                //'sub_ib_id' => (is_array($request->sub_ib_id)) ? $request->sub_ib_id['id'] : $request->sub_ib_id,
                'nilai_rebate' => $request->nilai_rebate,
            ]);
            if($request->sub_ib_id){
                Upline::updateOrCreate(
                    [
                        'trader_id' => $request->id,
                        'upline_id' => $request->sub_ib_id['id'],
                    ],
                    [
                        'komisi' => (8 - $request->nilai_rebate)
                    ]
                );
                Downline::updateOrCreate(
                    [
                        'trader_id' => $request->sub_ib_id['id'],
                        'downline_id' => $request->id,
                    ],
                    [
                        'komisi' => (8 - $request->nilai_rebate)
                    ]
                );
            } else {
                Upline::where('upline_id', $request->id)->delete();
                Downline::where('trader_id', $request->id)->delete();
            }
            return response()->json(['status' => 'success', 'data' => $data, 'message' => 'Dollar berhasil diperbaharui']);
        } elseif($request->route('query') == 'file'){
        }
        return response()->json(['status' => 'failed', 'data' => NULL, 'message' => NULL]);
    }
    public function get_trader($request)
    {
        $all_data = Trader::with(['upline.trader'])->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->whereNotNull('email')->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_sub_ib($request)
    {
        $all_data = Trader::whereHas('downline')->withCount('downline')->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_new_trader($request)
    {
        $all_data = Trader::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($all_data) {
                $all_data = $all_data->where('nama', 'ilike', '%' . request()->q . '%');
        })->whereNull('email')->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_all_upline(Request $request){
        $all_data = Trader::select('id', 'nama_lengkap')->whereNotNull('email')->where('sub_ib', 'ya')->where('id', '<>', $request->id)->get();//->pluck('nama', 'sekolah_id');
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
}
