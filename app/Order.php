<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function store()
    {
        return $this->belongsTo('\App\Store');
    }

    public function userAddress()
    {
        return $this->belongsTo('\App\UserAddress');
    }


    public function paymentType()
    {
        return $this->belongsTo('\App\PaymentType');
    }
    
    public function orders(){
        return $this->hasMany('\App\Order');
    }

}
