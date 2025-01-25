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
        Schema::table('unitarios', function (Blueprint $table) {
            $table->dropColumn('cantidad_gratis');
            $table->string('producto_gratis', 255)->nullable();
            $table->enum('estado', ['comerciable', 'gratis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unitarios', function (Blueprint $table) {
            if (Schema::hasColumn('unitarios', 'producto_gratis')) {
                $table->dropColumn('producto_gratis');
            }
        });
    }
};
