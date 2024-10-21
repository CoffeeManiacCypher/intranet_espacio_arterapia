<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DualDatabaseConnection
{
    public static function getConnection()
    {
        $env = config('app.env'); // Detecta el entorno

        if ($env === 'production') {
            try {
                return DB::connection('bluehosting')->getPdo();
            } catch (\Exception $e) {
                \Log::error('Error de conexión con Bluehosting: ' . $e->getMessage());
                throw $e;
            }
        }

        return DB::connection('mysql')->getPdo();
    }
}
