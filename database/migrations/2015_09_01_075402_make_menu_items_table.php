<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('store_id')->unsigned(); no need
            $table->integer('menu_section_id')->unsigned();
            $table->string('title');
            $table->string('info')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('photo')->nullable();
            $table->integer('order');
            $table->boolean('available')->default(true);
            $table->timestamps();

            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');;
            $table->foreign('menu_section_id')->references('id')->on('menu_sections')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_items');
    }
}
