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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->foreignId('cliente_id')->nullable()->constrained('cliente')->onDelete('set null'); // Llave foránea con acción al eliminar
            $table->date('fecha'); // Campo de tipo fecha
            $table->double('total', 9, 2); // Campo total con precisión de 9 dígitos, 2 decimales
            $table->string('estado', 255); // Campo estado con longitud máxima de 255 caracteres
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
