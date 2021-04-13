<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $all_data = [];
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
}
