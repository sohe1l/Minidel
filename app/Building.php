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
        return $this->belongsToMany('\App\Store');
    }

    public function coverageStores(){
        return $this->belongsToMany('\App\Store')->withPivot('min','fee','feebelowmin');;
    }

}
