<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Forzamos la eliminación de la tabla si de casualidad quedó huérfana en la base de datos
        Schema::dropIfExists('citas');

        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Relación con pacientes (en minúsculas)
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            
            // La columna del problema, definida explícitamente
            $table->dateTime('fecha_hora'); 
            
            $table->string('motivo');
            $table->string('estado')->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};