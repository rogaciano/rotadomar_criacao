<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estilistas', function (Blueprint $table) {
            if (!Schema::hasColumn('estilistas', 'user_id')) {
                $table->foreignId('user_id')->nullable()->unique()->after('marca_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('estilistas', function (Blueprint $table) {
            if (Schema::hasColumn('estilistas', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropUnique('estilistas_user_id_unique');
                $table->dropColumn('user_id');
            }
        });
    }
};
