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
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['name', 'email']); // Eliminar columnas
        $table->string('usuario', 15)->nullable(false); // Agregar columna 'usuario'
        $table->string('tipo', 255)->nullable(false); // Agregar columna 'tipo'
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['usuario', 'tipo']); // Eliminar columnas nuevas
        $table->string('name'); // Restaura la columna 'name'
        $table->string('email')->unique(); // Restaura la columna 'email'
    });
}
};
