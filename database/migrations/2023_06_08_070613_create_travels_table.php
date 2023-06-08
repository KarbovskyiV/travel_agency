<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->boolean('is_public');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->integer('number_of_days');
            $table->integer('number_of_nights')->virtualAs('number_of_days - 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
