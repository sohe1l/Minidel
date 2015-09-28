<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('workmode_id')->unsigned();
            $table->char('day', 3);
            $table->time('start');
            $table->time('end');
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        Schema::create('workmodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });


/*
        Schema::create('timing_workmode', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('timing_id')->unsigned()->index();
            $table->foreign('timing_id')->references('id')->on('timings')->onDelete('cascade');
         
            $table->integer('workmode_id')->unsigned()->index();
            $table->foreign('workmode_id')->references('id')->on('workmodes')->onDelete('cascade');

            $table->timestamps();
        });
*/

        DB::table('workmodes')->insert(array( 'name' => 'Building Delivery'));
        DB::table('workmodes')->insert(array( 'name' => 'Area Delivery'));

        DB::table('workmodes')->insert(array( 'name' => 'Ramadan Building Delivery'));
        DB::table('workmodes')->insert(array( 'name' => 'Ramadan Area Delivery'));

        DB::table('workmodes')->insert(array( 'name' => 'Normal Openning')); // used for pick up
        DB::table('workmodes')->insert(array( 'name' => 'Ramadan Openning')); // used for pick up orders

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('timings');
        Schema::drop('workmodes');
       // Schema::drop('timing_workmode');
    }
}
