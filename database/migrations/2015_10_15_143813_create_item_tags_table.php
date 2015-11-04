<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_tags', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->enum('store_type', ['Food','Groceries','Laundry','Pharmacy','Flower']);

            $table->timestamps();
        });
    


        Schema::create('item_tag_menu_item', function(Blueprint $table)
        {
            $table->integer('menu_item_id')->unsigned()->index();
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');

            $table->integer('item_tag_id')->unsigned()->index();
            $table->foreign('item_tag_id')->references('id')->on('item_tags')->onDelete('cascade');

            $table->timestamps();
        });


        //Middle Eastern    Arabian   Asian
        DB::table('item_tags')->insert([

            ['store_type' => 'Food', 'name' => 'Spicy'],
            ['store_type' => 'Food', 'name' => 'Vegetarian'],
            ['store_type' => 'Food', 'name' => 'Vegan'],
            ['store_type' => 'Food', 'name' => 'Gluten free'],
            ['store_type' => 'Food', 'name' => 'Pork'],
            ['store_type' => 'Food', 'name' => 'Nut or Peanut Allergies'],

            ]);















    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('itemtags');
        Schema::drop('item_tag_menu_item');
    }
}
