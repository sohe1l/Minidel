<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    public function user(){
        return $this->belongsTo('\App\User');
    }

    public function store(){
        return $this->belongsTo('\App\Store');
    }

    public function scopeLatest($query){
        return $query->orderBy('id','desc');
    }

}
