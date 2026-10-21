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
    Schema::create('facturas',function(Blueprint $table){
        $table->id();
        $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
        $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
        $table->decimal('monto',8,2)->default(25.00);
        $table->string('moneda',3)->default('USD');
        $table->timestamp('fecha_pago');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
