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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            $table->string('nombres');
            $table->string('apellidos');
            
            $table->enum('tipo_cedula', ['V', 'E'])->default('V');
            $table->string('cedula');
            $table->unique(['tipo_cedula', 'cedula']);


            $table->enum('tipo_rif', ['V', 'J', 'E'])->nullable(); 
            $table->string('rif')->nullable();
            $table->unique(['tipo_rif', 'rif']); 
            
            $table->string('correo')->unique();
            $table->string('telefono')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['masculino', 'femenino'])->nullable();
            $table->text('direccion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};