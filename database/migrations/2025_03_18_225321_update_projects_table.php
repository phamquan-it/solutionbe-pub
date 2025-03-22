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
        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->change(); // Make user_id nullable
            $table->string('phone')->nullable(); // Add phone field
            $table->string('customer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('user_id')->nullable(false)->change(); // Revert user_id to not nullable
            $table->dropColumn('phone'); // Remove phone field
            $table->dropColumn('customer_name');
        });
    }
};
