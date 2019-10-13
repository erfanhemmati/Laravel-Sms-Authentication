<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->bigInteger('phone')->unique();
            $table->string('password');
            $table->string('verification_code')->unique()->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('national_code')->unique()->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('card_number')->nullable();
            $table->string('level')->default('user');
            $table->boolean('status')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
}
