<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function area()
    {
        return $this->belongsTo('\App\Area'); //->withTimestamps();
    }

    public function stores()
    {
        return $this->hasMany('\App\Store')->listed(); //->withTimestamps();
    }

    public function coverageStores(){
        return $this->belongsToMany('\App\Store')->withPivot('min','fee','feebelowmin','discount')->listed();
    }

    static function listByAreaSelect($area)
    {
        $blds = \App\Building::where('area_id',$area)->get();
        $arr = array();
        foreach($blds as $bld)
            $arr[$bld->id] = $bld->name;
        return ($arr);
    }


}
