<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuOption extends Model
{
    public function options(){
        return $this->hasMany('\App\menuOptionOption');
    }

    public function items(){
        return $this->belongsToMany('\App\menuItem');
    }

}