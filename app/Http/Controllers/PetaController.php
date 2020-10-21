<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
class PetaController extends Controller
{
    public function index()
    {
        $json = File::get('geojson/000000.geojson');
        $json = json_decode($json);
        return response()->json(['data' => $json]);
    }
}
