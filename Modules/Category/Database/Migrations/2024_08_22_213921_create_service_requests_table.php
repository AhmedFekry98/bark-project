<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Category\Entities\ServiceRequest;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('hired_id')->nullable();
            $table->json('questions_data');
            $table->integer('total_credits')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->enum('status', ServiceRequest::$statuses);
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
        Schema::dropIfExists('service_requests');
    }
};
