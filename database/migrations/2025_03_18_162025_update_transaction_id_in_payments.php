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
        Schema::table('payments', function (Blueprint $table) {
            // Drop the old column first
            $table->dropColumn('transaction_id');

            // Add the new unsignedBigInteger column with a foreign key
            $table->unsignedBigInteger('transaction_id')->nullable()->after('id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rollback changes: drop foreign key and column, then restore original column
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
            $table->string('transaction_id')->nullable()->after('id');
        });
    }
};
