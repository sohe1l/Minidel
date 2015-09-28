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
}
