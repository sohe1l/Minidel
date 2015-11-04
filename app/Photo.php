<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    protected $fillable = ['path','text', 'user_id', 'type', 'order'];

    public function imageable()
    {
        return $this->morphTo();
    }



}
