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
        Schema::create('domicilio', function (Blueprint $table) {
            $table->id('id_domicilio');
            $table->string('calle');
            $table->integer('numero');
            $table->string('localidad');
            $table->string('barrio')->nullable();
            $table->string('entre_calles')->nullable();
            $table->string('cp')->nullable();
            $table->string('provincia');
            $table->string('pais');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilio');
    }
};