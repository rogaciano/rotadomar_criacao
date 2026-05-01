<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('endpoint');
                $table->string('p256dh_key');
                $table->string('auth_key');
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('push_subscriptions')) {
            Schema::dropIfExists('push_subscriptions');
        }
    }
};
