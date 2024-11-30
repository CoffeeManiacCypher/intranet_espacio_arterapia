<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadesController extends Controller
{
    public function index()
    {
        $ciudades = Ciudad::with('comunas')->get();
        return response()->json($ciudades);
    }
}
