<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use App\Trader;

class TransaksiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_rebate($request){
        $all_data = Transaksi::whereHas('trader_email', function($query){
            $query->whereNotNull('email');
        })
        ->with(['trader_email.upline.trader'])
        ->selectRaw('email')
        ->selectRaw('tanggal_upload')
        ->selectRaw("SUM(volume) as volume_trading")
        ->selectRaw("SUM(laba_ib) as sum_laba_ib")
        ->selectRaw("SUM(rebate) as jumlah_rebate")
        ->groupBy('email')
        ->groupBy('tanggal_upload')
        ->paginate(10);
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_komisi($request){
        $all_data = Transaksi::whereHas('trader_email', function($query){
            $query->whereNotNull('email');
            $query->whereHas('downline');
        })
        ->with(['trader_email' => function($query){
            $query->with(['upline.trader', 'transaksi_downline']);
        }])
        ->selectRaw('email')
        ->selectRaw('tanggal_upload')
        ->selectRaw("SUM(volume) as volume_trading")
        ->selectRaw("ROUND(SUM(laba_ib),2) as sum_laba_ib")
        //->selectRaw("ROUND(SUM(laba_ib) * dollar,2) as sum_laba_ib")
        ->selectRaw("SUM(rebate) as jumlah_rebate")
        ->groupBy('dollar')
        ->groupBy('email')
        ->groupBy('tanggal_upload')
        ->paginate(10);
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_detil_komisi($request){
        $data = Trader::with(['downline' => function($query){
            $query->with(['trader', 'transaksi']);
        }])->find($request->trader_id);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
