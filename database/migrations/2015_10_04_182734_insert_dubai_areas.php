<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDubaiAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


$text = <<<'EOT'
AbuHail
Academic City
Al Awir
Al Bada
Al Baraha
Al Barari
Al Barsha 1
Al Barsha 2
Al Barsha 3
Al Barsha South
Al Buteen
Al Dhagaya
Al Fahidi Street
Al Furjan
Al Garhoud
Al Hamriya
Al Hudaiba
Al Jaddaf
Al Jafiliya
Al Karama
Al Khabisi
Al Khwaneej
Al Kifaf
Al Mamzar
Al Manara
Al Mankhool
Al Merkad
Al Mina
Al Mizhar
Al Mizhar 1
Al Mizhar 2
Al Muraqqabat
Al Murar
Al Mushrif
Al Muteena
Al Nahda 1
Al Nahda 2
Al Nasr
Al Quoz 1
Al Quoz 2
Al Quoz 3
Al Quoz 4
Al Quoz Industrial 1
Al Quoz Industrial 2
Al Quoz Industrial 3
Al Quoz Industrial 4
Al Qusais 1
Al Qusais 2
Al Qusais 3
Al Qusais Industrial 1
Al Qusais Industrial 2
Al Qusais Industrial 3
Al Qusais Industrial 4
Al Qusais Industrial 5
Al Raffa
Al Ras
Al Rashidiya
Al Rigga
Al Sabkha
Al Safa
Al Safa 1
Al Safa 2
Al Safouh 1
Al Safouh 2
Al Satwa
Al Shindagha
Al Souq Al Kabeer
Al Twar 1
Al Twar 2
Al Twar 3
Al Waheda
Al Warqa 1
Al Warqa 2
Al Warqaa
Al Wasl
Aleyas
Arabian Ranches
Ayal Nasir
Baniyas Square
Bank Street
Bu Kadra
Bur Dubai
Burj Residences
Business Bay
Corniche Deira
Creek Road
Deira
DIFC
Discovery Gardens
Downtown Burj Khalifa
Downtown Jebel Ali
Dubai Airport Freezone
Dubai Cargo Village
Dubai Design District 
Dubai Falcon City
Dubai Festival City
Dubai Health Care City
Dubai International Airport
Dubai Internet City
Dubai Investment Park
Dubai Investment Park 1
Dubai Investment Park 2
Dubai Knowledge Village
Dubai Land
Dubai Marina
Dubai Maritime City
Dubai Media City
Dubai Metal Comodities Center
Dubai Motor City
Dubai Silicon Oasis
Dubai Sport City
Dubai Techno Park
Dubai Trade Centre
Emirates Hills
Gardens
Global Village
Green Community
Greens
Hattan
Hor Al Anz
Hor Al Anz East
Ibn Batutta Mall
IMPZ
International City
Jebel Ali 1
Jebel Ali 2
Jebel Ali Free Zone
Jebel Ali Freezone Airport
Jebel Ali Gardens
Jebel Ali Industrial
Jebel Ali Village
Jumeirah
Jumeirah 1
Jumeirah 2
Jumeirah 3
Jumeirah Beach Residence JBR
Jumeirah Golf Estates
Jumeirah Heights
Jumeirah Islands
Jumeirah Lakes Towers JLT
Jumeirah Park
Jumeirah Village
Jumeirah Village Circle JVC
Jumeirah Village Triangle JVT
Lakes
Layan Community
Meadows
Meena Bazar
Mirdif
Muhaisna 1
Muhaisna 2
Muhaisna 3
Muhaisna 4
Muhaisnah
Nad Al Hammar
Nad Shamma
Nadd Al Shiba 2
Nadd Al Shiba 3
Nadd Al Shiba 4
Naif
Naser Sq
Old Town Areas
Oud Al Muteena
Oud Metha
Outsource Zone
Palm Deira
Palm Jebel Ali
Palm Jumeirah
Port Rashid
Port Saeed
Ras Al Khor
Ras Al Khor Industrial 1
Ras Al Khor Industrial 2
Ras Al Khor Industrial 3
Reemram
Remraam
Rigga Al Buteen
Satwa
Skycourts
Springs
Studio City
Tecom
Textile City
The Villa
The World
Umm Al Sheif
Umm Hurair
Umm Hurair 1
Umm Hurair 2
Umm Ramool
Umm Suqeim
Umm Suqueim 1
Umm Suqueim 2
Umm Suqueim 3
University Village - Al Ruwaiya
Victory Heights
Wadi Alamardi
Wafi Mall   Wafi Residences
Warsan 1
Warsan 2
Waterfront
Zabeel 1
Zabeel 2
EOT;
    
        $array_sql = [];

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){
            $name = trim($line);
            $slug = str_slug(trim($line));
            $array_sql[] = ['city_id' => '4', 'name' => $name, 'slug'=>$slug];
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
