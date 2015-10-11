<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['country', 'name', 'slug', 'areacode'];

    public function areas()
    {
        return $this->hasMany('\App\Area'); //->withTimestamps();
    }

    public function stores()
    {
        return $this->hasMany('\App\Store'); //->withTimestamps();
    }

    static function listByCountry($country)
    {
        $cities = \App\City::where('country','AE')->get();
        $arr = array();
        foreach($cities as $option)
            $arr[$option->id] =  $option->name;
        return ($arr);
    }

    static function listByCountrySelect($country)
    {
        $cities = \App\City::where('country',$country)->get();
        $arr = array();
        foreach($cities as $city)
            $arr[$city->id] = $city->name;
        return ($arr);
    }

    static function listByCountrySelect2($country)
    {
        $cities = \App\City::where('country',$country)->get();
        $arr = array();
        foreach($cities as $city)
            $arr[] = ['id' =>$city->id, 'text'=>$city->name];
        return ($arr);
    }
}
