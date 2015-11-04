<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('slug');

            $table->timestamps();
        });


        Schema::create('payment_type_store', function(Blueprint $table)
        {
            $table->integer('payment_type_id')->unsigned()->index();
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');

            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->timestamps();
        });


        DB::table('payment_types')->insert([
            ['slug' => 'cash', 'name' => 'Cash'],
            ['slug' => 'card_delivery', 'name' => 'Card on Delivery'],
            ['slug' => 'card', 'name' => 'Credit Card (online)'],
            // ['slug' => 'paypal', 'name' => 'Paypal'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_types');
        Schema::drop('payment_type_store');
    }
}
