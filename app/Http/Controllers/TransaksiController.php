<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;

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
        ->with(['trader.upline'])
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
        $all_data = Transaksi::whereHas('trader', function($query){
            $query->whereNotNull('email');
        })
        ->with(['trader.upline'])
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
}
