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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->boolean('vendor_type')->default(1)->comment('1:Manufacturer,2:Suppliers, 3:Dealer');
            $table->boolean('is_active')->default(1);
            $table->string('otp')->nullable();
            $table->boolean('otp_status')->default(0);
            $table->string('otp_valid_till')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('vendor_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendorid')->constrained('vendors')->onDelete('cascade');
            $table->string('adhar_front')->nullable();
            $table->string('adhar_back')->nullable();
            $table->string('pancard')->nullable();
            $table->string('company_name')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('gst_doc')->nullable();
            $table->string('license')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:pending,1:approved,2:rejected');
            $table->string('reject_reason')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('vendor_kycs');
    }
};
