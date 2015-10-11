<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->unsigned();
            $table->string('code', 2);
            $table->string('slug');
            $table->string('currency_name');
            $table->string('currency_sign');
            $table->timestamps();
        });


        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('areacode')->unsigned();
            $table->string('country', 2);
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->timestamps();
        });



        DB::table('cities')->insert([
            ['areacode' => '2', 'country' => 'AE', 'name'=>'Abu Dhabi', 'slug'=>'abu-dhabi'],
            ['areacode' => '3', 'country' => 'AE', 'name'=>'Al Ain', 'slug'=>'al-ain'],
            ['areacode' => '6', 'country' => 'AE', 'name'=>'Ajman', 'slug'=>'ajman'],
            ['areacode' => '4', 'country' => 'AE', 'name'=>'Dubai', 'slug'=>'dubai'],
            ['areacode' => '9', 'country' => 'AE', 'name'=>'Fujairah', 'slug'=>'fujairah'],
            ['areacode' => '7', 'country' => 'AE', 'name'=>'Ras al-Khaimah', 'slug'=>'ras-al-khaimah'],
            ['areacode' => '6', 'country' => 'AE', 'name'=>'Sharjah', 'slug'=>'sharjah'],
            ['areacode' => '6', 'country' => 'AE', 'name'=>'Umm al-Quwain', 'slug'=>'umm-al-quwain']
        ]);






    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
        Schema::drop('cities');
    }
}
