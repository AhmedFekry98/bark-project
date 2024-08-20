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
            $table->string(('first_name'));
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->json('extra')->default(json_encode([])); // ? this for any more fields you want add to this table.
            $table->string('verified_at')->nullable(); // for account
            $table->string('verified_email')->nullable(); // for account
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
