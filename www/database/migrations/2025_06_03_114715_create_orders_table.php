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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('timeslot')->nullable();
            $table->date('booking_date');
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 6);
            $table->double('total_amount',10,2);
            $table->string('payment_method')->nullable();
            $table->string('payment_status');
            $table->string('transaction_id');
            $table->tinyInteger('order_status')->default(0);
            $table->integer('cart_qty');
            $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->double('price',10,2);
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
    }
};
