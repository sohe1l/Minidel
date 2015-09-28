<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->unsigned();
            $table->string('code', 2);
            $table->string('slug');
            $table->string('currency_name');
            $table->string('currency_sign');
            $table->timestamps();
        });


        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('areacode')->unsigned();
            $table->string('country', 2);
            $table->string('name');
            $table->string('slug')->unique()->index();
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
        Schema::drop('countries');
        Schema::drop('cities');
    }
}
