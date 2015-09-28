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
        return $this->hasMany('\App\Store'); //->withTimestamps();
    }

    public function coverageStores(){
        return $this->belongsToMany('\App\Store')->withPivot('min','fee','feebelowmin');;
    }
    

}
