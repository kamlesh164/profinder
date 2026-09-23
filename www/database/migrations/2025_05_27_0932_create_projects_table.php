<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('service_category_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->string('customer_email');
            $table->double('budget',10,2);
            $table->string('duration')->nullable();
            $table->date('start_date');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('house_no')->nullable();
            $table->string('area')->nullable();
            $table->integer('pincode');
            $table->string('city');
            $table->string('state');
            $table->string('image');
            $table->tinyInteger('status')->default(0)->comment('0:pending,1:approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
