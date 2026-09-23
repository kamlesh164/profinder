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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('service_id')->constrained('services');
            $table->enum('property_type',['apartment','villa']);
            $table->date('slot_date');
            $table->time('slot_time');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->double('total_amount',2);
            $table->integer('admin_commission_percent');
            $table->double('admin_commission',2);
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
