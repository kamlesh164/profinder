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
        Schema::create('service_professional', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 255);
            $table->enum('gender', ['Male', 'Female', 'Other', ''])->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->tinyInteger('vendor_type')->default(1)->comment('1:Manufacturer,2:Suppliers, 3:Dealer');
            $table->tinyInteger('is_active')->default(1);
            $table->string('image', 255)->nullable();
            $table->string('otp', 255)->nullable();
            $table->tinyInteger('otp_status')->default(0);
            $table->string('otp_valid_till', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_professional');
    }
};
