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
        Schema::create('square_feet', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('from_area');
            $table->integer('to_area');
            $table->decimal('price', 10, 2);
            $table->integer('price_multiply')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('square_feet');
    }
};
