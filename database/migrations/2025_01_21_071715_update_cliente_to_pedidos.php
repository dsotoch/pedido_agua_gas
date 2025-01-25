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
        Schema::table('pedidos', function (Blueprint $table) {
            // Eliminar la clave foránea existente
            $table->dropForeign(['cliente_id']); // Nombre de la columna de la clave foránea

            // Volver a agregar la clave foránea con nuevas restricciones
            $table->foreign('cliente_id')
                ->references('id')->on('users') // La tabla y columna de referencia
                ->onDelete('cascade'); // Nueva acción en caso de eliminar el cliente

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            //
        });
    }
};
