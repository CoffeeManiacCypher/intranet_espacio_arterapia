<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    

    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if (!Schema::hasColumn('pacientes', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('pacientes', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }
    
    
};
