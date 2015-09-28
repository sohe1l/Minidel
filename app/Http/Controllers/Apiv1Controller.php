<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Apiv1Controller extends Controller
{
    
    function listCities($countrySlug){

        $array_cities = array();

        $cities = \App\City::where('country', $countrySlug)->get();

        if($cities)
            foreach($cities as $city)
                $array_cities[] = ['id' => $city->id, 'text' => $city->name];
        
        return $array_cities;
    }

    function listAreas($countrySlug, $cityID){

        $array_areas = array();

        $city = \App\City::where('country', $countrySlug)->where('id',$cityID)->first();

        if($city)
            foreach($city->areas as $area)
                $array_areas[] = ['id' => $area->id, 'text' => $area->name];
        
        return $array_areas;
    }

    function listBuildings($countrySlug, $cityID, $areaID){

        $arr = array();

        $city = \App\City::where('country', $countrySlug)->where('id',$cityID)->first();

        if($city)
            $area = $city->areas()->where('id',$areaID)->first();
        
        if($area)
            foreach($area->buildings as $building)
                $arr[] = ['id' => $building->id, 'text' => $building->name];
        
        return $arr;
    }

}
