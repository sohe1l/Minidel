<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('store_id')->unsigned()->index();

            $table->text('name');


            $table->enum('type', ['discount_percent']);

            $table->integer('value');
            $table->text('text');

            $table->date('start_date');
            $table->date('end_date');

            $table->enum('status', ['pending_review','approved','rejected']);

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
        Schema::drop('promos');
    }
}
