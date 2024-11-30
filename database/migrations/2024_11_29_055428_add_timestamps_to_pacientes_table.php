<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    

    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->timestamps(); // Esto añade `created_at` y `updated_at`
        });
    }
    
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
    
};
