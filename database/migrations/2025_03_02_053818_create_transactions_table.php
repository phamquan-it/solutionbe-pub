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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->dateTime('transaction_date');
            $table->string('account_number');
            $table->decimal('amount_in', 15, 2)->nullable();
            $table->decimal('amount_out', 15, 2)->nullable();
            $table->decimal('accumulated', 15, 2)->nullable();
            $table->string('code')->nullable();
            $table->text('transaction_content')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('body')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
