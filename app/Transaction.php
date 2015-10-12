<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public function user()
    {
        return $this->belongsTo('\App\User'); //->withTimestamps();
    }

    public function store()
    {
        return $this->belongsTo('\App\Store'); //->withTimestamps();
    }
    


    public function getAmountAttribute($value)
    {
        return ($value / 100);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['Amount'] = $value*100;
    }



}
