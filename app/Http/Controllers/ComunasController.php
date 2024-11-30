<?php
namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class ComunasController extends Controller
{
    public function index(Ciudad $ciudad)
    {
        $comunas = $ciudad->comunas;
        return response()->json($comunas);
    }
}
