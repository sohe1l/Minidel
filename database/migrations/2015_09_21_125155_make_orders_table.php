<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned()->index();
            $table->integer('user_address_id')->unsigned();
            

            $table->integer('store_id')->unsigned()->index();
            $table->integer('payment_type_id')->unsigned();

            $table->timestamp('schedule')->nullable();
            $table->enum('type', ['delivery', 'pickup']);
            $table->enum('status', ['pending', 'accepted', 'delivering', 'delivered', 'canceled', 'rejected'])->default('pending');
            $table->boolean('hidden_store');
            $table->boolean('hidden_user');
            $table->boolean('callback');
            $table->text('instructions')->nullable();
            $table->text('cart')->nullable();
            $table->text('reason')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->integer('discount')->unsigned();
            $table->enum('discount_type', ['percent', 'amount'])->default('percent');

            $table->integer('promo_id')->unsigned()->nullable()->index();

            $table->integer('call_count')->unsigned();
            $table->timestamp('call_last')->nullable();

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
        Schema::drop('orders');
    }
}
