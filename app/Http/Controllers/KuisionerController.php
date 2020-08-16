<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Instrumen;
class KuisionerController extends Controller
{
    public function index(Request $request, $query = NULL, $id = NULL){
        return Komponen::all();
    }
}
