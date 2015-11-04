<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_times', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index();
            $table->integer('order_id')->index();
            $table->integer('store_id')->index();
            
            $table->enum('status', ['pending', 'accepted', 'delivering', 'delivered', 'canceled', 'rejected']);
            
            $table->integer('timestamp')->unsigned();

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
        Schema::drop('times');
    }
}
