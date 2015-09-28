<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    protected $fillable = ['workmode_id', 'day', 'start', 'end'];


    public function store(){
        return $this->belongsTo('\App\Store');
    }

    public function workmode(){
        return $this->belongsTo('\App\Workmode');
    }


    public function scopeSortByDay($query)
    {
        return $query->orderByRaw(\DB::raw("FIELD(day, 'sun','mon','tue','wed','thu','fri','sat')"));
    }




}
