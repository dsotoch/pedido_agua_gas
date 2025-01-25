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
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('direccion', 255)->nullable(); // Dirección del negocio
            $table->string('descripcion', 1000)->nullable();
            $table->json('imagenes')->nullable(); // Galería de imágenes como JSON
            $table->string('whatsapp', 50)->nullable(); // URL de WhatsApp
            $table->string('facebook', 255)->nullable(); // URL de Facebook
            $table->string('telefono', 20)->nullable(); // Número de teléfono
            $table->string('servicios', 100)->nullable(); // Servicios ofrecidos (agua, gas, etc.)
            $table->json('logo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            //
        });
    }
};
