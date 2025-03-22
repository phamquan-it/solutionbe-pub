<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->text('icon')->change();
        });
    }

    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->string('icon', 255)->change();
        });
    }
};
