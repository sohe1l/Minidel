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
            $table->string('slug')->unique()->index();
            $table->enum('type', ['Food','Groceries','Laundry','Pharmacy','Flower'])->default('Food');
            $table->string('country')->nullable();
            $table->integer('city_id')->unsigned()->nullable()->index();
            $table->integer('area_id')->unsigned()->nullable()->index();
            $table->integer('building_id')->unsigned()->nullable()->index();
            $table->text('address')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->integer('comission')->default(10);
            $table->enum('status_listing', ['published', 'draft', 'review'])->default('draft');
            $table->enum('status_working', ['open', 'close', 'busy'])->default('open');
            $table->boolean('accept_orders')->default(1);
            $table->text('info')->nullable();
            $table->text('coordinate')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->integer('chain_id')->nullable();
            $table->timestamp('last_check')->default(0);
            $table->timestamps();

            //$table->unique( array('slug','area_id') );

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
