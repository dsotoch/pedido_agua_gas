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
        Schema::table('persona', function (Blueprint $table) {
            $table->string('correo', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            if (Schema::hasColumn('personas', 'correo')) {
                $table->dropColumn('correo');
            }
        });
    }
};
