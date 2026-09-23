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
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedInteger('plan_id');
            $table->string('plan_name', 255);
            $table->string('plan_price', 255);
            $table->string('expiration_date', 255);
            $table->string('expiration', 255);
            $table->string('maximum_service', 255);
            $table->string('status', 255);
            $table->string('payment_method', 255);
            $table->string('payment_status', 255);
            $table->string('transaction', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_histories');
    }
};
