<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuSection extends Model
{
    protected $fillable = ['title'];

    public function store(){
        return $this->belongsTo('\App\Store');
    }

    public function items(){
        return $this->hasMany('\App\menuItem');
    }

}
