<?php

use App\Enums\EscolaridadeEnum;
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
        Schema::create('curriculos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone');
            $table->string('cargo_desejado');
            $table->enum('escolaridade', EscolaridadeEnum::values());
            $table->text('observacoes')->nullable();
            $table->string('arquivo_path');
            $table->string('arquivo_original_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculos');
    }
};
