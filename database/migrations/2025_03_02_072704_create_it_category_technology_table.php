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
        Schema::create('it_category_technology', function (Blueprint $table) {
            $table->foreignId('technology_id')
                ->constrained('technologies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('it_category_id')
                ->constrained('i_t_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(['technology_id', 'it_category_id']); // Composite Primary Key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_category_technology');
    }
};
