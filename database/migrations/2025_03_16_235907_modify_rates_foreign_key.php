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
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['post_id']); // Drop existing foreign key
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete(); // Add cascade delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['post_id']); // Rollback cascade
            $table->foreign('post_id')->references('id')->on('posts'); // Restore old foreign key
        });
    }
};
