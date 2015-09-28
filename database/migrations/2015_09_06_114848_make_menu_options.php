<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMenuOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->string('name');
            $table->enum('type', ['radio', 'select']);
            $table->integer('min')->unsigned();
            $table->integer('max')->unsigned();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        Schema::create('menu_option_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_option_id')->unsigned()->index();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('menu_option_id')->references('id')->on('menu_options')->onDelete('cascade');
        });

        Schema::create('menu_item_menu_option', function (Blueprint $table) { // pivot table for menu
            $table->increments('id');

            $table->integer('menu_option_id')->unsigned()->index();
            $table->foreign('menu_option_id')->references('id')->on('menu_options')->onDelete('cascade');
         
            $table->integer('menu_item_id')->unsigned()->index();
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');

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
        Schema::drop('menu_items_menu_options');
        Schema::drop('menu_options_option');
        Schema::drop('menu_options');
    }
}
