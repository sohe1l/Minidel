<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertJltBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        DB::table('buildings')->insert([

            // A
            ['area_id' => '140', 'name' => 'Dubai Gate 2 Tower', 'slug'=>'dubai-gate2'],
            ['area_id' => '140', 'name' => 'Movenpick Hotel & Residence', 'slug'=>'movenpick-hotel-residence'],
            ['area_id' => '140', 'name' => 'Lake Side Residence', 'slug'=>'lake-side-residence'],

            ['area_id' => '140', 'name' => 'Wind Tower 2', 'slug'=>'wind-tower2'],
            ['area_id' => '140', 'name' => 'Lake View Tower', 'slug'=>'lake-wiew-tower'],
            ['area_id' => '140', 'name' => 'Wind Tower 1', 'slug'=>'wind-tower1'],

            ['area_id' => '140', 'name' => 'Fortune Tower', 'slug'=>'fortune-tower'],
            ['area_id' => '140', 'name' => 'Gold Crest Executive Tower', 'slug'=>'gold-crest-executive-tower'],
            ['area_id' => '140', 'name' => 'the-palladium', 'slug'=>'the-palladium'],

            ['area_id' => '140', 'name' => 'Indigo Tower', 'slug'=>'indigo-tower'],
            ['area_id' => '140', 'name' => 'Lake Terrace Tower', 'slug'=>'lake-terrace-tower'],
            ['area_id' => '140', 'name' => 'Lake City Tower', 'slug'=>'lake-city-tower'],

            ['area_id' => '140', 'name' => 'Global Lake View', 'slug'=>'global-lake-view'],
            ['area_id' => '140', 'name' => 'Al Shera Tower', 'slug'=>'al-shera-tower'],
            ['area_id' => '140', 'name' => 'Saba Tower 1', 'slug'=>'saba-tower1'],

            ['area_id' => '140', 'name' => 'Bobyan Towe', 'slug'=>'bobyan-tower'],
            ['area_id' => '140', 'name' => 'HDS Tower', 'slug'=>'hds-tower'],
            ['area_id' => '140', 'name' => 'Indigo Icon', 'slug'=>'indigo-icon'],

            //G
            ['area_id' => '140', 'name' => 'Dubai Arch Tower', 'slug'=>'dubai-arch-tower'],
            ['area_id' => '140', 'name' => 'Jumeirah Business Centre Tower 1 - JBC1', 'slug'=>'jumeirah-business-centre-tower1'],
            ['area_id' => '140', 'name' => 'G3 - Under Construction', 'slug'=>'g3'],

            ['area_id' => '140', 'name' => 'Jumeirah Business Center 8 JBC8', 'slug'=>'jumeirah-business-center8'],
            ['area_id' => '140', 'name' => 'Concorde Tower', 'slug'=>'concorde-tower'],
            ['area_id' => '140', 'name' => 'Jumeirah Business Center 7 JBC7', 'slug'=>'jumeirah-business-center7'],
            
            ['area_id' => '140', 'name' => 'Silver Tower', 'slug'=>'silver-tower'],
            ['area_id' => '140', 'name' => 'Platinum Tower', 'slug'=>'platinum-tower'],
            ['area_id' => '140', 'name' => 'Gold Tower', 'slug'=>'gold-tower'],
            
            ['area_id' => '140', 'name' => 'Gold Crest Views Tower 2', 'slug'=>'gold-crest-views-tower2'],
            ['area_id' => '140', 'name' => 'Mohammed Ibrahim Tower', 'slug'=>'mohammed-ibrahim-tower'],
            ['area_id' => '140', 'name' => 'Bonnington Hotel', 'slug'=>'bonnington-hotel'],

            //k

            //L
            ['area_id' => '140', 'name' => 'Icon Tower 2', 'slug'=>'icon-tower2'],
            ['area_id' => '140', 'name' => 'Dubai Star Tower', 'slug'=>'dubai-star-tower'],
            ['area_id' => '140', 'name' => 'Jumeirah Business Center 6 JBC6', 'slug'=>'jumeirah-business-center6'],

            //M
            ['area_id' => '140', 'name' => 'HDS Business Center', 'slug'=>'hds-business-center'],
            ['area_id' => '140', 'name' => 'Icon 1 Tower', 'slug'=>'icon1tower'],
            ['area_id' => '140', 'name' => '', 'slug'=>''],

            //N
            ['area_id' => '140', 'name' => 'The Dome Tower', 'slug'=>'the-dome-tower'],
            ['area_id' => '140', 'name' => 'Lake Point Tower', 'slug'=>'lake-point-tower'],
            ['area_id' => '140', 'name' => 'Jumeirah Business Centre 4 - JBC4', 'slug'=>'jumeirah-business-centre4-jbc4'],

            //O
            ['area_id' => '140', 'name' => 'Reef Tower', 'slug'=>'reef-tower'],
            ['area_id' => '140', 'name' => 'O2 Tower', 'slug'=>'o2-tower'],
            ['area_id' => '140', 'name' => 'Madina Tower', 'slug'=>'madina-tower'],

            ['area_id' => '140', 'name' => 'Armada Tower 1', 'slug'=>'armada-tower1'],
            ['area_id' => '140', 'name' => 'Armada Tower 2', 'slug'=>'armada-tower2'],
            ['area_id' => '140', 'name' => 'Armada Tower 3', 'slug'=>'armada-tower3'],

            ['area_id' => '140', 'name' => 'Saba Tower 3', 'slug'=>'saba-tower3'],
            ['area_id' => '140', 'name' => 'Dubai Gate 1 Tower', 'slug'=>'dubai-gate1-tower'],
            ['area_id' => '140', 'name' => 'Saba Tower 2', 'slug'=>'saba-tower2'],

            //R
            ['area_id' => '140', 'name' => 'Al Waleed Paradise', 'slug'=>'al-waleed-paradise'],
            ['area_id' => '140', 'name' => 'Mag 214', 'slug'=>'mag214'],
            ['area_id' => '140', 'name' => 'Al Saqran Tower', 'slug'=>'al-saqran-tower'],

            ['area_id' => '140', 'name' => 'Green Lakes Tower 1', 'slug'=>'green-lakes-tower1'],
            ['area_id' => '140', 'name' => 'Green Lakes Tower 2', 'slug'=>'green-lakes-tower2'],
            ['area_id' => '140', 'name' => 'Green Lakes Tower 3', 'slug'=>'green-lakes-tower3'],

            ['area_id' => '140', 'name' => 'Fortune Executive Tower', 'slug'=>'fortune-executive-tower'],
            ['area_id' => '140', 'name' => 'One Lake Plaza', 'slug'=>'one-lake-plaza'],
            ['area_id' => '140', 'name' => 'Pullman Dubai JLT', 'slug'=>'pullman-dubai-jlt'],

            ['area_id' => '140', 'name' => 'Al Seef Tower 3', 'slug'=>'al-seef-tower3'],
            ['area_id' => '140', 'name' => 'Al Seef Tower 2', 'slug'=>'al-seef-tower2'],
            ['area_id' => '140', 'name' => 'Tamweel Tower', 'slug'=>'tamweel-tower'],

            ['area_id' => '140', 'name' => 'Jumeirah Bay X1', 'slug'=>'jumeirah-bay-x1'],
            ['area_id' => '140', 'name' => 'Jumeirah Bay X2', 'slug'=>'jumeirah-bay-x2'],
            ['area_id' => '140', 'name' => 'Jumeirah Bay X3', 'slug'=>'jumeirah-bay-x3'],

            ['area_id' => '140', 'name' => 'Jumeirah Business Centre Tower 3', 'slug'=>'jumeirah-business-centre-tower3'],
            ['area_id' => '140', 'name' => 'Lake Shore Tower', 'slug'=>'lake-shore-tower'],
            ['area_id' => '140', 'name' => 'Swiss Tower', 'slug'=>'swiss-tower'],

            ['area_id' => '140', 'name' => 'Jumeirah Business Centre Tower 2', 'slug'=>'jumeirah-business-centre-tower2'],
            ['area_id' => '140', 'name' => 'Gold Crest Views Tower 1', 'slug'=>'gold-crest-views-tower1'],
            ['area_id' => '140', 'name' => 'V3 Tower', 'slug'=>'v3-tower'],

            ['area_id' => '140', 'name' => 'Jumeirah Business Centre 5', 'slug'=>'jumeirah-business-centre5'],
            ['area_id' => '140', 'name' => 'Tiffany Towers', 'slug'=>'tiffany-towers'],
            ['area_id' => '140', 'name' => 'Oaks Liwa Heights And Hotel', 'slug'=>'oaks-liwa-heights-and-hotel'],



            ['area_id' => '140', 'name' => 'Vernus Early Learning Centre', 'slug'=>'vernus-early-learning-centre'],
            ['area_id' => '140', 'name' => 'Red Diamond', 'slug'=>'red-diamond'],
            ['area_id' => '140', 'name' => 'Damas', 'slug'=>'damas'],
            ['area_id' => '140', 'name' => 'Ary Aurum Plus', 'slug'=>'ary-aurum-plus'],
            ['area_id' => '140', 'name' => 'Al Ghurair Gold Refinery', 'slug'=>'al-ghurair-gold-refinery'],
            ['area_id' => '140', 'name' => 'Emirates Gold', 'slug'=>'emirates-gold'],
            ['area_id' => '140', 'name' => 'Fancy Rose', 'slug'=>'fancy-rose'],
            ['area_id' => '140', 'name' => 'Italian M Jewellery dmcc', 'slug'=>'italian-m-jewellery'],
            ['area_id' => '140', 'name' => 'Aarzee Jewellery DMCC', 'slug'=>'aarzee-jewellery'],
            ['area_id' => '140', 'name' => 'One Jlt', 'slug'=>'one-jlt'],
            ['area_id' => '140', 'name' => 'Almas Tower', 'slug'=>'almas-tower'],

            ['area_id' => '140', 'name' => 'Mazaya AA-1', 'slug'=>'mazaya-aa1'],
            ['area_id' => '140', 'name' => 'Mazaya BB-1', 'slug'=>'mazaya-bb1'],
            ['area_id' => '140', 'name' => 'Mazaya BB-2', 'slug'=>'mazaya-bb2']




        ]);

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
