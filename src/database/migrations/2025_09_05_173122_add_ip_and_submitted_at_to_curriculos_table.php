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
        Schema::table('curriculos', function (Blueprint $table) {
            $table->ipAddress('ip_address')->after('arquivo_original_name');
            $table->timestamp('submitted_at')->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curriculos', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'submitted_at']);
        });
    }
};
