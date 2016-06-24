<?php

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
            $table->string('username')->nullable()->default(null)->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password', 60)->nullable();
            
            $table->string('mobile')->nullable();
            $table->char('gender',1);
            $table->date('dob');

            $table->boolean('verified_email')->default(false);
            $table->boolean('verified_mobile')->default(false);

            $table->string('dp')->nullable();

            $table->string('provider')->nullable();
            $table->string('provider_id')->unique()->nullable();


            $table->string('confirmation_code')->nullable();
            $table->rememberToken();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
