<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->unsignedInteger('city_id');
            $table->string('zip_code')->default("");
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_size')->nullable();
            $table->boolean('sales')->nullable();
            $table->boolean('social')->nullable();
            $table->string('verified_at')->nullable(); // for account
            $table->unsignedInteger('credits')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
};
