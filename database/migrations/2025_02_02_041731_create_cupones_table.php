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
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del cupón
            $table->enum('tipo', ['porcentaje', 'fijo']); // Tipo de descuento
            $table->decimal('valor', 8, 2); // Valor del descuento
            $table->integer('limite_uso')->nullable(); // Límite de uso (opcional)
            $table->integer('usado')->default(0); // Cantidad de veces usado
            $table->timestamp('expira_en')->nullable(); // Fecha de expiración
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
