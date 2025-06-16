<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produto_tecido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->foreignId('tecido_id')->constrained('tecidos')->onDelete('cascade');
            $table->decimal('consumo', 10, 3);
            $table->timestamps();
            
            $table->unique(['produto_id', 'tecido_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('produto_tecido');
    }
};