<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('country')->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('area_id')->unsigned()->nullable();
            $table->integer('building_id')->unsigned()->nullable();
            $table->text('address')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->integer('comission')->default(10);
            $table->string('status_listing')->default(0);
            $table->enum('status_working', ['open', 'close', 'busy'])->default('open');
            $table->text('info')->nullable();
            $table->text('coordinate')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->timestamps();

            //$table->foreign('city_id')->references('id')->on('cities');
            //$table->foreign('area_id')->references('id')->on('areas');
            //$table->foreign('building_id')->references('id')->on('buildings');
        });


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stores');
    }
}
