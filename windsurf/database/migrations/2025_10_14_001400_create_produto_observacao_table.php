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
        if (!Schema::hasTable('produto_observacao')) {
            Schema::create('produto_observacao', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('produto_id');
                $table->text('observacao');
                $table->unsignedBigInteger('usuario_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
                $table->foreign('usuario_id')->references('id')->on('users')->onDelete('set null');
                $table->index('produto_id');
                $table->index('usuario_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('produto_observacao')) {
            Schema::dropIfExists('produto_observacao');
        }
    }
};
