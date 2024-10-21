<?php

namespace App\Http\Controllers;

use App\Services\DualDatabaseConnection;

class DatabaseTestController extends Controller
{
    public function index()
    {
        try {
            $connection = DualDatabaseConnection::getConnection();
            return response()->json(['message' => 'Conexión exitosa']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
