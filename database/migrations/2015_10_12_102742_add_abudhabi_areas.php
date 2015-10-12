<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAbudhabiAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

$text = <<<'EOT'
Abu Dhabi Industrial Area
Airport Road
Al Aman
Al Bahia
Al Bandar
Al Dafrah
Al Falah City
Al Hosn
Al Ittihad
Al Karamah
Al Khalidiyah
Al Khubeirah
Al Madina Al Riyadiya
Al Mafraq
Al Manhal
Al Maqtaa
Al Mariah
Al Markaziyah West
Al Markaziyah
Al Matar
Al Meena
Al Muneera
Al Muroor
Al Musalla
Al Mushrif
Al Muzoon
Al Nahyan
Al Qubesat
Al Rahba
Al Reef
Al Rehhan
Al Rowdah
Al Safarrat
Al Samha 1
Al Samha 2
Al Shamkha
Al Shawamekh
Al Wahdah
Al Wathba 
Al Zaab
Al Zahraa
Al Zeina
As Suwwah Island
Bain Al Jesrain
Baniyas
Belghailam
BreakWater
Cornishe Road
Crescent Island
Dhow Harbour
Ghabath
Hadbat Al Zafranah
Hydra City
Khor Al Bateen
Lulu Island
Madinat Khalifa A
Madinat Khalifa B
Madinat Khalifa C
Madinat Zayed
Masdar City
Mohammed Bin Zayed City
Muroor Road
Mussafah Industrial Area 1
Mussafah Industrial Area 2
Mussafah Industrial Area 3
Mussafah Industrial Area 4
Mussafah Industrial Area 5
Mussafah Industrial Area 6
Mussafah Industrial city
New Shahama
Officers City
Police Officers City
Port Zayed
Qasr El Bahr
Qasr El Shantie
Raha Beach Road
Ras Al Akhdar
Reem Island
Saadiyat Island
Sas Al Nakhl Island
Shahama
Tourist Club Area
Umm Al Nar
Yas Island
EOT;
    
        $array_sql = [];

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){
            $name = trim($line);
            $slug = str_slug(trim($line));
            $array_sql[] = ['city_id' => '1', 'name' => $name, 'slug'=>$slug];
        } 

        DB::table('areas')->insert($array_sql);


        //change slug for
        //jlt
        //jvc
        //jvt
        //jbr





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
