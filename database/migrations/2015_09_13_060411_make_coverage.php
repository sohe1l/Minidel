<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCoverage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('building_store', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
         
            $table->integer('building_id')->unsigned()->index();
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');

            $table->integer('min')->unsigned();
            $table->integer('fee')->unsigned();
            $table->integer('feebelowmin')->unsigned();

            $table->integer('discount')->unsigned();
            $table->enum('discount_type', ['percent', 'amount'])->default('percent');
            
            $table->timestamps();
        });


        Schema::create('area_store', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
         
            $table->integer('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');

            $table->integer('min')->unsigned();
            $table->integer('fee')->unsigned();
            $table->integer('feebelowmin')->unsigned();

            $table->integer('discount')->unsigned();
            $table->enum('discount_type', ['percent', 'amount'])->default('percent');

            
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
        Schema::drop('building_store');
        Schema::drop('area_store');
    }
}
