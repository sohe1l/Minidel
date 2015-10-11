<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function city()
    {
        return $this->belongsTo('\App\City'); //->withTimestamps();
    }

    public function buildings()
    {
        return $this->hasMany('\App\Building'); //->withTimestamps();
    }

    public function stores()
    {
        return $this->hasMany('\App\Store')->listed(); //->withTimestamps();
    }

    public function storesWith($with)
    {
        return $this->hasMany('\App\Store')->with($with)->listed(); //->withTimestamps();
    }

    public function coverageStores(){
        return $this->belongsToMany('\App\Store')->withPivot('min','fee','feebelowmin')->listed();
    }
    

    static function listByCitySelect($city)
    {
        $areas = \App\Area::where('city_id',$city)->get();
        $arr = array();
        foreach($areas as $area)
            $arr[$area->id] = $area->name;
        return ($arr);
    }

}
