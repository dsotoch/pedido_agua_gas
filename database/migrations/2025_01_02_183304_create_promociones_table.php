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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id') // Relación con la tabla productos
                ->constrained('productos')
                ->onDelete('cascade');
            $table->unsignedInteger('cantidad'); // Cantidad mínima para la promoción
            $table->decimal('precio_promocional', 10, 2); // Precio especial
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
