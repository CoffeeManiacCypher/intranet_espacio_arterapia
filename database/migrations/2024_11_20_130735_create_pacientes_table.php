<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 12)->nullable(); // Solo permite números chilenos (+569XXXXXXXX)
            $table->string('email')->nullable();
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->date('fecha_nacimiento');
            $table->integer('edad')->generatedAs('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE())')->stored();
            $table->date('fecha_registro')->default(now());
            $table->timestamps(); // Incluye created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
