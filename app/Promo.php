<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Promo extends Model
{



    public function store(){
        return $this->belongsTo('\App\Store');
    }


    public function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=' , Carbon::now())->whereDate('end_date', '>=' , Carbon::now());
    }


}
