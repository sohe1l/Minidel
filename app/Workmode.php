<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workmode extends Model
{

    public function timings(){
        return $this->hasMany('\App\timings');
    }

    public function asList()
    {

    }

}
