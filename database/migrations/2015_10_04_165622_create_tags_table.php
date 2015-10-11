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

            $table->timestamps();
        });



        Schema::create('store_tag', function(Blueprint $table)
        {
            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->timestamps();
        });


        //Middle Eastern    Arabian   Asian
        DB::table('tags')->insert([
            ['type' => 'cuisine', 'name' => 'French'],
            ['type' => 'cuisine', 'name' => 'European'],
            ['type' => 'cuisine', 'name' => 'American'],
            ['type' => 'cuisine', 'name' => 'Indian'],
            ['type' => 'cuisine', 'name' => 'Thai'],
            ['type' => 'cuisine', 'name' => 'North Indian'],
            ['type' => 'cuisine', 'name' => 'Australian'],
            ['type' => 'cuisine', 'name' => 'Mediterranean'],
            ['type' => 'cuisine', 'name' => 'Persian'],
            ['type' => 'cuisine', 'name' => 'Japanese'],
            ['type' => 'cuisine', 'name' => 'Italian'],
            ['type' => 'cuisine', 'name' => 'Mughlai'],
            ['type' => 'cuisine', 'name' => 'Mexican'],
            ['type' => 'cuisine', 'name' => 'Portuguese'],
            ['type' => 'cuisine', 'name' => 'African'],
            ['type' => 'cuisine', 'name' => 'Lebanese'],
            ['type' => 'cuisine', 'name' => 'Bengali'],
            ['type' => 'cuisine', 'name' => 'Tunisian'],
            ['type' => 'cuisine', 'name' => 'Peruvian'],
            ['type' => 'cuisine', 'name' => 'Gujarati'],
            ['type' => 'cuisine', 'name' => 'Tex-Mex'],
            ['type' => 'cuisine', 'name' => 'Emirati'],
            ['type' => 'cuisine', 'name' => 'Filipino'],
            ['type' => 'cuisine', 'name' => 'Contemporary'],
            ['type' => 'cuisine', 'name' => 'Singaporean'],
            ['type' => 'cuisine', 'name' => 'Syrian'],
            ['type' => 'cuisine', 'name' => 'Chinese'],
            ['type' => 'cuisine', 'name' => 'Kerala'],
            ['type' => 'cuisine', 'name' => 'Pakistani'],
            ['type' => 'cuisine', 'name' => 'Afghani'],
            ['type' => 'cuisine', 'name' => 'Vietnamese'],
            ['type' => 'cuisine', 'name' => 'Korean'],
            ['type' => 'cuisine', 'name' => 'Russian'],
            ['type' => 'cuisine', 'name' => 'Spanish'],
            ['type' => 'cuisine', 'name' => 'Maharashtrian'],
            ['type' => 'cuisine', 'name' => 'Egyptian'],
            ['type' => 'cuisine', 'name' => 'Goan'],
            ['type' => 'cuisine', 'name' => 'Rajasthani'],
            ['type' => 'cuisine', 'name' => 'Chettinad'],
            ['type' => 'cuisine', 'name' => 'Uzbek'],
            ['type' => 'cuisine', 'name' => 'Brazilian'],
            ['type' => 'cuisine', 'name' => 'Kashmiri'],
            ['type' => 'cuisine', 'name' => 'Parsi'],
            ['type' => 'cuisine', 'name' => 'Sri Lankan'],
            ['type' => 'cuisine', 'name' => 'Bangladeshi'],
            ['type' => 'cuisine', 'name' => 'Ethiopian'],
            ['type' => 'cuisine', 'name' => 'Nepalese'],
            ['type' => 'cuisine', 'name' => 'British'],
            ['type' => 'cuisine', 'name' => 'Latin American'],
            ['type' => 'cuisine', 'name' => 'Turkish'],
            ['type' => 'cuisine', 'name' => 'International'],
            ['type' => 'cuisine', 'name' => 'German'],
            ['type' => 'cuisine', 'name' => 'Malaysian'],
            ['type' => 'cuisine', 'name' => 'Indonesian'],
            ['type' => 'cuisine', 'name' => 'Continental'],
            ['type' => 'cuisine', 'name' => 'South Indian'],
            ['type' => 'cuisine', 'name' => 'Moroccan'],
            ['type' => 'cuisine', 'name' => 'Hyderabadi'],
            ['type' => 'cuisine', 'name' => 'Greek'],
            ['type' => 'cuisine', 'name' => 'Belgian'],
            ['type' => 'cuisine', 'name' => 'Argentine'],
            ['type' => 'cuisine', 'name' => 'Irish'],
            ['type' => 'cuisine', 'name' => 'Cuban'],








            ['type' => 'dish', 'name' => 'Cake'],
            ['type' => 'dish', 'name' => 'Crepe'],
            ['type' => 'dish', 'name' => 'Pancake'],
            ['type' => 'dish', 'name' => 'Ice Cream'],
            ['type' => 'dish', 'name' => 'Frozen Yogurt'],
            ['type' => 'dish', 'name' => 'Cookies'],
            ['type' => 'dish', 'name' => 'Burger'],
            ['type' => 'dish', 'name' => 'Pizza'],
            ['type' => 'dish', 'name' => 'Seafood'],
            ['type' => 'dish', 'name' => 'Sushi'],
            ['type' => 'dish', 'name' => 'Kebab'],
            ['type' => 'dish', 'name' => 'Sandwich'],
            ['type' => 'dish', 'name' => 'Drinks'],
            ['type' => 'dish', 'name' => 'Biryani'],
            ['type' => 'dish', 'name' => 'Coffee'],
            ['type' => 'dish', 'name' => 'Tea'],
            ['type' => 'dish', 'name' => 'Juice'],
            ['type' => 'dish', 'name' => 'Healthy'],
            ['type' => 'dish', 'name' => 'Mandi'],
            ['type' => 'dish', 'name' => 'Bubble Tea'],
            ['type' => 'dish', 'name' => 'Tapas'],
            ['type' => 'dish', 'name' => 'Noodle'],
            ['type' => 'dish', 'name' => 'Steak'],
            ['type' => 'dish', 'name' => 'Hotdog'],
            ['type' => 'dish', 'name' => 'BBQ'],
            ['type' => 'dish', 'name' => 'Soup'],
            ['type' => 'dish', 'name' => 'Shawerma'],


            ['type' => 'store', 'name' => 'Casual Dining'],
            ['type' => 'store', 'name' => 'Fine Dining'],
            ['type' => 'store', 'name' => 'Cafeteria'],
            ['type' => 'store', 'name' => 'Cafe'],
            ['type' => 'store', 'name' => 'Bakery'],
            ['type' => 'store', 'name' => 'Fast Food'],
            ['type' => 'store', 'name' => 'Ice Cream Parlor'],
            ['type' => 'store', 'name' => 'Streak House'],
            ['type' => 'store', 'name' => 'Pizzeria'],
            ['type' => 'store', 'name' => 'Kiosk'],
            ['type' => 'store', 'name' => 'Bar'],



            ['type' => 'feature', 'name' => 'Accept Cards'],
            ['type' => 'feature', 'name' => 'Indoor Smoking'],
            ['type' => 'feature', 'name' => 'Outdoor seating'],
            ['type' => 'feature', 'name' => 'Shisha'],
            ['type' => 'feature', 'name' => 'Alcohol'],
            ['type' => 'feature', 'name' => 'Sports bar'],
            ['type' => 'feature', 'name' => 'Live Music'],
            ['type' => 'feature', 'name' => 'Wifi'],


            ['type' => 'offer', 'name' => 'Happy Hour'],
            ['type' => 'offer', 'name' => 'Ladies Night'],


            ['type' => 'event', 'name' => 'Buffet'],
            ['type' => 'event', 'name' => 'Brunch'],



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



  




















