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
        Schema::create('language_technology', function (Blueprint $table) {
            $table->foreignId('technology_id')
                ->constrained('technologies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('language_id')
                ->constrained('languages')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['technology_id', 'language_id']); // Composite Primary Key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_technology');
    }
};
