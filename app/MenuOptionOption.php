<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuOptionOption extends Model
{
    public function menuOption(){
        return $this->belongsTo('\App\menuOption');
    }


}
