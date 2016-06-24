<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->enum('type', ['cuisine', 'dish', 'store', 'feature', 'offer', 'event']);
            $table->enum('store_type', ['Food','Groceries','Laundry','Pharmacy','Flower']);

            $table->nullableTimestamps();
        });



        Schema::create('store_tag', function(Blueprint $table)
        {
            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->nullableTimestamps();
        });


        //Middle Eastern    Arabian   Asian
        DB::table('tags')->insert([
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'French'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'European'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'American'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Indian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Thai'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'North Indian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Australian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Mediterranean'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Persian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Japanese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Italian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Mughlai'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Mexican'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Portuguese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'African'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Lebanese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Bengali'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Tunisian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Peruvian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Gujarati'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Tex-Mex'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Emirati'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Filipino'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Contemporary'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Singaporean'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Syrian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Chinese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Kerala'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Pakistani'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Afghani'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Vietnamese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Korean'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Russian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Spanish'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Maharashtrian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Egyptian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Goan'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Rajasthani'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Chettinad'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Uzbek'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Brazilian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Kashmiri'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Parsi'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Sri Lankan'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Bangladeshi'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Ethiopian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Nepalese'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'British'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Latin American'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Turkish'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'International'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'German'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Malaysian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Indonesian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Continental'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'South Indian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Moroccan'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Hyderabadi'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Greek'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Belgian'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Argentine'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Irish'],
            ['store_type' => 'Food', 'type' => 'cuisine', 'name' => 'Cuban'],


            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Cake'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Crepe'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Pancake'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Ice Cream'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Frozen Yogurt'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Cookies'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Burger'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Pizza'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Seafood'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Sushi'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Kebab'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Sandwich'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Drinks'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Biryani'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Coffee'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Tea'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Juice'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Healthy'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Mandi'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Bubble Tea'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Tapas'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Noodle'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Steak'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Hotdog'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'BBQ'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Soup'],
            ['store_type' => 'Food', 'type' => 'dish', 'name' => 'Shawerma'],


            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Casual Dining'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Fine Dining'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Cafeteria'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Cafe'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Bakery'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Fast Food'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Ice Cream Parlor'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Streak House'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Pizzeria'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Kiosk'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Bar'],
            ['store_type' => 'Food', 'type' => 'store', 'name' => 'Sports bar'],


            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Accept Cards'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Indoor Smoking'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Outdoor seating'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Shisha'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Alcohol'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Live Music'],
            ['store_type' => 'Food', 'type' => 'feature', 'name' => 'Wifi'],


            ['store_type' => 'Food', 'type' => 'offer', 'name' => 'Happy Hour'],
            ['store_type' => 'Food', 'type' => 'offer', 'name' => 'Ladies Night'],


            ['store_type' => 'Food', 'type' => 'event', 'name' => 'Buffet'],
            ['store_type' => 'Food', 'type' => 'event', 'name' => 'Brunch'],

            ]);




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
        Schema::drop('store_tag');
    }
}






// 



  




















