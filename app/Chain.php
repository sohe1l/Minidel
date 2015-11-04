<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{

    public function chain(){
        return $this->hasMany('\App\Store');
    }

}
