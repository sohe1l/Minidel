<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = ['name', 'phone', 'country', 'city_id', 'area_id', 'building_id', 'unit', 'info'];


    public function building()
    {
        return $this->belongsTo('\App\Building'); //->withTimestamps();
    }

    public function area()
    {
        return $this->belongsTo('\App\Area'); //->withTimestamps();
    }


}
