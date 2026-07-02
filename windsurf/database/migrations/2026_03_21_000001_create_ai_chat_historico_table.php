<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chat_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('pergunta');
            $table->text('sql_gerado')->nullable();
            $table->longText('resposta');
            $table->boolean('util')->nullable();
            $table->timestamps();

            $table->index(['util', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_historico');
    }
};
