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
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre');
            $table->foreignId('id_tipo_cliente')
                ->constrained('tipo_cliente', 'id_tipo_cliente');
            $table->foreignId('id_tipo_documento')
                ->constrained('tipo_documento', 'id_tipo_documento');
            $table->string('documento')->unique();
            $table->timestamps();
            $table->foreignId('id_domicilio')
                ->unique()
                ->constrained('domicilio', 'id_domicilio')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
