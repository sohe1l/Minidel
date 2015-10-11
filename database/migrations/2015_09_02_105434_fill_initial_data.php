<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillInitialData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert(array( 'name' => 'store_owner'));
        DB::table('roles')->insert(array( 'name' => 'store_manager'));
        DB::table('roles')->insert(array( 'name' => 'store_staff'));

        // Insert some cities
        /*
        DB::table('cities')->insert(
            array(
                'areacode' => '1',
                'country' => '',
                'name' => '',
                'slug' => ''
            )
        );
        

        // Insert some cities
        DB::table('areas')->insert(
            array(
                'city_id' => '1',
                'name' => 'Not Available',
                'slug' => 'na'
            )
        );



        // Insert some cities
        DB::table('buildings')->insert(
            array(
                'area_id' => '1',
                'name' => 'Not Available',
                'slug' => 'na'
            )
        );

*/





    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
