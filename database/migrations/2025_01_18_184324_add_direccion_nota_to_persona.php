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
            $table->string('direccion',255)->nullable();
            $table->string('nota',500)->nullable();
            $table->string('dni')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            if(Schema::hasColumns('persona',['direccion','nota'])){
                $table->dropColumn('direccion');
                $table->dropColumn('nota');

            }
        });
    }
};
