<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = ['name', 'phone', 'country', 'city_id', 'area_id', 'building_id', 'unit', 'info'];



    public static function boot()
    {
        parent::boot();
        static::creating(function($address){
            $address->name = ucwords($address->name);
        });
    }


    public function building()
    {
        return $this->belongsTo('\App\Building'); //->withTimestamps();
    }

    public function area()
    {
        return $this->belongsTo('\App\Area'); //->withTimestamps();
    }

    public function city()
    {
        return $this->belongsTo('\App\City'); //->withTimestamps();
    }


    public function orders()
    {
        return $this->hasMany('\App\Order'); //->withTimestamps();
    }

}
